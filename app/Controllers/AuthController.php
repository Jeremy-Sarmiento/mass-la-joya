<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\ValidationException;
use App\Core\Validator;
use App\Core\View;
use App\Models\Order;
use App\Models\User;
use App\Services\Auth;

/**
 * Inicio de sesión, registro y panel de cliente.
 */
final class AuthController extends Controller
{
    public function login(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:120',
            'password' => 'required|string|max:72',
        ]);

        if ($validator->fails()) {
            $this->failAuth($validator->errors());
        }

        $wait = Auth::throttleSeconds();
        if ($wait > 0) {
            $minutes = max(1, (int) ceil($wait / 60));
            $this->respond([
                'ok' => false,
                'message' => 'Demasiados intentos fallidos. Espera unos ' . $minutes . ' minuto(s) e inténtalo de nuevo.',
                'retry_after' => $wait,
            ], 429);
        }

        $user = Auth::attempt($request->string('email'), trim((string) $request->input('password', '')));

        if ($user === null) {
            $this->respond([
                'ok' => false,
                'message' => 'Correo o contraseña incorrectos. Verifica tus datos e inténtalo otra vez.',
            ], 401);
        }

        $this->respond($this->payload(
            '¡Hola de nuevo, ' . (string) $user['first_name'] . '! Tu sesión está activa.',
            $user
        ));
    }

    public function register(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        try {
            $user = User::register($request->all());
        } catch (ValidationException $exception) {
            $this->failAuth($exception->errors());
        }

        Auth::login($user);

        $this->respond($this->payload(
            '¡Bienvenido a Mass de La Joya, ' . (string) $user['first_name'] . '! Tu cuenta fue creada.',
            $user
        ), 201);
    }

    public function logout(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        Auth::logout();

        $this->respond([
            'ok' => true,
            'message' => 'Tu sesión se cerró correctamente. ¡Vuelve pronto!',
            'user' => null,
            'panel' => $this->guestPanel(),
        ]);
    }

    /**
     * Devuelve el panel de cuenta actualizado (usado tras iniciar sesión).
     */
    public function account(): never
    {
        $request = new Request();
        $user = Auth::user();

        if ($user === null) {
            if ($request->wantsJson()) {
                $this->json(['ok' => false, 'message' => 'Inicia sesión para ver tu cuenta.'], 401);
            }

            $this->redirect('');
        }

        $this->json([
            'ok' => true,
            'user' => $user,
            'panel' => $this->userPanel($user),
        ]);
    }

    /** @param array<string, mixed> $user */
    private function payload(string $message, array $user): array
    {
        return [
            'ok' => true,
            'message' => $message,
            'user' => $user,
            'panel' => $this->userPanel($user),
        ];
    }

    /** @param array<string, mixed> $user */
    private function userPanel(array $user): string
    {
        return View::renderToString('partials/account-panel', [
            'user' => $user,
            'orders' => Order::forUser((string) $user['id']),
            'csrfToken' => csrf_token(),
            'whatsapp' => (string) config('whatsapp'),
        ]);
    }

    private function guestPanel(): string
    {
        return View::renderToString('partials/account-panel', [
            'user' => null,
            'orders' => [],
            'csrfToken' => csrf_token(),
            'whatsapp' => (string) config('whatsapp'),
        ]);
    }

    /** @param array<string, string> $errors */
    private function failAuth(array $errors): never
    {
        $this->respond([
            'ok' => false,
            'message' => (string) (reset($errors) ?: 'Revisa los datos del formulario.'),
            'errors' => $errors,
        ], 422);
    }
}
