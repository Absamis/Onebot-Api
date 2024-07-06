<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Models\Configurations\AccountOption;
use App\Models\Configurations\Role;
use App\Models\Configurations\SigninOption;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{
    //
    public function getSigninOptions()
    {
        $data = SigninOption::all();
        return ApiResponse::success("Record fetched", $data);
    }

    public function getAccountOptions()
    {
        $data = AccountOption::all();
        return ApiResponse::success("Record fetched", $data);
    }
    public function getRoles()
    {
        $data = Role::all();
        return ApiResponse::success("Record fetched", $data);
    }
}
