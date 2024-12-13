<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Database\Seeder;

class VendorProductRatingSeeder extends Seeder {
    private readonly int $nVendors;
    private readonly int $nProductsPerVendor;
    private readonly int $nRatingsPerProduct;

    function __construct(int $nVendors, int $nProductsPerVendor, int $nRatingsPerProduct)
    {
        $this->nVendors = $nVendors;
        $this->nProductsPerVendor = $nProductsPerVendor;
        $this->nRatingsPerProduct = $nRatingsPerProduct;
    }

    public function run(): void {
        $seeder = $this;

        Vendor::factory()
        ->count($seeder->nVendors)
        ->create()
        ->each(function ($vendor) use ($seeder) {
            Product::factory()
                ->count($seeder->nProductsPerVendor)
                ->state(['vendor_id' => $vendor->id])
                ->hasRatings($seeder->nRatingsPerProduct)
                ->create();
        });
    }
}