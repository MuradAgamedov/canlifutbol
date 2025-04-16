<?php

namespace App\View\Components;

use App\Repositories\ProjectRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WorksComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ProjectRepository $projectRepository)
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $lastProjects = $this->projectRepository->paginate_active(10,'date', 'desc');
        return view('components.works-component', compact('lastProjects'));
    }
}
