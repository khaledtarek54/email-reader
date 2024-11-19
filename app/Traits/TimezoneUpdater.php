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
            // Normalize the input timezone offset (e.g., +2 becomes +02:00, -3 becomes -03:00)
            $formattedOffset = sprintf('%+03d:00', (int) $timezoneOffset);

            // Parse the input date using Carbon, accounting for various possible formats
            $carbonDate = Carbon::parse($date);

            // Convert the date to the provided timezone offset
            $carbonDate->setTimezone($formattedOffset);

            // Return the updated date in the desired format (ISO 8601 or custom format)
            return $carbonDate->format('Y-m-d H:i:s'); // Adjust the format as needed
        } catch (Exception $e) {
            // Handle invalid date or timezone offset errors gracefully
            throw new Exception("Error updating timezone: " . $e->getMessage());
        }
    }
}
