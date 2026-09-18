<?php

namespace App\Http\Controllers\Testimonial;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HighlightedProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = DB::table('users')
            ->select('id','name','mobile')
            ->where('role', '!=', 'admin')
            ->get();

        $highlighted_profiles = DB::table('highlighted_profiles')
            ->join('users', 'highlighted_profiles.user_id', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select('users.*', 'user_details.*', 'highlighted_profiles.id as highlighted_id')
            ->where('users.role', '!=', 'admin')
            ->paginate(5);

        return view('admin.testimonials.highlighted-profiles', compact('users', 'highlighted_profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $insert = [
          'user_id' => $request->input('user_id')
        ];

        $highlighted_profiles = new Testimonial();
        $highlighted_profiles->storeHighlightedProfiles($insert);

        return redirect('admin/highlighted-profiles')->with('success', 'Highlighted Profile added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('highlighted_profiles')->where('id', $id)->delete();
        return redirect('admin/highlighted-profiles')->with('success', 'Highlighted profile deleted successfully');
    }

}
