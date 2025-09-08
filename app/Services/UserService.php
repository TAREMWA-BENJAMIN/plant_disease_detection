<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Get all users with pagination.
     *
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return User::with(['district'])
            ->select(
                'id',
                'first_name',
                'last_name',
                'phone_number',
                'email',
                'user_type',
                'district_id',
                'is_verified as status',
                'photo',
                'created_at'
            )
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get a user by ID with relationships.
     *
     * @param int $id The user ID
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return User::with(['district'])
            ->find($id);
    }

    /**
     * Create a new user with the given data.
     *
     * @param array $data The user data to create
     * @return User The created user
     */
    public function create(array $data): User
    {
        if (isset($data['photo'])) {
            if (str_starts_with($data['photo'], 'data:image')) {
                $data['photo'] = $this->processBase64Image($data['photo']);
            }
            // else: keep the file path as is
        }

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    /**
     * Update an existing user with the given data.
     *
     * @param User $user The user to update
     * @param array $data The data to update
     * @return User The updated user
     */
    public function update(User $user, array $data): User
    {
        if (isset($data['photo'])) {
            if (str_starts_with($data['photo'], 'data:image')) {
                $data['photo'] = $this->processBase64Image($data['photo']);
            }
            // else: keep the file path as is
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user->load(['district']);
    }

    /**
     * Delete a user.
     *
     * @param User $user The user to delete
     * @return bool
     */
    public function delete(User $user): bool
    {
        DB::table('sessions')->where('user_id', $user->id)->delete();
        return $user->delete();
    }

    /**
     * Process a base64 image string.
     *
     * @param string|null $base64Image The base64 image string
     * @return string|null The processed image string or null
     */
    protected function processBase64Image(?string $base64Image): ?string
    {
        if ($base64Image && str_starts_with($base64Image, 'data:image')) {
            return $base64Image; // save as-is in DB
        }
        return null;
    }
}
