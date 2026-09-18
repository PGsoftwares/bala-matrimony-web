<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function aboutUs(): View
    {
        $aboutUs = DB::table('about_us')->get();
        return view('web.API.about-us', compact('aboutUs'));
    }

    public function privacyPolicy(): View
    {
        $privacyPolicies = DB::table('privacy_policy')->get();
        return view('web.API.privacy-policy', compact('privacyPolicies'));
    }

    public function termsAndConditions(): View
    {
        $termsAndConditions = DB::table('terms_conditions')->get();
        return view('web.API.terms-and-conditions', compact('termsAndConditions'));
    }

    public function refundPolicy(): View
    {
        $refundPolicies = DB::table('refund_policy')->get();
        return view('web.API.refund-policy', compact('refundPolicies'));
    }

    public function getBlogs(): View
    {
        $blogs = DB::table('blog')->get();
        return view('web.API.blogs', compact('blogs'));
    }

    public function BlogDetails($id): View
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        return view('web.API.blog-details', compact('blog'));
    }


}
