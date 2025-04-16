<?php

namespace App\Http\Controllers;

use App\Repositories\TipsAndTricksRepository;
use Illuminate\Http\Request;

class TipsAndTricksController extends Controller
{
    public function __construct(public TipsAndTricksRepository $tricksRepository)
    {

    }
    public function index()
    {
        $tips = $this->tricksRepository->paginate_active(4, 'date', 'desc');
        $allTipsCount = $this->tricksRepository->count();

        return view('pages.tripsAndTricks', compact('tips', 'allTipsCount'));
    }
    public function tipAndTrick($slug)
    {
        $tip = $this->tricksRepository->getBySlug($slug);
        return view('pages.tip', compact('tip'));
    }
}
