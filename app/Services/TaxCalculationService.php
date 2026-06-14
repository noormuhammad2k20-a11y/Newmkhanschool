<?php

namespace App\Services;

class TaxCalculationService
{
    /**
     * Calculate Pakistan FBR monthly tax for a given gross salary.
     * Slabs (Annual) for Salaried Individuals:
     * 1. 0 - 600,000: 0%
     * 2. 600,001 - 1,200,000: 5% of amount exceeding 600,000
     * 3. 1,200,001 - 2,200,000: 30,000 + 15% of amount exceeding 1,200,000
     * 4. 2,200,001 - 3,200,000: 180,000 + 25% of amount exceeding 2,200,000
     * 5. 3,200,001 - 4,100,000: 430,000 + 30% of amount exceeding 3,200,000
     * 6. 4,100,001+: 700,000 + 35% of amount exceeding 4,100,000
     */
    public function calculateMonthlyTax(float $grossSalary): float
    {
        $annualSalary = $grossSalary * 12;
        $annualTax = 0;

        if ($annualSalary <= 600000) {
            $annualTax = 0;
        } elseif ($annualSalary <= 1200000) {
            $annualTax = ($annualSalary - 600000) * 0.05;
        } elseif ($annualSalary <= 2200000) {
            $annualTax = 30000 + (($annualSalary - 1200000) * 0.15);
        } elseif ($annualSalary <= 3200000) {
            $annualTax = 180000 + (($annualSalary - 2200000) * 0.25);
        } elseif ($annualSalary <= 4100000) {
            $annualTax = 430000 + (($annualSalary - 3200000) * 0.30);
        } else {
            $annualTax = 700000 + (($annualSalary - 4100000) * 0.35);
        }

        return [
            'taxable_income' => $annualSalary / 12,
            'tax_amount' => round($annualTax / 12, 2),
            'net_income' => ($annualSalary - $annualTax) / 12
        ];
    }
}
