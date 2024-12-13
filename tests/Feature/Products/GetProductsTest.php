<?php

namespace Tests\Products;

use App\Models\Product;
use App\Models\Vendor;
use Tests\Seeders\VendorProductRatingSeeder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Tests\Helpers\endpointBuilder;

class TestConditions {
    public const N_VENDORS = 2;
    public const N_PRODUCTS_PER_VENDOR = 2;
    public const N_RATINGS_PER_PRODUCT = 3;
    public const REST_API_HEADERS = ['accept' => 'application/json'];
}

uses(RefreshDatabase::class);
beforeEach(function () {
    (new VendorProductRatingSeeder(
        TestConditions::N_VENDORS,
        TestConditions::N_PRODUCTS_PER_VENDOR,
        TestConditions::N_RATINGS_PER_PRODUCT
    ))->run();
});

function endpoint($extend = '') { return endpointBuilder('/api/products')($extend); }

function responseOkWithoutParams() {
    return function () {
        $response = get(endpoint());

        $response->assertStatus(200);
    };
}

describe('Products - Get products', function () {
    describe('Endpoint', function () {
        it('should exist', responseOkWithoutParams());
    });

    describe('Request', function () {
        describe('Validation', function () {
            describe('Param: vendor_id', function () {
                it('should be optional', responseOkWithoutParams());

                it('should filter by vendor ID', function () {
                    $vendor = Vendor::find(1);
                    $response = get(endpoint("?vendor_id={$vendor->id}"), TestConditions::REST_API_HEADERS);

                    $response->assertStatus(200);
                    expect($response['data'])->toBeArray();

                    foreach ($response['data'] as $product) {
                        expect($product['vendor'])->toBe($vendor['name']);
                    }
                });
            });

            describe('Param: name', function () {
                it('should be optional', responseOkWithoutParams());

                it('should filter by text', function () {
                    $product = Product::find(1);
                    $name = explode(' ', $product->name)[0];

                    $response = get(endpoint("?name=$name"), TestConditions::REST_API_HEADERS);
                    
                    $response->assertStatus(200);
                    foreach ($response['data'] as $jsonProduct) {
                        expect(strtolower($jsonProduct['name']))->toContain(strtolower($name));
                    }
                });
            });
        });
    });

    describe('Response', function () {
        it('should match output format', function () {
            $response = get(endpoint(), TestConditions::REST_API_HEADERS);

            $response->assertStatus(200)
                ->assertJsonIsObject();
            expect($response['data'])->toBeArray()
                ->toHaveLength(TestConditions::N_VENDORS * TestConditions::N_PRODUCTS_PER_VENDOR);
        });
    });
});