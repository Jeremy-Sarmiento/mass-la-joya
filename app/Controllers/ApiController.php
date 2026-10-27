<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\ValidationException;
use App\Models\Claim;
use App\Models\JobApplication;
use App\Models\LocalOffer;
use App\Models\Order;
use App\Models\Product;
use App\Services\Auth;

/**
 * Endpoints JSON consumidos por assets/js/app.js.
 * Si el navegador no envía JavaScript, redirigen con un mensaje flash.
 */
final class ApiController extends Controller
{
    public function order(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        $data = $request->all();
        // El navegador nunca decide a qué cuenta pertenece el pedido.
        $data['user_id'] = Auth::id();

        try {
            $order = Order::place($data);
        } catch (ValidationException $exception) {
            $this->failWith($exception, (string) $request->input('_anchor', ''));
        }

        $this->respond([
            'ok' => true,
            'message' => 'Pedido ' . $order['id'] . ' registrado correctamente.',
            'order' => [
                'id' => $order['id'],
                'subtotal' => $order['subtotal'],
                'delivery' => $order['delivery'],
                'discount' => $order['discount'],
                'total' => $order['total'],
            ],
        ], 201);
    }

    public function claim(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        try {
            $claim = Claim::register($request->all());
        } catch (ValidationException $exception) {
            $this->failWith($exception, (string) $request->input('_anchor', '#libro-reclamaciones'));
        }

        $this->respond([
            'ok' => true,
            'message' => 'Reclamo registrado. Tu código es ' . $claim['code'] . '. Te responderemos en un máximo de 15 días hábiles.',
            'code' => $claim['code'],
        ], 201);
    }

    public function jobApplication(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        try {
            JobApplication::register($request->all());
        } catch (ValidationException $exception) {
            $this->failWith($exception, (string) $request->input('_anchor', '#seccion-fuerza-amarilla'));
        }

        $this->respond([
            'ok' => true,
            'message' => '¡Gracias por postular a la Fuerza Amarilla! Tu solicitud fue registrada.',
        ], 201);
    }

    public function localOffer(): never
    {
        $request = new Request();
        $this->requirePost();
        $this->requireCsrf();

        try {
            LocalOffer::register($request->all());
        } catch (ValidationException $exception) {
            $this->failWith($exception, (string) $request->input('_anchor', '#seccion-ofrece-local'));
        }

        $this->respond([
            'ok' => true,
            'message' => '¡Gracias por ofrecer tu local! Nuestro equipo de Expansión se pondrá en contacto contigo.',
        ], 201);
    }

    public function health(): never
    {
        $this->json([
            'ok' => true,
            'app' => config('app.name'),
            'php' => PHP_VERSION,
            'products' => count(Product::all()),
            'time' => date('c'),
        ]);
    }

    private function failWith(ValidationException $exception, string $anchor): never
    {
        $errors = $exception->errors();

        $this->respond([
            'ok' => false,
            'message' => (string) (reset($errors) ?: 'Revisa los datos del formulario.'),
            'errors' => $errors,
        ], 422, $anchor);
    }
}
