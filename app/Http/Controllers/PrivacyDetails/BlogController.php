<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function Blog(): View
    {
        return view('admin.privacyDetails.blog');
    }

    public function Blogs(): View
    {
        $blogs = DB::table('blog')->get();
        return view('admin.privacyDetails.blogs', compact('blogs'));
    }

    public function BlogStore(Request $request): RedirectResponse
    {
        // Handle form submission
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'blog_image' => 'required|image',
            'content' => 'required',
        ]);


        // Handle file upload for horoscope_image
        if ($request->hasFile('blog_image')) {
            $blogImage = $request->file('blog_image');
            $blogImageName = time() . '.' . $blogImage->getClientOriginalExtension();
            $blogImage->move(public_path('BlogImage'), $blogImageName);
        }

        DB::table('blog')->insert([
           'title' => $request->input('title'),
           'description' => $request->input('description'),
           'blog_image' => $blogImageName ?? null,
           'content' => $request->input('content'),
        ]);


        return redirect()->back()->with('success', 'Blog added successfully!');
    }


    public function BlogDetails($id): View
    {
        $userId = Auth::id();

        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', '=', $userId)
            ->first();

        $blog = DB::table('blog')->where('id', $id)->first();

        $featuredBlogs = DB::table('blog')->get();
        $recentBlogs = DB::table('blog')->orderBy('created_at', 'desc')->limit(5)->get();

        // Fetch previous blog
        $previousBlog = DB::table('blog')
            ->where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();

        // Fetch next blog
        $nextBlog = DB::table('blog')
            ->where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        $contactInfo = DB::table('contact_info')->first();

        return view('web.blog-details', compact('blog','userAndUserDetails', 'featuredBlogs', 'recentBlogs', 'previousBlog', 'nextBlog', 'contactInfo'));
    }

    public function AdminBlogDetails($id): View
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        return view('admin.privacyDetails.blog-details', compact('blog'));
    }

    public function AdminEditBlog($id): View
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        return view('admin.privacyDetails.edit-blog', compact('blog'));
    }

    public function AdminUpdateBlog(Request $request, $id): RedirectResponse
    {
        // Handle file upload for blog image
        if ($request->hasFile('blog_image')) {
            $blogImage = $request->file('blog_image');
            $blogImageName = time() . '.' . $blogImage->getClientOriginalExtension();
            $blogImage->move(public_path('BlogImage'), $blogImageName);

            // Delete the old image if a new one is uploaded
            $oldBlog = DB::table('blog')->where('id', $id)->first();
            if ($oldBlog && $oldBlog->blog_image) {
                $oldImagePath = public_path('BlogImage/' . $oldBlog->blog_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } else {
            $blogImageName = DB::table('blog')->where('id', $id)->value('blog_image');
        }

        // Update the blog in the database
        DB::table('blog')->where('id', $id)->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'blog_image' => $blogImageName,
        ]);

        return redirect()->route('adminEditBlog', $id)->with('success', 'Blog updated successfully!');
    }

    public function deleteBlog($id): RedirectResponse
    {
        $blog = DB::table('blog')->where('id', $id)->first();

        if ($blog) {
            if ($blog->blog_image) {
                $imagePath = public_path('BlogImage/' . $blog->blog_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            DB::table('blog')->where('id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Blog deleted successfully!');
    }



    public function blogImageStore(Request $request): JsonResponse
    {
        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('BlogImage'), $imageName);

            // Correct the URL generation
            $url = asset('BlogImage/' . $imageName);

            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded.']]);
    }
}
