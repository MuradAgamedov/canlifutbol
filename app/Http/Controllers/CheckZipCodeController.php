<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckZipCodeController extends Controller
{
    public function check_zip(Request $request)
    {
        $zip_code = $request->zip_code;

        // Zip kodunu yoxla
        $check = ZipCode::where('zip_code', $zip_code)->first();

        // Əgər tapılıbsa, cookiyə saxla
        if ($check) {
            Cookie::queue('zip_code', $check->zip_code, 60 * 24 * 30); // 30 gün
            Cookie::queue('city', $check->city, 60 * 24 * 30);         // 30 gün
            Cookie::queue('number', $check->number, 60 * 24 * 30);     // 30 gün
        }

        return redirect()->back();
    }
}
