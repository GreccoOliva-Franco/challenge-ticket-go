<?php

namespace Tests\Pagination;

use Database\Seeders\VendorProductRatingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Tests\Helpers\endpointBuilder;

class TestConditions
{
    public const N_VENDORS = 2;

    public const N_PRODUCTS_PER_VENDOR = 2;

    public const N_RATINGS_PER_PRODUCT = 3;

    public const REST_API_HEADERS = ['Accept' => 'application/json'];

    public const SUCCESSFUL_REQUEST_PAGINATION_OUTPUT_KEYS = ['data', 'meta', 'links'];

    public const INVALID_REQUEST_PAGINATION_OUTPUT_KEYS = ['message', 'errors'];

    public const META_SUBKEYS = ['current_page', 'from', 'path', 'per_page', 'to'];

    public const LINKS_SUBKEYS = ['first', 'last', 'prev', 'next'];
}

uses(RefreshDatabase::class);
beforeEach(function () {
    (new VendorProductRatingSeeder(
        TestConditions::N_VENDORS,
        TestConditions::N_PRODUCTS_PER_VENDOR,
        TestConditions::N_RATINGS_PER_PRODUCT
    ))->run();
});

function endpoint($extend = '')
{
    return endpointBuilder('/api/products')($extend);
}

function responseOkWithoutParams()
{
    return function () {
        $response = get(endpoint(), TestConditions::REST_API_HEADERS);

        $response->assertStatus(200);
    };
}

describe('Pagination - Get list', function () {
    describe('Endpoint', function () {
        it('should exist', responseOkWithoutParams());
    });

    describe('Request', function () {
        describe('Validation', function () {
            describe('Param: page', function () {
                it('should be optional', responseOkWithoutParams());

                it('should be set to default value of 1 if undefined', function () {
                    $response = get(endpoint(), TestConditions::REST_API_HEADERS);

                    $response->assertStatus(200)
                        ->assertJsonPath('meta.current_page', 1);
                });

                it('should get a specific page when param is set', function () {
                    $page = 4;
                    $response = get(endpoint("?page=$page"), TestConditions::REST_API_HEADERS);

                    $response->assertStatus(200)
                        ->assertJsonPath('meta.current_page', $page);
                });

                it('should fail when passed invalid data', function () {
                    /**
                     * [null, ''] don't represent problems because they are processed as non-existant query parameter
                     */
                    $invalidValues = ['hola', '-24', -24];

                    foreach ($invalidValues as $page) {
                        $message = 'The page field must be at least 1.';

                        $response = get(endpoint("?page=$page"), TestConditions::REST_API_HEADERS);

                        $response->assertStatus(422);
                        expect($response->json())->toHaveKeys(['message', 'errors']);
                        // dd($response->json());
                        expect($response['message'])->toBeString()
                            ->toBe($message);
                        expect($response['errors'])->toHaveKeys(['page']);
                        expect($response['errors']['page'])->toBeArray()
                            ->toContain($message);
                    }
                });
            });

            describe('Param: per_page', function () {
                it('should be optional', responseOkWithoutParams());

                it('should be set to default value of 15 if undefined', function () {
                    $response = get(endpoint(), TestConditions::REST_API_HEADERS);

                    $response->assertStatus(200)
                        ->assertJsonPath('meta.per_page', 15);
                });

                it('should limit items per page', function () {
                    $per_page = 2; // TODO: Improve to be rand(1, $nProducts)
                    $response = get(endpoint("?per_page=$per_page"), TestConditions::REST_API_HEADERS);

                    $response->assertStatus(200)
                        ->assertJsonPath('meta.per_page', $per_page)
                        ->assertJsonPath('meta.from', 1);
                    expect($response['meta']['to'])->toBeGreaterThan(1)
                        ->toBeLessThan($per_page + 1);
                });

                it('should fail when passed invalid data', function () {
                    /**
                     * [null, ''] don't represent problems because they are processed as non-existant query parameter
                     */
                    $invalidValues = ['hola', '-24', -24];

                    foreach ($invalidValues as $per_page) {
                        $message = 'The per page field must be at least 1.';

                        $response = get(endpoint("?per_page=$per_page"), TestConditions::REST_API_HEADERS);

                        $response->assertStatus(422);
                        expect($response['message'])->toBeString()
                            ->toBe($message);
                        expect($response['errors'])->toHaveKeys(['per_page']);
                        expect($response['errors']['per_page'])->toBeArray()
                            ->toContain($message);
                    }
                });
            });
        });
    });

    describe('Response', function () {
        it('should match valid output format when successful', function () {
            $response = get(endpoint(), TestConditions::REST_API_HEADERS);

            $response->assertStatus(200);
            expect($response->json())->toHaveKeys(TestConditions::SUCCESSFUL_REQUEST_PAGINATION_OUTPUT_KEYS);
            expect($response->json()['data'])->toBeArray();
            expect($response->json()['meta'])->toHaveKeys(TestConditions::META_SUBKEYS);
            expect($response->json()['links'])->toHaveKeys(TestConditions::LINKS_SUBKEYS);
        });

        it('should match invalid output format when invalid', function () {
            $response = get(endpoint('?page=-1'), TestConditions::REST_API_HEADERS); // force invalid request

            $response->assertStatus(422);
            expect($response->json())->toHaveKeys(TestConditions::INVALID_REQUEST_PAGINATION_OUTPUT_KEYS);
            expect($response->json()['message'])->toBeString();
            expect($response->json()['errors'])->toBeArray()
                ->toHaveLength(1);
            expect($response->json()['errors']['page'])->toBeArray()
                ->toHaveLength(1);
        });
    });
});
