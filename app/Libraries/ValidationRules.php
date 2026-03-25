<?php

namespace App\Libraries;

use CodeIgniter\Validation\Rules as BaseRules;

/**
 * Custom validation rules for the application
 */
class ValidationRules extends BaseRules
{
    /**
     * Custom validation: End date must be >= start date
     *
     * @param string|null $end_date   The end date value
     * @param string      $start_date The start date (passed as parameter)
     * @return bool
     */
    public function check_end_date($end_date, string $start_date): bool
    {
        if (empty($start_date) || empty($end_date)) {
            return true; // Let required rule handle empty values
        }

        try {
            $start = new \DateTime($start_date);
            $end = new \DateTime($end_date);

            return $end >= $start;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Custom validation: Deadline must be before both start and end dates
     *
     * @param string|null $deadline   The deadline value
     * @param string      $params     Comma-separated start_date,end_date
     * @return bool
     */
    public function check_deadline($deadline, string $params): bool
    {
        if (empty($deadline)) {
            return true; // permit_empty handles this
        }

        $dates = explode(',', $params);
        if (count($dates) !== 2) {
            return false;
        }

        $start_date = trim($dates[0]);
        $end_date = trim($dates[1]);

        // If start or end date is empty, skip this validation
        if (empty($start_date) || empty($end_date)) {
            return true;
        }

        try {
            $deadline_dt = new \DateTime($deadline);
            $start_dt = new \DateTime($start_date);
            $end_dt = new \DateTime($end_date);

            // Deadline must be before start date AND before end date
            return ($deadline_dt < $start_dt) && ($deadline_dt < $end_dt);
        } catch (\Exception $e) {
            return false;
        }
    }
}
