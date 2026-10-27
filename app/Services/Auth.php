<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
 * Sesión de clientes.
 *
 * Guarda únicamente el ID del usuario en la sesión, renueva el ID de
 * sesión al iniciar/cerrar sesión y aplica un límite de intentos fallidos.
 */
final class Auth
{
    private const SESSION_KEY = '_auth_user_id';
    private const ATTEMPTS_KEY = '_auth_login_attempts';
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_SECONDS = 300;

    public static function check(): bool
    {
        return self::id() !== null;
    }

    public static function id(): ?string
    {
        $id = $_SESSION[self::SESSION_KEY] ?? null;

        return is_string($id) && $id !== '' ? $id : null;
    }

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        $id = self::id();
        if ($id === null) {
            return null;
        }

        $user = User::find($id);
        if ($user === null) {
            // La cuenta ya no existe: se limpia la sesión.
            self::logout();

            return null;
        }

        return User::safe($user);
    }

    /**
     * Intenta iniciar sesión. Registra el fallo para el límite de intentos.
     *
     * @return array<string, mixed>|null
     */
    public static function attempt(string $email, string $password): ?array
    {
        $user = User::attempt($email, $password);

        if ($user === null) {
            self::recordFailure();

            return null;
        }

        self::clearAttempts();
        self::login($user);

        return $user;
    }

    /** @param array<string, mixed> $user */
    public static function login(array $user): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION[self::SESSION_KEY] = (string) ($user['id'] ?? '');
        $_SESSION['_auth_time'] = time();
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY], $_SESSION['_auth_time']);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /** Segundos que faltan para poder volver a intentar (0 = libre). */
    public static function throttleSeconds(): int
    {
        $attempts = self::recentAttempts();

        if (count($attempts) < self::MAX_ATTEMPTS) {
            return 0;
        }

        $last = (int) end($attempts);
        $remaining = self::WINDOW_SECONDS - (time() - $last);

        return max(0, $remaining);
    }

    public static function recordFailure(): void
    {
        $attempts = self::recentAttempts();
        $attempts[] = time();

        $_SESSION[self::ATTEMPTS_KEY] = array_slice($attempts, -self::MAX_ATTEMPTS);
    }

    public static function clearAttempts(): void
    {
        unset($_SESSION[self::ATTEMPTS_KEY]);
    }

    /** @return array<int, int> */
    private static function recentAttempts(): array
    {
        $raw = $_SESSION[self::ATTEMPTS_KEY] ?? [];
        if (!is_array($raw)) {
            return [];
        }

        $limit = time() - self::WINDOW_SECONDS;
        $attempts = [];

        foreach ($raw as $timestamp) {
            $timestamp = (int) $timestamp;
            if ($timestamp > $limit) {
                $attempts[] = $timestamp;
            }
        }

        return $attempts;
    }
}
