<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(public ServiceRepository $serviceRepository)
    {
        return view('pages.services');
    }
    public function index()
    {
        return view('pages.services');
    }

    public function service($slug)
    {
        $service = $this->serviceRepository->getBySlug($slug);
        return view('pages.service', compact('service', 'slug'));
    }
}
