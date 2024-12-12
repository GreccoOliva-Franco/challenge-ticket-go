<?php

use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () { require __DIR__.'/api/products.php'; });
