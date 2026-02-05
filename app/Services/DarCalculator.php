<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Dar;
use App\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DarCalculator
{
    /**
     * Calculate missing DAR dates for an employee
     * 
     * @param Employee $employee
     * @return array Array of missing dates (Carbon objects)
     */
    public function getMissingDarDates(Employee $employee): array
    {
        if (!$employee->join_date) {
            return [];
        }

        $joinDate = Carbon::parse($employee->join_date);
        $today = Carbon::today();
        
        // If join date is in future, no pending DARs
        if ($joinDate->isFuture()) {
            return [];
        }
        
        // Start checking from join date or reasonable limit (e.g., start of year or max 3 months ago)
        // For now, let's respect join date but maybe cap it to not go back too far if join date is very old?
        // Requirement says "from join_date". Let's assume safe range.
        
        $period = CarbonPeriod::create($joinDate, $today);
        
        // Get all holidays in this period involving DB query
        $holidays = Holiday::whereBetween('date', [$joinDate->format('Y-m-d'), $today->format('Y-m-d')])
                           ->pluck('date')
                           ->toArray();

        // Get all existing DARs
        $existingDars = Dar::where('employee_id', $employee->id)
                           ->whereBetween('dar_date', [$joinDate->format('Y-m-d'), $today->format('Y-m-d')])
                           ->pluck('dar_date')
                           ->map(function($date) {
                               return Carbon::parse($date)->format('Y-m-d');
                           })
                           ->toArray();

        // Get all approved leaves (full day)
        // Assuming Leave model has start_date and end_date
        $leaves = Leave::where('employee_id', $employee->id)
                       ->where('status', 'approved')
                       ->where(function($q) use ($joinDate, $today) {
                           $q->whereBetween('start_date', [$joinDate->format('Y-m-d'), $today->format('Y-m-d')])
                             ->orWhereBetween('end_date', [$joinDate->format('Y-m-d'), $today->format('Y-m-d')]);
                       })
                       ->get();

        $leaveDates = [];
        foreach ($leaves as $leave) {
            $leavePeriod = CarbonPeriod::create($leave->start_date, $leave->end_date);
            foreach ($leavePeriod as $date) {
                $leaveDates[] = $date->format('Y-m-d');
            }
        }

        $missingDates = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            // Exclude Weekends (Saturday, Sunday)
            if ($date->isWeekend()) {
                continue;
            }

            // Exclude Holidays
            if (in_array($dateString, $holidays)) {
                continue;
            }

            // Exclude Existing DARs
            if (in_array($dateString, $existingDars)) {
                continue;
            }

            // Exclude Approved Leaves
            if (in_array($dateString, $leaveDates)) {
                continue;
            }

            $missingDates[] = $dateString;
        }

        return $missingDates;
    }
}
