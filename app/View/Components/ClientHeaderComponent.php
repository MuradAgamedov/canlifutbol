<?php

namespace App\View\Components;


use App\Repositories\InfoRepository;
use App\Repositories\LangRepository;
use App\Repositories\MenuRepository;

use App\Repositories\ServiceRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientHeaderComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ServiceRepository $serviceRepository)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client-header-component');
    }
}
