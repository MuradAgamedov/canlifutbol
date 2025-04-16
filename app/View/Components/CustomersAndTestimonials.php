<?php

namespace App\View\Components;

use App\Repositories\PartnerRepository;
use App\Repositories\TestimonialRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomersAndTestimonials extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public TestimonialRepository $testimonialRepository, public PartnerRepository $partnerRepository)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $testimonials = $this->testimonialRepository->all_active('order');
        $partners = $this->partnerRepository->all_active('order');
        return view('components.customers-and-testimonials', compact('testimonials', 'partners'));
    }
}
