<?php

namespace App\Services;

use App\Models\PgtAiResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class PgtAiService
{
    /**
     * Get all results for a user
     */
    public function getUserResults(User $user, bool $paginate = false, int $perPage = 10): Collection|LengthAwarePaginator
    {
        $query = PgtAiResult::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get all shared results
     */
    public function getSharedResults(bool $paginate = false, int $perPage = 10): Collection|LengthAwarePaginator
    {
        $query = PgtAiResult::where('shared', true)
            ->with('user')
            ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get all results for all users
     */
    public function getAllResults(bool $paginate = false, int $perPage = 10): Collection|LengthAwarePaginator
    {
        $query = PgtAiResult::with('user')
            ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Create a new result
     */
    public function createResult(array $data): PgtAiResult
    {
        // Only save if plant_image is present and not empty
        if (empty($data['plant_image'])) {
            throw new \Exception('Plant image is required.');
        }
        // Handle base64 image upload
        if (isset($data['plant_image']) && str_starts_with($data['plant_image'], 'data:image')) {
            $base64Image = $data['plant_image'];
            @list($type, $fileData) = explode(';base64,', $base64Image);
            $extension = explode('/', $type)[1];
            $imageName = 'plant_' . time() . '.' . $extension;
            
            // Store in files/images/plant_images directory
            $destination = base_path('files/images/plant_images');
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            file_put_contents($destination . '/' . $imageName, base64_decode($fileData));
            $data['plant_image'] = $imageName; // Store just the filename
        }

        // Ensure user_id is set for the result
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        return PgtAiResult::create($data);
    }

    /**
     * Get a specific result
     */
    public function getResult(int $id): ?PgtAiResult
    {
        return PgtAiResult::find($id);
    }

    /**
     * Update a result
     */
    public function updateResult(PgtAiResult $result, array $data): bool
    {
        return $result->update($data);
    }

    /**
     * Delete a result
     */
    public function deleteResult(PgtAiResult $result): bool
    {
        return $result->delete();
    }
} 