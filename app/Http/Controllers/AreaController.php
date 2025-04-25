<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::where("area_id","!=", 0)->get();
        return view("pages.areas", compact('areas'));
    }
}
