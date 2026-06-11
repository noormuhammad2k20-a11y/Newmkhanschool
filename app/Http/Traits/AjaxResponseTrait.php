<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait AjaxResponseTrait
{
    /**
     * Return a success response — JSON for AJAX, redirect for traditional requests.
     *
     * @param  Request     $request
     * @param  string      $message   Flash / toast message
     * @param  mixed       $data      Optional payload for JSON responses
     * @param  string|null $redirect  Optional redirect URL (ignored for AJAX)
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function ajaxSuccess(Request $request, string $message, $data = null, ?string $redirect = null)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $payload = [
                'status'  => 'success',
                'message' => $message,
            ];
            if ($data !== null) {
                $payload['data'] = $data;
            }
            if ($redirect !== null) {
                $payload['redirect'] = $redirect;
            }
            return response()->json($payload);
        }

        $target = $redirect ? redirect($redirect) : redirect()->back();
        return $target->with('success', $message);
    }

    /**
     * Return an error response — JSON for AJAX, redirect for traditional requests.
     *
     * @param  Request  $request
     * @param  string   $message   Error message
     * @param  int      $status    HTTP status code for JSON response
     * @param  mixed    $errors    Optional validation errors
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function ajaxError(Request $request, string $message, int $status = 422, $errors = null)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $payload = [
                'status'  => 'error',
                'message' => $message,
            ];
            if ($errors !== null) {
                $payload['errors'] = $errors;
            }
            return response()->json($payload, $status);
        }

        return redirect()->back()->with('error', $message)->withInput();
    }
}
