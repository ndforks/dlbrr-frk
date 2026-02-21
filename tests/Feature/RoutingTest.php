<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_home_redirects_to_htdocs(): void
    {
        $response = $this->get('/');
        
        $response->assertStatus(302);
        $response->assertRedirect('/htdocs/index.php');
    }

    public function test_user_routes_exist(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertTrue($routes->contains('user'));
        $this->assertTrue($routes->contains('user/card.php'));
        $this->assertTrue($routes->contains('user/list.php'));
    }

    public function test_product_routes_exist(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertTrue($routes->contains('product'));
        $this->assertTrue($routes->contains('product/card.php'));
        $this->assertTrue($routes->contains('product/list.php'));
    }

    public function test_societe_routes_exist(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertTrue($routes->contains('societe'));
        $this->assertTrue($routes->contains('societe/card.php'));
        $this->assertTrue($routes->contains('societe/list.php'));
    }

    public function test_api_routes_exist(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertTrue($routes->contains('api/{path?}'));
    }

    public function test_admin_routes_exist(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertTrue($routes->contains('admin'));
        $this->assertTrue($routes->contains('admin/system/'));
        $this->assertTrue($routes->contains('admin/modules.php'));
    }

    public function test_fallback_route_is_registered(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('fallback'));
    }
}
