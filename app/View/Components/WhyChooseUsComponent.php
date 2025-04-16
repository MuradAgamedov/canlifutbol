<?php

namespace App\View\Components;

use App\Repositories\WhyUsChooseRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WhyChooseUsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public WhyUsChooseRepository $whyUsChooseRepository,)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $whyUsElements = $this->whyUsChooseRepository->all_active('order');
        return view('components.why-choose-us-component', compact('whyUsElements'));
    }
}
