<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Testimonios de vecinos de La Joya.
 */
final class Testimonial
{
    public static function all(): array
    {
        $path = BASE_PATH . '/app/Data/testimonials.json';
        $raw = file_get_contents($path);
        $testimonials = $raw === false ? null : json_decode($raw, true);

        return is_array($testimonials) ? $testimonials : [];
    }
}
