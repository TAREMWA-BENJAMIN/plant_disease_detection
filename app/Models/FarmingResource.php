<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FarmingResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'subcategory',
        'type',
        'file_path',
        'youtube_link',
        'thumbnail_path',
        'duration_seconds',
        'page_count',
        'file_size_mb',
        'language',
        'target_regions',
        'is_featured',
        'is_offline_available',
        'download_count',
        'view_count',
        'uploaded_by'
    ];

    protected $casts = [
        'target_regions' => 'array',
        'is_featured' => 'boolean',
        'is_offline_available' => 'boolean',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'duration_seconds' => 'integer',
        'page_count' => 'integer',
    ];

    // Resource types
    public static $types = [
        'video' => 'Video',
        'pdf' => 'PDF Document',
        'document' => 'Document'
    ];

    // Categories available for farming resources
    public static $categories = [
        'crop_management' => 'Crop Management',
        'pest_control' => 'Pest Control',
        'soil_health' => 'Soil Health',
        'irrigation' => 'Irrigation',
        'harvesting' => 'Harvesting',
        'post_harvest' => 'Post-Harvest',
        'organic_farming' => 'Organic Farming',
        'climate_smart' => 'Climate Smart Agriculture',
        'livestock' => 'Livestock Management',
        'equipment' => 'Farm Equipment',
        'marketing' => 'Marketing & Sales',
        'financial' => 'Financial Management',
        'business_planning' => 'Business Planning',
        'record_keeping' => 'Record Keeping'
    ];

    // Languages supported
    public static $languages = [
        'en' => 'English',
        'sw' => 'Swahili',
        'lg' => 'Luganda',
        'ak' => 'Akan',
        'am' => 'Amharic',
        'rw' => 'Kinyarwanda'
    ];

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }
        
        return url('files/images/farming_resources/' . $this->file_path);
    }


    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return null;
    }

    /**
     * Get formatted duration (for videos)
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds || $this->type !== 'video') {
            return null;
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size_mb) {
            return 'Unknown';
        }

        $size = (float) $this->file_size_mb;
        if ($size >= 1024) {
            return round($size / 1024, 2) . ' GB';
        }

        return round($size, 2) . ' MB';
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    /**
     * Check if resource is a video
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Check if resource is a PDF
     */
    public function isPdf()
    {
        return $this->type === 'pdf';
    }

    /**
     * Check if resource is a document
     */
    public function isDocument()
    {
        return $this->type === 'document';
    }

    /**
     * Get display type name
     */
    public function getTypeNameAttribute()
    {
        return self::$types[$this->type] ?? $this->type;
    }

    /**
     * Scope for offline available resources
     */
    public function scopeOfflineAvailable($query)
    {
        return $query->where('is_offline_available', true);
    }

    /**
     * Scope for featured resources
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for resources by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for resources by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for resources by language
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope for resources by region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->whereJsonContains('target_regions', $region)
                    ->orWhereNull('target_regions');
    }

    /**
     * Scope for videos only
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope for PDFs only
     */
    public function scopePdfs($query)
    {
        return $query->where('type', 'pdf');
    }

    /**
     * Scope for documents only
     */
    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Check if resource is relevant for a specific region
     */
    public function isRelevantForRegion($region)
    {
        if (empty($this->target_regions)) {
            return true; // Available for all regions if no specific regions set
        }

        return in_array($region, $this->target_regions);
    }

    /**
     * Get appropriate icon for resource type
     */
    public function getTypeIconAttribute()
    {
        switch ($this->type) {
            case 'video':
                return '🎥';
            case 'pdf':
                return '📄';
            case 'document':
                return '📋';
            default:
                return '📁';
        }
    }
} 