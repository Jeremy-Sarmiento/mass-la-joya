<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ValidationException;
use App\Core\Validator;

/**
 * Ofertas de locales para la expansión de Mass en La Joya.
 */
final class LocalOffer extends JsonRecord
{
    protected const FILE = 'local_offers';

    public static function register(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => 'required|string|min:3|max:120',
            'phone' => 'required|phone',
            'area' => 'required|numeric|min:1|max:100000',
            'address' => 'required|string|min:5|max:200',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $data = $validator->validated();

        return self::persist([
            'created_at' => date('c'),
            'created_at_local' => self::now(),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'area' => (float) $data['area'],
            'address' => $data['address'],
            'status' => 'recibida',
            'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        ]);
    }
}
