<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function getAllHRs()
    {
        return User::where('role', 'hr')->latest()->paginate(config('app.paginate', 5));
    }

    public function createHr(array $data): User
    {

        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'hr',
                'is_active' => $data['is_active'] ,
            ]);
        });
    }

    public function updateAdmin(User $user, array $data): bool
    {

        return DB::transaction(function () use ($user, $data) {
            return $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'is_active' => $data['is_active'] ,
            ]);
        });
    }

    public function resetPassword(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            return $user->update([
                logger()->info('Reset password for user: ' . $user->email),
                'password' => Hash::make(config('auth.default_password')),
            ]);
        });
    }

    public function toggleActive(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->is_active = !$user->is_active;
            return $user->save();
        });
    }

    public function deleteAdmin(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            try {
                foreach ($user->candidates as $candidate) {

                    $candidate->skills()->detach();

                    $candidate->delete();
                }
                DB::commit();
                return $user->delete();
            } catch (Exception $e) {
                DB::rollBack();
                logger()->error('Error deleting admin: ' . $e->getMessage());
                throw $e;
            }
        });
    }
}
