<?php

namespace App\Http\Controllers;

use App\Repositories\TeamRepository;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __construct(public TeamRepository $teamRepository)
    {

    }
    public function index()
    {
        $team = $this->teamRepository->all_active('order');
        return view('pages.about', compact('team'));
    }
}
