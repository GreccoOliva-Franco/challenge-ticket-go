<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketGoSeeder extends Seeder
{
    use WithoutModelEvents;

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
            ->count(1000)
            ->create();
    }

    private function createProducts() {
        foreach ($this->vendors as $vendor) {
            // $nProducts = rand(100, 500);
            // $nRatigns = rand(1, 50);
            $nProducts = 10;
            $nRatigns = 3;

            Product::factory()
                ->count($nProducts)
                ->state(['vendor_id' => $vendor->id])
                ->hasRatings($nRatigns)
                ->create();
        }
    }
}
