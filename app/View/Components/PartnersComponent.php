<?php

namespace App\View\Components;

use App\Repositories\PartnerRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PartnersComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public PartnerRepository $partnerRepository)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $partners = $this->partnerRepository->all_active('order');
        return view('components.partners-component', compact('partners'));
    }
}
