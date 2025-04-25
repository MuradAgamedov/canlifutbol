<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index($slug)
    {
        $area = Area::with('countries')->where('slug', $slug)->firstOrFail();

        $countries = $area->countries()
            ->whereNotIn('country_id', [2, 56, 77, 106, 108])->get();

        return view('pages.countries', compact('area', 'countries'));
    }
}
