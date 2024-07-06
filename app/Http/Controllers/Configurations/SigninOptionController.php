<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Configurations\SigninOption;
use Illuminate\Http\Request;

class SigninOptionController extends Controller
{
    public function index()
    {
        return SigninOption::all();
    }
}
