<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketGoSeeder extends Seeder
{
    use WithoutModelEvents;

    const NUMBER_OF_VENDORS = 1000;
    const MIN_NUMBER_OF_PRODUCTS_PER_VENDOR = 100;
    const MAX_NUMBER_OF_PRODUCTS_PER_VENDOR = 500;
    const MIN_NUMBER_OF_RATINGS_PER_PRODUCT = 1;
    const MAX_NUMBER_OF_RATINGS_PER_PRODUCT = 50;

    private $vendors = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createVendors();
        $this->createProducts();
    }

    private function createVendors() {
        $this->vendors = Vendor::factory()
            ->count(TicketGoSeeder::NUMBER_OF_VENDORS)
            ->create();
    }

    private function createProducts() {
        foreach ($this->vendors as $vendor) {
            $nProducts = rand(
                TicketGoSeeder::MIN_NUMBER_OF_PRODUCTS_PER_VENDOR,
                TicketGoSeeder::MAX_NUMBER_OF_PRODUCTS_PER_VENDOR,
            );
            $nRatigns = rand(
                TicketGoSeeder::MIN_NUMBER_OF_RATINGS_PER_PRODUCT,
                TicketGoSeeder::MAX_NUMBER_OF_RATINGS_PER_PRODUCT
            );

            Product::factory()
                ->count($nProducts)
                ->state(['vendor_id' => $vendor->id])
                ->hasRatings($nRatigns)
                ->create();
        }
    }
}
