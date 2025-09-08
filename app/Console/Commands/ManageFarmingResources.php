<?php

namespace App\Console\Commands;

use App\Models\FarmingResource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManageFarmingResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farming-resources:manage 
                            {action : Action to perform (list, add, update, delete, featured)}
                            {--id= : Resource ID for update/delete operations}
                            {--title= : Resource title}
                            {--description= : Resource description}
                            {--category= : Resource category}
                            {--subcategory= : Resource subcategory}
                            {--type= : Resource type (video, pdf, document)}
                            {--file-path= : Path to file}
                            {--thumbnail-path= : Path to thumbnail image}
                            {--language=en : Resource language (en, sw, lg, ak, am, rw)}
                            {--regions= : Target regions (comma-separated)}
                            {--featured : Mark as featured}
                            {--offline=true : Available for offline download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage farming resources (videos and PDFs) for the training center';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listResources();
                break;
            case 'add':
                $this->addResource();
                break;
            case 'update':
                $this->updateResource();
                break;
            case 'delete':
                $this->deleteResource();
                break;
            case 'featured':
                $this->toggleFeatured();
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->info('Available actions: list, add, update, delete, featured');
                return 1;
        }

        return 0;
    }

    /**
     * List all resources
     */
    private function listResources()
    {
        $resources = FarmingResource::orderBy('created_at', 'desc')->get();

        if ($resources->isEmpty()) {
            $this->info('No farming resources found.');
            return;
        }

        $this->info('Farming Resources:');
        $this->info(str_repeat('-', 80));

        foreach ($resources as $resource) {
            $this->info("ID: {$resource->id}");
            $this->info("Title: {$resource->title}");
            $this->info("Type: {$resource->type_name} ({$resource->type_icon})");
            $this->info("Category: {$resource->category} ({$resource->subcategory})");
            $this->info("Language: {$resource->language}");
            
            if ($resource->isVideo()) {
                $this->info("Duration: {$resource->formatted_duration}");
            } elseif ($resource->isPdf()) {
                $this->info("Pages: {$resource->page_count}");
            }
            
            $this->info("File Size: {$resource->formatted_file_size}");
            $this->info("Views: {$resource->view_count} | Downloads: {$resource->download_count}");
            $this->info("Featured: " . ($resource->is_featured ? 'Yes' : 'No'));
            $this->info("Offline: " . ($resource->is_offline_available ? 'Yes' : 'No'));
            $this->info("Created: {$resource->created_at->format('Y-m-d H:i:s')}");
            $this->info(str_repeat('-', 80));
        }
    }

    /**
     * Add a new resource
     */
    private function addResource()
    {
        $this->info('Adding new farming resource...');

        $data = $this->getResourceData();
        
        if (!$data) {
            return;
        }

        // Validate data
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(FarmingResource::$categories)),
            'subcategory' => 'nullable|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(FarmingResource::$types)),
            'file_path' => 'required|string',
            'thumbnail_path' => 'nullable|string',
            'language' => 'required|string|in:' . implode(',', array_keys(FarmingResource::$languages)),
            'target_regions' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_offline_available' => 'boolean',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("- {$error}");
            }
            return;
        }

        // Check if file exists
        if (!Storage::exists($data['file_path'])) {
            $this->error("File not found: {$data['file_path']}");
            return;
        }

        // Get file size
        $fileSize = Storage::size($data['file_path']);
        $data['file_size_mb'] = round($fileSize / (1024 * 1024), 2);

        // Create resource record
        $resource = FarmingResource::create($data);

        $this->info("Resource added successfully!");
        $this->info("ID: {$resource->id}");
        $this->info("Title: {$resource->title}");
        $this->info("Type: {$resource->type_name}");
        $this->info("File Size: {$resource->formatted_file_size}");
    }

    /**
     * Update an existing resource
     */
    private function updateResource()
    {
        $id = $this->option('id');
        
        if (!$id) {
            $this->error('Resource ID is required for update operation.');
            $this->info('Use --id option to specify the resource ID.');
            return;
        }

        $resource = FarmingResource::find($id);
        
        if (!$resource) {
            $this->error("Resource with ID {$id} not found.");
            return;
        }

        $this->info("Updating resource: {$resource->title}");

        $data = $this->getResourceData($resource);
        
        if (!$data) {
            return;
        }

        // Remove empty values
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        if (empty($data)) {
            $this->info('No changes to update.');
            return;
        }

        $resource->update($data);

        $this->info("Resource updated successfully!");
        $this->info("Title: {$resource->title}");
    }

    /**
     * Delete a resource
     */
    private function deleteResource()
    {
        $id = $this->option('id');
        
        if (!$id) {
            $this->error('Resource ID is required for delete operation.');
            $this->info('Use --id option to specify the resource ID.');
            return;
        }

        $resource = FarmingResource::find($id);
        
        if (!$resource) {
            $this->error("Resource with ID {$id} not found.");
            return;
        }

        if ($this->confirm("Are you sure you want to delete '{$resource->title}'?")) {
            // Delete file
            if (Storage::exists($resource->file_path)) {
                Storage::delete($resource->file_path);
            }

            // Delete thumbnail
            if ($resource->thumbnail_path && Storage::exists($resource->thumbnail_path)) {
                Storage::delete($resource->thumbnail_path);
            }

            $resource->delete();

            $this->info("Resource deleted successfully!");
        }
    }

    /**
     * Toggle featured status
     */
    private function toggleFeatured()
    {
        $id = $this->option('id');
        
        if (!$id) {
            $this->error('Resource ID is required for featured operation.');
            $this->info('Use --id option to specify the resource ID.');
            return;
        }

        $resource = FarmingResource::find($id);
        
        if (!$resource) {
            $this->error("Resource with ID {$id} not found.");
            return;
        }

        $resource->is_featured = !$resource->is_featured;
        $resource->save();

        $status = $resource->is_featured ? 'featured' : 'unfeatured';
        $this->info("Resource '{$resource->title}' has been {$status}.");
    }

    /**
     * Get resource data from command options or prompt user
     */
    private function getResourceData($existingResource = null)
    {
        $data = [];

        // Title
        $data['title'] = $this->option('title') ?: $this->ask('Resource title', $existingResource?->title);
        if (!$data['title']) {
            $this->error('Title is required.');
            return null;
        }

        // Description
        $data['description'] = $this->option('description') ?: $this->ask('Resource description (optional)', $existingResource?->description);

        // Category
        $categories = FarmingResource::$categories;
        $categoryChoice = $this->option('category') ?: $this->choice(
            'Select category',
            $categories,
            $existingResource?->category
        );
        $data['category'] = array_search($categoryChoice, $categories);

        // Subcategory
        $data['subcategory'] = $this->option('subcategory') ?: $this->ask('Subcategory (optional)', $existingResource?->subcategory);

        // Type
        $types = FarmingResource::$types;
        $typeChoice = $this->option('type') ?: $this->choice(
            'Select resource type',
            $types,
            $existingResource?->type ?? 'video'
        );
        $data['type'] = array_search($typeChoice, $types);

        // File path
        $data['file_path'] = $this->option('file-path') ?: $this->ask('File path', $existingResource?->file_path);
        if (!$data['file_path'] && !$existingResource) {
            $this->error('File path is required.');
            return null;
        }

        // Thumbnail path
        $data['thumbnail_path'] = $this->option('thumbnail-path') ?: $this->ask('Thumbnail image path (optional)', $existingResource?->thumbnail_path);

        // Language
        $languages = FarmingResource::$languages;
        $languageChoice = $this->option('language') ?: $this->choice(
            'Select language',
            $languages,
            $existingResource?->language ?? 'en'
        );
        $data['language'] = array_search($languageChoice, $languages);

        // Target regions
        $regions = $this->option('regions') ?: $this->ask('Target regions (comma-separated, optional)', $existingResource?->target_regions ? implode(',', $existingResource->target_regions) : '');
        if ($regions) {
            $data['target_regions'] = array_map('trim', explode(',', $regions));
        }

        // Featured status
        $data['is_featured'] = $this->option('featured') ?: $this->confirm('Mark as featured?', $existingResource?->is_featured ?? false);

        // Offline availability
        $data['is_offline_available'] = $this->option('offline') ?: $this->confirm('Available for offline download?', $existingResource?->is_offline_available ?? true);

        // Uploaded by
        $data['uploaded_by'] = $existingResource?->uploaded_by ?: 'Admin';

        return $data;
    }
} 