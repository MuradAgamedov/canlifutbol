<?php

namespace App\View\Components;

use App\Repositories\StatisticsRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatisticsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public StatisticsRepository $statisticsRepository)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $items = $this->statisticsRepository->all_active('order');

        return view('components.statistics-component', compact('items'));
    }
}
