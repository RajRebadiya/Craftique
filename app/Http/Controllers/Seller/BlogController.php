<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('created_at', 'desc');

        if ($request->search != null) {
            $blogs = $blogs->where('title', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $blogs = $blogs->paginate(15);

        return view('seller.blog_system.blog.index', compact('blogs', 'sort_search'));
    }

    public function create()
    {
        $blog_categories = BlogCategory::all();
        $products = \DB::table('products')
            ->select('id', 'name')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('seller.blog_system.blog.create', compact('blog_categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|max:255',
        ]);

        $blog = new Blog;

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->banner = $request->banner;
        $blog->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        $blog->product_ids = $this->normalizeProductIds($request->input('product_ids', []));
        $blog->hashtags = $this->normalizeHashtags($request->input('hashtags'));

        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;

        $blog->save();

        flash(translate('Blog post has been created successfully'))->success();
        return redirect()->route('seller.blog.index');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $blog_categories = BlogCategory::all();
        $products = \DB::table('products')
            ->select('id', 'name')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('seller.blog_system.blog.edit', compact('blog', 'blog_categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|max:255',
        ]);

        $blog = Blog::findOrFail($id);

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->banner = $request->banner;
        $blog->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        $blog->product_ids = $this->normalizeProductIds($request->input('product_ids', []));
        $blog->hashtags = $this->normalizeHashtags($request->input('hashtags'));

        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;

        $blog->save();

        flash(translate('Blog post has been updated successfully'))->success();
        return redirect()->route('seller.blog.index');
    }

    public function destroy($id)
    {
        Blog::findOrFail($id)->delete();
        return back();
    }

    private function normalizeProductIds($value): array
    {
        $ids = is_array($value) ? $value : [];
        return collect($ids)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function normalizeHashtags($value): ?string
    {
        $tags = collect(explode(',', (string) $value))
            ->map(fn ($tag) => trim(ltrim($tag, '#')))
            ->filter()
            ->unique()
            ->values();

        return $tags->isEmpty() ? null : $tags->implode(', ');
    }
}
