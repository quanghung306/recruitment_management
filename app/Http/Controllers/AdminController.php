<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request): View|RedirectResponse
    {
        try {
            $this->authorize('manage', User::class);
            $users = $this->adminService->getAllHRs();
            $admins = $this->adminService->getAllAdmins();

            return view('admin.index', [
                'admins' => $admins,
                'users' => $users,
                'defaultPassword' => config('auth.default_password')
            ]);
        } catch (\Throwable $th) {
            Log::error('Error fetching accounts: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Không thể tải danh sách tài khoản.');
        }
    }

    public function create(): View
    {
        $this->authorize('manage', User::class);
        return view('admin.create');
    }

    public function store(AdminRequest $request): RedirectResponse
    {
        try {
            $this->authorize('manage', User::class);
            $this->adminService->createHr($request->validated());

            return redirect()->route('admin.index')
                ->with('success', 'Tạo hr thành công!');
        } catch (\Throwable $th) {
            Log::error('Error creating hr: ' . $th->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể tạo tài khoản hr.');
        }
    }

    public function update(AdminUpdateRequest $request, User $user): RedirectResponse
    {
        try {
            $this->authorize('manage', User::class);
            $this->adminService->updateAdmin($user, $request->validated());
            return redirect()->route('admin.index')
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            Log::error('Error updating admin: ' . $th->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật thông tin admin.');
        }
    }

    public function resetPassword(User $user): RedirectResponse
    {
        try {
            $this->authorize('manage', $user);
            $this->adminService->resetPassword($user);

            return back()->with('success', 'Đã reset mật khẩu thành công.');
        } catch (\Throwable $th) {
            Log::error('Error resetting password: ' . $th->getMessage());
            return back()->with('error', 'Không thể reset mật khẩu.');
        }
    }

    public function toggleActive(User $user): RedirectResponse
    {
        try {
            $this->authorize('toggleActive', $user);
            $isActive = $this->adminService->toggleActive($user);

            return back()->with('success',
                $isActive
                    ? 'Tài khoản đã được kích hoạt'
                    : 'Tài khoản đã được vô hiệu hóa');
        } catch (\Throwable $th) {
            Log::error('Error toggling active status: ' . $th->getMessage());
            return back()->with('error', 'Không thể cập nhật trạng thái.');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->authorize('manage', User::class);
            $this->adminService->deleteAdmin($user);

            return redirect()->route('admin.index')
                ->with('success', 'Xóa admin thành công!');
        } catch (\Throwable $th) {
            Log::error('Error deleting admin: ' . $th->getMessage());
            return redirect()->back()
                ->with('error', 'Không thể xóa admin.');
        }
    }
}
