<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use App\Exceptions\CantDeleteAdminUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::paginate(10);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        $attributes['password'] = Hash::make($attributes['password']);

        User::create($attributes);

        return redirect()
            ->route('admin.users.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.user.store')
            ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->validated();

        $user->update($attributes);

        return redirect()
            ->route('admin.users.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.user.update')
            ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $response = redirect()->route('admin.users.index');

        try {
            if ($user->id === 1) {
                throw new CantDeleteAdminUser();
            }

            $user->deleteOrFail();
        } catch (CantDeleteAdminUser) {
            return $response->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.user.adminUser')
            ]);
        } catch (QueryException) {
            return $response->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.user.cannotDeleteUser')
            ]);
        }

        return $response->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.user.delete')
        ]);
    }

    public function massDestroy(Request $request): RedirectResponse
    {
        $ids = $request->get('ids') ?? [];
        $response = redirect()->route('admin.users.index');

        if (empty($ids)) {
            return $response->with([
                'alertType' => AlertType::INFO,
                'alertMessage' => __('messages.user.massDeleteFail')
            ]);
        }

        try {
            foreach ($ids as $id) {
                if ($id === 1) {
                    throw new CantDeleteAdminUser();
                }

                $user = User::findOrFail($id);
                $user->delete();
            }
        } catch (QueryException) {
            return $response->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.user.cannotDeleteUser')
            ]);
        } catch (CantDeleteAdminUser) {
            return $response->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.user.adminUser')
            ]);
        }

        return $response->with([
            'alertType' => AlertType::DANGER,
            'alertMessage' => __('messages.user.massDeleted')
        ]);
    }
}
