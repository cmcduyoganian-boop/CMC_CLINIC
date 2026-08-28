<?php

namespace App\Utilities;

class PasswordGenerator
{
    /**
     * Generate a secure random password
     * Requirements:
     * - Length: 6-8 characters
     * - Must contain uppercase letter
     * - Must contain lowercase letter
     * - Must contain number
     * - Must contain special character
     */
    public static function generate(): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '@#$%^&!';

        // Length between 6-8
        $length = rand(6, 8);

        // Ensure we have at least one of each required type
        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];

        // Fill remaining characters randomly from all types
        $allChars = $uppercase . $lowercase . $numbers . $special;
        while (strlen($password) < $length) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        $password = str_shuffle($password);

        // Validate
        if (self::validate($password)) {
            return $password;
        }

        // Recursive call if validation fails (very rare)
        return self::generate();
    }

    /**
     * Validate password meets all requirements
     */
    public static function validate(string $password): bool
    {
        $length = strlen($password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[@#$%^&!]/', $password);

        return $length >= 6 && $length <= 8 && $hasUpper && $hasLower && $hasNumber && $hasSpecial;
    }
}