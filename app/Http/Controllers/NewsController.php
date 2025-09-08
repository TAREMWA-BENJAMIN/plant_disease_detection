<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('published_date', 'desc')->paginate(12);
        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    public function apiIndex(Request $request)
    {
        $news = News::orderBy('published_date', 'desc')
            ->when($request->country, function($query, $country) {
                return $query->where('country', strtoupper($country));
            })
            ->when($request->search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate($request->per_page ?? 10);
        
        $news->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'content' => $item->content,
                'url' => $item->url,
                'image_url' => $item->image_url,
                'published_date' => $item->published_date,
                'source' => $item->source,
                'country' => $item->country,
                'language' => $item->language,
                'category' => $item->category,
            ];
        });
        return response()->json($news);
    }

    public function apiShow(News $news)
    {
        return response()->json([
            'id' => $news->id,
            'title' => $news->title,
            'description' => $news->description,
            'content' => $news->content,
            'url' => $news->url,
            'image_url' => $news->image_url,
            'published_date' => $news->published_date,
            'source' => $news->source,
            'country' => $news->country,
            'language' => $news->language,
            'category' => $news->category,
        ]);
    }

    public function fetchNews()
    {
        try {
            \Artisan::call('app:fetch-news');
            return response()->json(['success' => true, 'message' => 'News fetched successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch news: ' . $e->getMessage()], 500);
        }
    }
}
