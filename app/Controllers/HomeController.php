<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\JobApplication;
use App\Models\Product;
use App\Models\Testimonial;

/**
 * Página principal de la tienda.
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        $request = new Request();

        $category = $request->query('categoria', 'todos');
        if (!Category::exists($category)) {
            $category = 'todos';
        }

        $search = mb_substr($request->query('q', ''), 0, 80);
        $sort = $request->query('orden', 'default');
        if (!in_array($sort, ['default', 'price-asc', 'price-desc', 'discount', 'name'], true)) {
            $sort = 'default';
        }

        $zoneId = $request->query('zona', DeliveryZone::DEFAULT);
        if (DeliveryZone::find($zoneId) === null) {
            $zoneId = DeliveryZone::DEFAULT;
        }

        $categories = Category::withCounts();
        $products = Product::filter($category, $search, $sort);
        $featured = Product::featured(8);
        $deliveryZones = DeliveryZone::all();
        $testimonials = Testimonial::all();

        $this->view('pages/home', [
            'title' => 'Mass de La Joya | ¡Lleva Mass pagando Menos! - Arequipa',
            'description' => 'Tienda Mass de La Joya, Arequipa. Encuentra los mejores precios del barrio en abarrotes, lácteos, bebidas, limpieza y más. ¡Haz tus pedidos por WhatsApp con delivery a todo La Joya!',
            'canonical' => absolute_url(),
            'pixelId' => (string) config('meta_pixel_id', ''),
            'store' => (array) config('store'),
            'whatsapp' => (string) config('whatsapp'),
            'paymentMethods' => (array) config('store.payment_methods', []),
            'categories' => $categories,
            'products' => $products,
            'featured' => $featured,
            'testimonials' => $testimonials,
            'deliveryZones' => $deliveryZones,
            'positions' => JobApplication::POSITIONS,
            'filters' => [
                'category' => $category,
                'search' => $search,
                'sort' => $sort,
                'zone' => $zoneId,
            ],
            'productsCount' => count($products),
            'totalProducts' => count(Product::all()),
            'csrfToken' => csrf_token(),
            'flashes' => flashes(),
            'jsData' => [
                'products' => Product::all(),
                'categories' => $categories,
                'deliveryZones' => $deliveryZones,
                'coupons' => Coupon::all(),
                'api' => [
                    'orders' => url('api/orders'),
                    'claims' => url('api/claims'),
                    'jobs' => url('api/job-applications'),
                    'localOffers' => url('api/local-offers'),
                ],
                'csrf' => csrf_token(),
                'store' => [
                    'whatsapp' => (string) config('whatsapp'),
                    'currency' => 'PEN',
                    'paymentMethods' => (array) config('store.payment_methods', []),
                ],
                'initialState' => [
                    'category' => $category,
                    'search' => $search,
                    'sortBy' => $sort,
                    'selectedZone' => $zoneId,
                ],
            ],
        ]);
    }
}
