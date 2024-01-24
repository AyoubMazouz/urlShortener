<?php

use App\Http\Controllers\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Exception are handled in the Handler.php file

// Show All URLs
Route::get("/", [Url::class, "index"]);
// Store An Url
Route::post("/", [Url::class, "store"]);
// Destroy An Url
Route::delete("/", [Url::class, "destroy"]);
// Use the Url Short Code To Redirect
Route::get("/{code}", [Url::class, "redirect"]);
