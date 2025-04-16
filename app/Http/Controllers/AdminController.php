<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use App\Services\AdminService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
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
            $users = $this->adminService->getAllHRs($request->all());
            return view('admin.index', [
                'users' => $users,
                'defaultPassword' => config('auth.default_password')
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching accounts: ' . $e->getMessage());
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
        } catch (Exception $e) {
            Log::error('Error creating hr: ' . $e->getMessage());
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
        } catch (Exception $e) {
            Log::error('Error updating admin: ' . $e->getMessage());
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

            return redirect()->back()->with('success', 'Đã reset mật khẩu thành công.');
        } catch (Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return redirect()->back()->with([
                'errors' => $e->getMessage()
            ]);
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
            log::info('Toggled active status : ' . $isActive);
        } catch (Exception $e) {
            logger()->error('Error toggling active status: ' . $e->getMessage());
            return back()->with('errors', 'Không thể cập nhật trạng thái.');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->authorize('manage', User::class);
            $this->adminService->deleteAdmin($user);
            return redirect()->route('admin.index')
                ->with('success', 'Xóa  tài khoản hr thành công!');
        } catch (Exception $e) {
            Log::error('Error deleting admin: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Không thể xóa admin.');
        }
    }
}
