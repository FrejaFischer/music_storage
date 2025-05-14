<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Method for validating an ID
     * @param mixed $value, ID to validate
     * @return bool, true if valid, false if not
     */
    public static function isValidId(mixed $value): bool
    {
        // Checks if ID is integer or numeric string, with a value of minimum 1 (can't be negativ)
        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
    }
}