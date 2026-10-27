<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ValidationException;
use App\Core\Validator;

/**
 * Postulaciones para la Fuerza Amarilla de Mass La Joya.
 */
final class JobApplication extends JsonRecord
{
    protected const FILE = 'job_applications';

    public const POSITIONS = [
        'Atención al Cliente y Caja',
        'Reponedor / Almacén',
        'Líder de Tienda / Supervisor',
        'Personal de Limpieza y Seguridad',
    ];

    public static function register(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => 'required|string|min:3|max:120',
            'dni' => 'required|string|min:8|max:12',
            'age' => 'required|integer|min:18|max:70',
            'phone' => 'required|phone',
            'zone' => 'required|string|min:3|max:120',
            'position' => 'required|in:' . implode(',', self::POSITIONS),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $data = $validator->validated();

        return self::persist([
            'created_at' => date('c'),
            'created_at_local' => self::now(),
            'name' => $data['name'],
            'dni' => $data['dni'],
            'age' => (int) $data['age'],
            'phone' => $data['phone'],
            'zone' => $data['zone'],
            'position' => $data['position'],
            'status' => 'recibida',
            'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        ]);
    }
}
