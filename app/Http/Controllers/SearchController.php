<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(public ServiceRepository $serviceRepository)
    {
        return view('pages.services');
    }
    public function search(Request $request)
    {
        $q = $request->q;
        $foundServices = $this->serviceRepository->search_paginate($q, [], 10);
        return view('pages.search_results', compact('foundServices'));
    }
}
