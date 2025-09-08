<?php

namespace Database\Seeders;

use App\Models\FarmingResource;
use Illuminate\Database\Seeder;

class FarmingResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            // VIDEOS
            [
                'title' => 'Organic Pest Control Methods for Small-Scale Farmers',
                'description' => 'Learn effective organic pest control techniques that are safe for your crops and environment. This video covers natural pest repellents, companion planting, and biological control methods.',
                'category' => 'pest_control',
                'subcategory' => 'Organic Methods',
                'type' => 'video',
                'file_path' => 'videos/pest-control-organic.mp4',
                'thumbnail_path' => 'thumbnails/pest-control-organic.jpg',
                'duration_seconds' => 1800, // 30 minutes
                'file_size_mb' => 45.2,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Agricultural Expert'
            ],
            [
                'title' => 'Soil Health Management for Better Crop Yields',
                'description' => 'Discover how to improve your soil health through proper testing, organic matter addition, and sustainable farming practices. Essential knowledge for every farmer.',
                'category' => 'soil_health',
                'subcategory' => 'Soil Testing',
                'type' => 'video',
                'file_path' => 'videos/soil-health-management.mp4',
                'thumbnail_path' => 'thumbnails/soil-health.jpg',
                'duration_seconds' => 2400, // 40 minutes
                'file_size_mb' => 62.8,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania', 'Ethiopia'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Soil Expert'
            ],
            [
                'title' => 'Efficient Irrigation Techniques for Dry Seasons',
                'description' => 'Master water conservation and efficient irrigation methods to ensure your crops thrive even during dry periods. Learn drip irrigation and water management.',
                'category' => 'irrigation',
                'subcategory' => 'Drip Irrigation',
                'type' => 'video',
                'file_path' => 'videos/efficient-irrigation.mp4',
                'thumbnail_path' => 'thumbnails/irrigation.jpg',
                'duration_seconds' => 2100, // 35 minutes
                'file_size_mb' => 55.3,
                'language' => 'sw',
                'target_regions' => ['Kenya', 'Tanzania'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Water Management Expert'
            ],
            [
                'title' => 'Maize Farming Best Practices',
                'description' => 'Complete guide to successful maize farming including land preparation, planting, maintenance, and harvesting techniques.',
                'category' => 'crop_management',
                'subcategory' => 'Maize',
                'type' => 'video',
                'file_path' => 'videos/maize-farming.mp4',
                'thumbnail_path' => 'thumbnails/maize.jpg',
                'duration_seconds' => 2700, // 45 minutes
                'file_size_mb' => 71.2,
                'language' => 'lg',
                'target_regions' => ['Uganda'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Crop Specialist'
            ],
            [
                'title' => 'Coffee Farming and Processing',
                'description' => 'Learn the complete process of coffee farming from seedling to cup. Includes harvesting, processing, and quality control methods.',
                'category' => 'crop_management',
                'subcategory' => 'Coffee',
                'type' => 'video',
                'file_path' => 'videos/coffee-farming.mp4',
                'thumbnail_path' => 'thumbnails/coffee.jpg',
                'duration_seconds' => 3600, // 60 minutes
                'file_size_mb' => 89.5,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Ethiopia'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Coffee Expert'
            ],

            // PDF DOCUMENTS
            [
                'title' => 'Complete Guide to Organic Farming',
                'description' => 'Comprehensive manual covering all aspects of organic farming including soil preparation, crop rotation, natural pest control, and certification requirements.',
                'category' => 'organic_farming',
                'subcategory' => 'Guide',
                'type' => 'pdf',
                'file_path' => 'pdfs/organic-farming-guide.pdf',
                'thumbnail_path' => 'thumbnails/organic-guide.jpg',
                'page_count' => 45,
                'file_size_mb' => 8.2,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania', 'Ethiopia', 'Rwanda'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Organic Farming Institute'
            ],
            [
                'title' => 'Farm Business Planning Template',
                'description' => 'Step-by-step template to help farmers create a comprehensive business plan including financial projections, market analysis, and risk assessment.',
                'category' => 'business_planning',
                'subcategory' => 'Template',
                'type' => 'pdf',
                'file_path' => 'pdfs/farm-business-plan-template.pdf',
                'thumbnail_path' => 'thumbnails/business-plan.jpg',
                'page_count' => 32,
                'file_size_mb' => 5.8,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Business Development Expert'
            ],
            [
                'title' => 'Pest Identification Manual',
                'description' => 'Detailed guide to identify common crop pests in East Africa with photos, damage symptoms, and control methods.',
                'category' => 'pest_control',
                'subcategory' => 'Identification',
                'type' => 'pdf',
                'file_path' => 'pdfs/pest-identification-manual.pdf',
                'thumbnail_path' => 'thumbnails/pest-manual.jpg',
                'page_count' => 68,
                'file_size_mb' => 12.4,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania', 'Ethiopia'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Entomologist'
            ],
            [
                'title' => 'Soil Testing Methods',
                'description' => 'Practical guide to soil testing using simple methods that farmers can perform at home with basic equipment.',
                'category' => 'soil_health',
                'subcategory' => 'Testing',
                'type' => 'pdf',
                'file_path' => 'pdfs/soil-testing-methods.pdf',
                'thumbnail_path' => 'thumbnails/soil-testing.jpg',
                'page_count' => 28,
                'file_size_mb' => 4.6,
                'language' => 'sw',
                'target_regions' => ['Kenya', 'Tanzania'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Soil Scientist'
            ],
            [
                'title' => 'Record Keeping for Small Farmers',
                'description' => 'Simple record keeping system designed for small-scale farmers to track income, expenses, and farm activities.',
                'category' => 'record_keeping',
                'subcategory' => 'System',
                'type' => 'pdf',
                'file_path' => 'pdfs/record-keeping-guide.pdf',
                'thumbnail_path' => 'thumbnails/record-keeping.jpg',
                'page_count' => 24,
                'file_size_mb' => 3.9,
                'language' => 'lg',
                'target_regions' => ['Uganda'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Agricultural Extension Officer'
            ],
            [
                'title' => 'Climate Smart Agriculture Handbook',
                'description' => 'Handbook on adapting farming practices to climate change including drought-resistant crops and water conservation techniques.',
                'category' => 'climate_smart',
                'subcategory' => 'Handbook',
                'type' => 'pdf',
                'file_path' => 'pdfs/climate-smart-agriculture.pdf',
                'thumbnail_path' => 'thumbnails/climate-handbook.jpg',
                'page_count' => 52,
                'file_size_mb' => 9.7,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania', 'Ethiopia', 'Rwanda'],
                'is_featured' => true,
                'is_offline_available' => true,
                'uploaded_by' => 'Climate Expert'
            ],
            [
                'title' => 'Post-Harvest Handling Guide',
                'description' => 'Essential techniques for proper post-harvest handling to prevent losses and maintain crop quality during storage and transportation.',
                'category' => 'post_harvest',
                'subcategory' => 'Handling',
                'type' => 'pdf',
                'file_path' => 'pdfs/post-harvest-handling.pdf',
                'thumbnail_path' => 'thumbnails/post-harvest.jpg',
                'page_count' => 36,
                'file_size_mb' => 6.8,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania', 'Rwanda'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Post-Harvest Specialist'
            ],
            [
                'title' => 'Financial Management for Farmers',
                'description' => 'Basic financial management skills for farmers including budgeting, saving, and accessing credit.',
                'category' => 'financial',
                'subcategory' => 'Management',
                'type' => 'pdf',
                'file_path' => 'pdfs/financial-management.pdf',
                'thumbnail_path' => 'thumbnails/financial.jpg',
                'page_count' => 40,
                'file_size_mb' => 7.3,
                'language' => 'en',
                'target_regions' => ['Uganda', 'Kenya', 'Tanzania'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Financial Advisor'
            ],
            [
                'title' => 'Marketing Strategies for Farm Products',
                'description' => 'Effective marketing strategies to help farmers sell their products at better prices and reach more customers.',
                'category' => 'marketing',
                'subcategory' => 'Strategies',
                'type' => 'pdf',
                'file_path' => 'pdfs/marketing-strategies.pdf',
                'thumbnail_path' => 'thumbnails/marketing.jpg',
                'page_count' => 30,
                'file_size_mb' => 5.5,
                'language' => 'sw',
                'target_regions' => ['Kenya', 'Tanzania'],
                'is_featured' => false,
                'is_offline_available' => true,
                'uploaded_by' => 'Marketing Expert'
            ]
        ];

        foreach ($resources as $resourceData) {
            FarmingResource::create($resourceData);
        }

        $this->command->info('Farming resources (videos and PDFs) seeded successfully!');
    }
} 