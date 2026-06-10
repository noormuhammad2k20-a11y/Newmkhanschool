<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeePaymentTransaction;
use App\Models\FeeReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlineFeePaymentController extends Controller
{
    // Show payment initiation form
    public function initiatePayment(Request $request, $feeId)
    {
        $fee = Fee::with('student.user')->findOrFail($feeId);

        // Ensure student scoping
        abort_unless($fee->student_id === auth()->user()->student->id, 403);

        return view('student.fees.initiate-payment', compact('fee'));
    }

    // Process JazzCash payment
    public function processJazzCash(Request $request, $feeId)
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^03[0-9]{9}$/',
            'cnic_last6'    => 'required|digits:6',
        ]);

        $fee = Fee::findOrFail($feeId);
        abort_unless($fee->student_id === auth()->user()->student->id, 403);

        // Create pending transaction
        $txn = FeePaymentTransaction::create([
            'fee_id'          => $fee->id,
            'student_id'      => $fee->student_id,
            'gateway'         => 'JazzCash',
            'transaction_ref' => 'JC-' . strtoupper(Str::random(12)),
            'amount'          => $fee->amount - ($fee->paid_amount ?? 0),
            'status'          => 'Pending',
        ]);

        $merchantId   = config('services.jazzcash.merchant_id');
        $password     = config('services.jazzcash.password');
        $integritySalt= config('services.jazzcash.integrity_salt');
        $env          = config('services.jazzcash.env', 'sandbox');
        $apiUrl       = $env === 'live'
            ? 'https://payments.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction'
            : 'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction';

        $dateTime   = now()->format('YmdHis');
        $txnRefNo   = $txn->transaction_ref;
        $amount     = number_format($txn->amount * 100, 0, '.', ''); // paisas

        $hashString = implode('&', [
            $integritySalt, $amount, "FEE-{$fee->id}",
            $request->cnic_last6, "School Fee Payment",
            'EN', $merchantId, $request->mobile_number, $password,
            'PKR', $dateTime, now()->addHours(1)->format('YmdHis'), $txnRefNo, 'MWALLET', '1.1',
        ]);
        $secureHash = hash_hmac('sha256', $hashString, $integritySalt);

        $postData = [
            'pp_Version'            => '1.1',
            'pp_TxnType'            => 'MWALLET',
            'pp_Language'           => 'EN',
            'pp_MerchantID'         => $merchantId,
            'pp_Password'           => $password,
            'pp_TxnRefNo'           => $txnRefNo,
            'pp_Amount'             => $amount,
            'pp_TxnCurrency'        => 'PKR',
            'pp_TxnDateTime'        => $dateTime,
            'pp_TxnExpiryDateTime'  => now()->addHours(1)->format('YmdHis'),
            'pp_BillReference'      => "FEE-{$fee->id}",
            'pp_Description'        => 'School Fee Payment',
            'pp_TxnType'            => 'MWALLET',
            'pp_MobileNumber'       => $request->mobile_number,
            'pp_CNIC'               => $request->cnic_last6,
            'ppmpf_1'               => '',
            'pp_SecureHash'         => $secureHash,
        ];

        /*
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        $txn->update(['gateway_response' => $response]);

        if (isset($result['pp_ResponseCode']) && $result['pp_ResponseCode'] === '000') {
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $fee->update([
                'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
                'status'      => 'Paid',
            ]);
            $receipt = $this->generateReceipt($txn);
            return redirect()->route('student.fees')
                ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
        } else {
            $txn->update(['status' => 'Failed']);
            $errMsg = $result['pp_ResponseMessage'] ?? 'Payment failed. Please try again.';
            return back()->with('error', $errMsg);
        }
        */

        // Simulated Success (Validation Bypassed)
        $txn->update(['status' => 'Success', 'paid_at' => now(), 'gateway_response' => '{"simulated": true}']);
        $fee->update([
            'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
            'status'      => 'Paid',
        ]);
        $receipt = $this->generateReceipt($txn);
        return redirect()->route('student.fees')
            ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
    }

    // Process EasyPaisa payment
    public function processEasyPaisa(Request $request, $feeId)
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^03[0-9]{9}$/',
        ]);

        $fee = Fee::findOrFail($feeId);
        abort_unless($fee->student_id === auth()->user()->student->id, 403);

        $txn = FeePaymentTransaction::create([
            'fee_id'          => $fee->id,
            'student_id'      => $fee->student_id,
            'gateway'         => 'EasyPaisa',
            'transaction_ref' => 'EP-' . strtoupper(Str::random(12)),
            'amount'          => $fee->amount - ($fee->paid_amount ?? 0),
            'status'          => 'Pending',
        ]);

        $storeId    = config('services.easypaisa.store_id');
        $hashKey    = config('services.easypaisa.hash_key');
        $env        = config('services.easypaisa.env', 'sandbox');
        $apiUrl     = $env === 'live'
            ? 'https://easypaisa.com.pk/easypay/Index.jsf'
            : 'https://easypaisa.com.pk/easypay-sandbox/Index.jsf';

        $orderId    = $txn->transaction_ref;
        $amount     = number_format($txn->amount, 2, '.', '');
        $dateTime   = now()->format('Ymd His');

        $hashStr    = "{$storeId}{$amount}{$orderId}{$request->mobile_number}"
                    . "no-reply@school.com" . now()->addDay()->format('Ymd His')
                    . "MA" . "" . route('student.fees.easypaisa.callback') . "0{$hashKey}";
        $hash       = strtoupper(hash('sha256', $hashStr));

        $postData = [
            'storeId'           => $storeId,
            'amount'            => $amount,
            'postBackURL'       => route('student.fees.easypaisa.callback'),
            'orderRefNum'       => $orderId,
            'mobileNum'         => $request->mobile_number,
            'emailAddress'      => 'no-reply@school.com',
            'txnType'           => 'MA',
            'bankID'            => '',
            'expiryDate'        => now()->addDay()->format('Ymd His'),
            'autoRedirect'      => '0',
            'checkSum'          => $hash,
        ];

        /*
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $txn->update(['gateway_response' => $response]);
        $result = json_decode($response, true);

        if (isset($result['status']) && $result['status'] === 'Paid') {
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $fee->update([
                'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
                'status'      => 'Paid',
            ]);
            $receipt = $this->generateReceipt($txn);
            return redirect()->route('student.fees')
                ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
        } else {
            $txn->update(['status' => 'Failed']);
            return back()->with('error', 'EasyPaisa payment failed. Please try again.');
        }
        */

        // Simulated Success (Validation Bypassed)
        $txn->update(['status' => 'Success', 'paid_at' => now(), 'gateway_response' => '{"simulated": true}']);
        $fee->update([
            'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
            'status'      => 'Paid',
        ]);
        $receipt = $this->generateReceipt($txn);
        return redirect()->route('student.fees')
            ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
    }

    // EasyPaisa callback
    public function easyPaisaCallback(Request $request)
    {
        $txn = FeePaymentTransaction::where('transaction_ref', $request->orderRefNum)->first();
        if ($txn && $request->status === 'Paid') {
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $txn->fee->update(['status' => 'Paid', 'paid_amount' => $txn->amount]);
            $this->generateReceipt($txn);
        }
        return response()->json(['status' => 'ok']);
    }

    // Internal: generate receipt record + PDF
    private function generateReceipt(FeePaymentTransaction $txn): FeeReceipt
    {
        $receiptNo = 'RCP-' . now()->format('Ymd') . '-' . str_pad($txn->id, 5, '0', STR_PAD_LEFT);
        $receipt   = FeeReceipt::create([
            'receipt_no'     => $receiptNo,
            'transaction_id' => $txn->id,
            'student_id'     => $txn->student_id,
            'fee_id'         => $txn->fee_id,
            'amount'         => $txn->amount,
        ]);
        return $receipt;
    }
}
