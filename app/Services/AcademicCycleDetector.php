<?php

namespace App\Services;

use App\Models\AcademicCycleRule;
use Carbon\Carbon;

class AcademicCycleDetector
{
    /**
     * Determine if the current date falls within the promotion window for a school.
     */
    public function isPromotionWindowActive(?int $schoolId = null): bool
    {
        $schoolId = $schoolId ?? auth()->user()->school_id ?? null;
        if (!$schoolId) return false;

        $rule = AcademicCycleRule::where('school_id', $schoolId)->where('is_active', true)->first();
        if (!$rule || !$rule->promotion_window_start_month || !$rule->promotion_window_end_month) {
            return false; // Default to false if not configured
        }

        $currentMonth = Carbon::now()->month;
        
        $start = $rule->promotion_window_start_month;
        $end = $rule->promotion_window_end_month;

        if ($start <= $end) {
            return $currentMonth >= $start && $currentMonth <= $end;
        } else {
            // Window wraps around the year (e.g. Nov to Feb)
            return $currentMonth >= $start || $currentMonth <= $end;
        }
    }
    
    /**
     * Get the active cycle rule for a school
     */
    public function getActiveRule(?int $schoolId = null): ?AcademicCycleRule
    {
        $schoolId = $schoolId ?? auth()->user()->school_id ?? null;
        return AcademicCycleRule::where('school_id', $schoolId)->where('is_active', true)->first();
    }
}
