<?php

namespace App\Http\Controllers;

use App\Repositories\AboutRepository;
use App\Repositories\CustomerRewsRepository;
use App\Repositories\FAQRepository;
use App\Repositories\HomePageSliderRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        public HomePageSliderRepository $homePageSliderRepository,
        public CustomerRewsRepository $customerRewsRepository,
        public FAQRepository $FAQRepository
    )
    {

    }
    public function index()
    {
        $slides = $this->homePageSliderRepository->all_active('order');
        $customerRews = $this->customerRewsRepository->all_active('order');
        $faq = $this->FAQRepository->all_active('order');
        return view('pages.home', compact('slides',  'customerRews', 'faq'));
    }
}
