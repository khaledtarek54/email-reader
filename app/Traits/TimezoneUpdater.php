<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;

trait TimezoneUpdater
{
    /**
     * Updates the date to the specified timezone.
     *
     * @param string $date The date string (in any format recognized by Carbon).
     * @param string $timezone The desired timezone (e.g., 'America/New_York').
     * @return string The updated date in the specified timezone.
     * @throws Exception
     */
    public function updateTimezone($date, $timezoneOffset)
    {
        try {
            // Parse the given date using Carbon
            $carbonDate = Carbon::parse($date);

            // Convert the timezone offset to a valid format (e.g., +02:00 or -03:00)
            $formattedOffset = sprintf('%+03d:00', (int) $timezoneOffset);  // Ensures proper formatting

            // Set the timezone using the numeric offset
            $carbonDate->setTimezone($formattedOffset);

            // Return the updated date as a string
            return $carbonDate->toDateTimeString(); // You can adjust the format if needed
        } catch (Exception $e) {
            // If an error occurs (e.g., invalid date or timezone), rethrow it
            throw new Exception("Error updating timezone: " . $e->getMessage());
        }
    }
}
