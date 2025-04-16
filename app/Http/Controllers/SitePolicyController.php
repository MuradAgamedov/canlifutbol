<?php

namespace App\Http\Controllers;

use App\Repositories\PolicyRepository;
use Illuminate\Http\Request;

class SitePolicyController extends Controller
{
    protected $policy;

    public function __construct(public PolicyRepository $policyRepository)
    {
        // Retrieve the policy in the constructor and share it with all views
        $this->policy = $this->policyRepository->get();
    }

    public function index()
    {
        return view('pages.site_policy', ['policy' => $this->policy]);
    }

    public function privacyPolicy()
    {
        return view('pages.privacyPolicy', ['policy' => $this->policy]);
    }

    public function termsAndConditions()
    {
        return view('pages.termsAndConditions', ['policy' => $this->policy]);
    }

    public function refundPolicy()
    {
        return view('pages.refundPolicy', ['policy' => $this->policy]);
    }
}
