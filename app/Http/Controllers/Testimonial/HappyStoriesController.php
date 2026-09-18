<?php

namespace App\Http\Controllers\Testimonial;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HappyStoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $happy_stories = DB::table('happy_stories')->paginate(5);
        return view('admin.testimonials.happy-stories', compact('happy_stories'));
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
        $validated = $request->validate([
            'groom' => 'required|string|max:255',
            'bride' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('HappyStory'), $imageName);
        }

        $insert = [
            'groom' => $request->input('groom'),
            'bride' => $request->input('bride'),
            'content' => $request->input('content'),
            'image' => $imageName,
        ];

        $happy_story = new Testimonial();
        $happy_story->storeHappyStories($insert);
        return redirect('admin/happy-stories')->with('success', 'Happy story added successfully');
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
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'groom' => 'required|string|max:255',
            'bride' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        // Fetch the existing happy story
        $happyStory = DB::table('happy_stories')->where('id', $id)->first();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('HappyStory'), $imageName);

            // Delete old image if exists
            if ($happyStory && file_exists(public_path('HappyStory/' . $happyStory->image))) {
                unlink(public_path('HappyStory/' . $happyStory->image));
            }
        } else {
            // Keep the old image if a new one isn't uploaded
            $imageName = $happyStory->image;
        }

        $update = [
            'groom' => $request->input('groom'),
            'bride' => $request->input('bride'),
            'content' => $request->input('content'),
            'image' => $imageName,
        ];

        DB::table('happy_stories')->where('id', $id)->update($update);

        return redirect('admin/happy-stories')->with('success', 'Happy story updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('happy_stories')->where('id', $id)->delete();
        return redirect('admin/happy-stories')->with('success', 'Happy story deleted successfully');
    }
}
