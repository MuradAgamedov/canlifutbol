<?php

namespace App\View\Components;

use App\Repositories\AboutRepository;
use App\Repositories\MenuRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientFooterComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public AboutRepository $aboutRepository)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $firstAboutText = $this->aboutRepository->getFirstText();
        return view('components.client-footer-component', compact('firstAboutText'));
    }
}
