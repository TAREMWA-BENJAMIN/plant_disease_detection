<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmingResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditTrailService;

class FarmingResourceWebController extends Controller
{
    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Clean up uploaded file if something goes wrong
     */
    private function cleanupUploadedFile($filename, $destination)
    {
        if ($filename && file_exists($destination . '/' . $filename)) {
            try {
                unlink($destination . '/' . $filename);
            } catch (\Exception $e) {
                // Log cleanup failure but don't throw error
                \Log::warning('Failed to cleanup uploaded file: ' . $e->getMessage());
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:video,pdf',
            'resource_source' => 'required|in:file,youtube',
            'file' => 'required_if:resource_source,file|file|mimes:mp4,mov,avi,wmv,flv,webm,pdf|max:102400', // 100MB max
            'youtube_link' => 'required_if:resource_source,youtube|nullable|url',
            'is_offline_available' => 'nullable|boolean',
        ], [
            'file.required_if' => 'A file is required when resource source is file.',
            'file.max' => 'File size must be less than 100MB.',
            'file.mimes' => 'File must be a valid video (mp4, mov, avi, wmv, flv, webm) or PDF format.',
            'youtube_link.required_if' => 'A YouTube link is required when resource source is YouTube link.',
        ]);

        // Ensure only one is provided
        if ($request->resource_source === 'file' && $request->filled('youtube_link')) {
            return back()->withErrors(['youtube_link' => 'Do not provide a YouTube link when uploading a file.'])->withInput();
        }
        if ($request->resource_source === 'youtube' && $request->hasFile('file')) {
            return back()->withErrors(['file' => 'Do not upload a file when providing a YouTube link.'])->withInput();
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'is_offline_available' => $request->has('is_offline_available'),
        ];

        if ($request->resource_source === 'file') {
            $file = $request->file('file');
            
            // Validate that the file is actually valid
            if (!$file->isValid()) {
                return back()->withErrors(['file' => 'Invalid file upload. Please try again.'])->withInput();
            }
            
            // Get file size BEFORE moving the file
            $fileSizeMb = round($file->getSize() / (1024 * 1024), 2);
            
            // Validate file size (optional: add a reasonable limit)
            if ($fileSizeMb > 100) { // 100MB limit
                return back()->withErrors(['file' => 'File size must be less than 100MB.'])->withInput();
            }
            
            $destination = base_path('files/images/farming_resources');
            
            // Ensure the destination directory exists
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Move the file
            try {
                $file->move($destination, $filename);
            } catch (\Exception $e) {
                return back()->withErrors(['file' => 'Failed to upload file: ' . $e->getMessage()])->withInput();
            }
            
            $data['file_path'] = $filename;
            $data['file_size_mb'] = $fileSizeMb;
            $data['youtube_link'] = null;
        } else if ($request->resource_source === 'youtube') {
            $data['youtube_link'] = $validated['youtube_link'];
            $data['file_path'] = null;
            $data['file_size_mb'] = null;
        }

        try {
            $resource = FarmingResource::create($data);

            // Log to audit trail
            $this->auditTrailService->log('create_farming_resource', Auth::user(), 'User posted a new farming resource: ' . $resource->title);

            return redirect()->route('farming-resources.index')->with('success', 'Resource uploaded successfully!');
        } catch (\Exception $e) {
            // If database creation fails, clean up the uploaded file
            if (isset($filename) && isset($destination)) {
                $this->cleanupUploadedFile($filename, $destination);
            }
            
            \Log::error('Failed to create farming resource: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $data
            ]);
            
            return back()->withErrors(['general' => 'Failed to create resource. Please try again.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $resource = \App\Models\FarmingResource::findOrFail($id);
        $title = $resource->title; // assign before delete
        $resource->delete();
        // Log to audit trail
        $this->auditTrailService->log('delete_farming_resource', Auth::user(), 'User deleted a farming resource: ' . $title);
        return redirect()->route('farming-resources.index')->with('success', 'Resource deleted successfully!');
    }
} 