<?php

if (!function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function str_slug($title, $separator = '-')
    {
        // Convert to lowercase
        $slug = strtolower($title);
        
        // Replace special characters
        $slug = preg_replace('/[áàäâã]/u', 'a', $slug);
        $slug = preg_replace('/[éèëê]/u', 'e', $slug);
        $slug = preg_replace('/[íìïî]/u', 'i', $slug);
        $slug = preg_replace('/[óòöôõ]/u', 'o', $slug);
        $slug = preg_replace('/[úùüû]/u', 'u', $slug);
        $slug = preg_replace('/[ñ]/u', 'n', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        
        // Remove special characters and replace with separator
        $slug = preg_replace('/[^a-z0-9\-]/', $separator, $slug);
        
        // Remove multiple separators
        $slug = preg_replace('/[' . preg_quote($separator) . ']+/', $separator, $slug);
        
        // Trim separators from beginning and end
        $slug = trim($slug, $separator);
        
        return $slug;
    }
}

if (!function_exists('validate_email')) {
    /**
     * Validate email address format
     *
     * @param  string  $email
     * @return bool
     */
    function validate_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('format_phone')) {
    /**
     * Format phone number for display
     *
     * @param  string  $phone
     * @return string
     */
    function format_phone($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Format based on length
        if (strlen($phone) == 10) {
            return '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
        } elseif (strlen($phone) == 11) {
            return substr($phone, 0, 1) . ' (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7);
        }
        
        return $phone;
    }
}

if (!function_exists('generate_school_code')) {
    /**
     * Generate a unique school code
     *
     * @param  string  $countryCode
     * @param  int     $sequence
     * @return string
     */
    function generate_school_code($countryCode = 'DO', $sequence = null)
    {
        if ($sequence) {
            return strtoupper($countryCode) . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        }
        
        return strtoupper($countryCode) . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('distance_between_coordinates')) {
    /**
     * Calculate distance between two coordinates in kilometers
     *
     * @param  float  $lat1
     * @param  float  $lon1
     * @param  float  $lat2
     * @param  float  $lon2
     * @return float
     */
    function distance_between_coordinates($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}

if (!function_exists('format_coordinates')) {
    /**
     * Format coordinates for display
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  int    $precision
     * @return string
     */
    function format_coordinates($latitude, $longitude, $precision = 6)
    {
        return number_format($latitude, $precision) . ', ' . number_format($longitude, $precision);
    }
} 