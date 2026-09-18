<?php

namespace App\Http\Controllers\Testimonial;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $testimonials = DB::table('testimonials')->paginate(5);
        return view('admin.testimonials.testimonials', compact('testimonials'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|max:1024',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('TestimonialImage'), $imageName);
        }


        DB::table('testimonials')->insert([
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'image' => $imageName,
        ]);

        return redirect('admin/testimonials')->with('success', 'Testimonial added successfully');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'sometimes|max:1024',
        ]);

        // Fetch the existing happy story
        $testimonial = DB::table('testimonials')->where('id', $id)->first();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('TestimonialImage'), $imageName);

            // Delete old image if exists
            if ($testimonial && file_exists(public_path('TestimonialImage/' . $testimonial->image))) {
                unlink(public_path('TestimonialImage/' . $testimonial->image));
            }
        } else {
            // Keep the old image if a new one isn't uploaded
            $imageName = $testimonial->image;
        }

        $update = [
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'image' => $imageName,
        ];

        DB::table('testimonials')->where('id', $id)->update($update);

        return redirect('admin/testimonials')->with('success', 'Testimonial updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('testimonials')->where('id', $id)->delete();
        return redirect('admin/testimonials')->with('success', 'Testimonial deleted successfully');
    }
}
