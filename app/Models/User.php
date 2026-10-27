<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ValidationException;
use App\Core\Validator;

/**
 * Cuentas de clientes de la tienda.
 *
 * Las contraseñas se guardan con password_hash() y nunca se devuelven
 * al navegador. Los registros viven en storage/users.php.
 */
final class User extends JsonRecord
{
    protected const FILE = 'users';

    /** Hash de relleno para igualar el tiempo de respuesta cuando el correo no existe. */
    private const DUMMY_HASH = '$2y$12$a5lsG.jj6XILunFxmNe.euT9iO.uh6Gs8FsD0Os29FdXAwm0C7576';

    /** @param array<string, mixed> $input */
    public static function register(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => 'required|string|min:2|max:80',
            'email' => 'required|email|max:120',
            'phone' => 'string|max:20|phone',
            'password' => 'required|string|min:8|max:72',
        ]);

        $errors = $validator->errors();

        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $phone = trim((string) ($input['phone'] ?? ''));
        $password = trim((string) ($input['password'] ?? ''));
        $confirmation = (string) ($input['password_confirmation'] ?? '');

        if ($password !== '' && strlen($password) > 72) {
            $errors['password'] = 'La contraseña no debe superar los 72 caracteres.';
        }

        if ($password !== '' && $confirmation === '') {
            $errors['password_confirmation'] = 'Confirma tu contraseña.';
        } elseif ($password !== $confirmation) {
            $errors['password_confirmation'] = 'Las contraseñas no coinciden.';
        }

        if (empty($input['terms'])) {
            $errors['terms'] = 'Debes aceptar la política de privacidad para crear tu cuenta.';
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false && self::findByEmail($email) !== null) {
            $errors['email'] = 'Ya existe una cuenta con este correo. Inicia sesión.';
        }

        if ($errors !== []) {
            throw new ValidationException($errors);
        }

        $record = [
            'id' => self::generateId(),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('c'),
            'created_at_local' => self::now(),
        ];

        self::persist($record);

        return self::safe($record);
    }

    /** @return array<string, mixed>|null */
    public static function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));
        if ($email === '') {
            return null;
        }

        foreach (self::all() as $user) {
            if (is_array($user) && strtolower((string) ($user['email'] ?? '')) === $email) {
                return $user;
            }
        }

        return null;
    }

    /** @return array<string, mixed>|null */
    public static function find(string $id): ?array
    {
        if ($id === '') {
            return null;
        }

        foreach (self::all() as $user) {
            if (is_array($user) && hash_equals((string) ($user['id'] ?? ''), $id)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Comprueba correo y contraseña. Devuelve el usuario sin el hash.
     *
     * @return array<string, mixed>|null
     */
    public static function attempt(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);

        if ($user === null || empty($user['password_hash'])) {
            // Verificación ficticia para no filtrar por tiempo si el correo existe.
            password_verify($password, self::DUMMY_HASH);

            return null;
        }

        if (!password_verify($password, (string) $user['password_hash'])) {
            return null;
        }

        return self::safe($user);
    }

    /**
     * Quita datos sensibles antes de enviarlos a la vista o a la API.
     *
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    public static function safe(array $user): array
    {
        unset($user['password_hash']);

        $name = (string) ($user['name'] ?? '');
        $user['initial'] = $name !== '' ? mb_strtoupper(mb_substr($name, 0, 1)) : 'M';
        $user['first_name'] = $name !== '' ? explode(' ', $name)[0] : 'Cliente';

        return $user;
    }

    private static function generateId(): string
    {
        do {
            $id = 'USR-' . strtoupper(bin2hex(random_bytes(4)));
        } while (self::find($id) !== null);

        return $id;
    }
}
