<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ValidationException;
use App\Core\Validator;

/**
 * Hoja de reclamación virtual (Ley N.° 29571).
 */
final class Claim extends JsonRecord
{
    protected const FILE = 'claims';

    public static function register(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => 'required|string|min:3|max:120',
            'dni' => 'required|string|min:8|max:12',
            'phone' => 'required|phone',
            'email' => 'required|email|max:120',
            'address' => 'required|string|min:5|max:200',
            'type' => 'required|in:queja,reclamo',
            'detail' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $data = $validator->validated();
        $code = 'REC-LJ-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::persist([
            'code' => $code,
            'created_at' => date('c'),
            'created_at_local' => self::now(),
            'name' => $data['name'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'type' => $data['type'],
            'detail' => $data['detail'],
            'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
        ]);
    }
}
