<?php

namespace App\Http\Controllers;

// 1. Import the trait
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // 2. Use the trait
    use AuthorizesRequests;
}