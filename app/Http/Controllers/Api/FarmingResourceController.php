<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmingResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FarmingResourceController extends Controller
{
    /**
     * Get all farming resources with filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = FarmingResource::query();

        // Apply filters
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        if ($request->has('language') && $request->language) {
            $query->byLanguage($request->language);
        }

        if ($request->has('region') && $request->region) {
            $query->byRegion($request->region);
        }

        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Only show offline available resources by default
        if (!$request->has('include_all')) {
            $query->offlineAvailable();
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subcategory', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $resources = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform the data
        $resources->getCollection()->transform(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'category_name' => FarmingResource::$categories[$resource->category] ?? $resource->category,
                'subcategory' => $resource->subcategory,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'type_icon' => $resource->type_icon,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'page_count' => $resource->page_count,
                'file_size' => $resource->formatted_file_size,
                'file_url' => $resource->file_url, // <-- Added for frontend
                'youtube_link' => $resource->youtube_link,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'is_featured' => $resource->is_featured,
                'is_offline_available' => $resource->is_offline_available,
                'download_count' => $resource->download_count,
                'view_count' => $resource->view_count,
                'uploaded_by' => $resource->uploaded_by,
                'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $resource->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources->items(),
            'pagination' => [
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'total' => $resources->total(),
            ],
            'filters' => [
                'categories' => FarmingResource::$categories,
                'types' => FarmingResource::$types,
                'languages' => FarmingResource::$languages,
            ]
        ]);
    }

    /**
     * Get a specific farming resource
     */
    public function show(Request $request, $id): JsonResponse
    {
        $resource = FarmingResource::findOrFail($id);

        // Increment view count
        $resource->incrementViewCount();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'category_name' => FarmingResource::$categories[$resource->category] ?? $resource->category,
                'subcategory' => $resource->subcategory,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'type_icon' => $resource->type_icon,
                'file_url' => $resource->file_url,
                'youtube_link' => $resource->youtube_link,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'duration_seconds' => $resource->duration_seconds,
                'page_count' => $resource->page_count,
                'file_size' => $resource->formatted_file_size,
                'file_size_mb' => $resource->file_size_mb,
                'file_extension' => $resource->file_extension,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'target_regions' => $resource->target_regions,
                'is_featured' => $resource->is_featured,
                'is_offline_available' => $resource->is_offline_available,
                'download_count' => $resource->download_count,
                'view_count' => $resource->view_count,
                'uploaded_by' => $resource->uploaded_by,
                'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $resource->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Download resource for offline use
     */
    public function download(Request $request, $id): JsonResponse
    {
        $resource = FarmingResource::findOrFail($id);

        if (!$resource->is_offline_available) {
            return response()->json([
                'status' => 'error',
                'message' => 'This resource is not available for offline download'
            ], 400);
        }

        // Increment download count
        $resource->incrementDownloadCount();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'file_url' => $resource->file_url,
                'file_size' => $resource->formatted_file_size,
                'file_size_mb' => $resource->file_size_mb,
                'file_extension' => $resource->file_extension,
                'download_count' => $resource->download_count,
                'message' => 'Resource ready for download'
            ]
        ]);
    }

    /**
     * Get featured resources
     */
    public function featured(): JsonResponse
    {
        $resources = FarmingResource::featured()
            ->offlineAvailable()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $resources->transform(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'category_name' => FarmingResource::$categories[$resource->category] ?? $resource->category,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'type_icon' => $resource->type_icon,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'page_count' => $resource->page_count,
                'youtube_link' => $resource->youtube_link,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'view_count' => $resource->view_count,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources
        ]);
    }

    /**
     * Get resource categories and types
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => FarmingResource::$categories,
                'types' => FarmingResource::$types,
                'languages' => FarmingResource::$languages,
            ]
        ]);
    }

    /**
     * Get resources by category
     */
    public function byCategory(Request $request, $category): JsonResponse
    {
        $query = FarmingResource::byCategory($category)->offlineAvailable();

        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        if ($request->has('region') && $request->region) {
            $query->byRegion($request->region);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(15);

        $resources->getCollection()->transform(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'subcategory' => $resource->subcategory,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'type_icon' => $resource->type_icon,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'page_count' => $resource->page_count,
                'youtube_link' => $resource->youtube_link,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'view_count' => $resource->view_count,
                'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources->items(),
            'pagination' => [
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'total' => $resources->total(),
            ],
            'category' => $category,
            'category_name' => FarmingResource::$categories[$category] ?? $category,
        ]);
    }

    /**
     * Get resources by type (videos, PDFs, documents)
     */
    public function byType(Request $request, $type): JsonResponse
    {
        $query = FarmingResource::byType($type)->offlineAvailable();

        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        if ($request->has('region') && $request->region) {
            $query->byRegion($request->region);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(15);

        $resources->getCollection()->transform(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'category_name' => FarmingResource::$categories[$resource->category] ?? $resource->category,
                'subcategory' => $resource->subcategory,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'page_count' => $resource->page_count,
                'youtube_link' => $resource->youtube_link,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'view_count' => $resource->view_count,
                'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources->items(),
            'pagination' => [
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'total' => $resources->total(),
            ],
            'type' => $type,
            'type_name' => FarmingResource::$types[$type] ?? $type,
        ]);
    }

    /**
     * Search resources
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search query is required and must be at least 2 characters',
                'errors' => $validator->errors()
            ], 400);
        }

        $query = $request->query;
        $resources = FarmingResource::offlineAvailable()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('subcategory', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $resources->getCollection()->transform(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'category_name' => FarmingResource::$categories[$resource->category] ?? $resource->category,
                'subcategory' => $resource->subcategory,
                'type' => $resource->type,
                'type_name' => $resource->type_name,
                'type_icon' => $resource->type_icon,
                'thumbnail_url' => $resource->thumbnail_url,
                'duration' => $resource->formatted_duration,
                'page_count' => $resource->page_count,
                'youtube_link' => $resource->youtube_link,
                'language' => $resource->language,
                'language_name' => FarmingResource::$languages[$resource->language] ?? $resource->language,
                'view_count' => $resource->view_count,
                'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources->items(),
            'pagination' => [
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'total' => $resources->total(),
            ],
            'search_query' => $query,
        ]);
    }
} 