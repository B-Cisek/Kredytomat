<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
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
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Użytkownik poprawnie dodany!'
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
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Użytkownik zaktualizowany.'
            ]);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            if ($user->id !== 1) {
                $user->deleteOrFail();
            }
        } catch (\Exception) {
            return redirect()
                ->route('admin.users.index')
                ->with([
                    'alert_type' => AlertType::WARNING,
                    'alert_message' => 'Nie można usunąć użytkownika który ma zapsiane symulacje.'
                ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Użytkownik usunięty.'
            ]);
    }

    public function massDestroy(Request $request): RedirectResponse
    {
        $ids = $request->get('ids') ?? [];

        if (empty($ids)) {
            return redirect()
                ->route('admin.users.index')
                ->with([
                    'alert_type' => AlertType::WARNING,
                    'alert_message' => 'Nie zaznaczono użytkowników do usunięcia!'
                ]);
        }

        try {
            User::destroy($ids);
        } catch (\Exception) {
            return redirect()
                ->route('admin.users.index')
                ->with([
                    'alert_type' => AlertType::WARNING,
                    'alert_message' => 'Nie można usunąć użytkownika który ma zapsiane symulacje.'
                ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Usunięto użytkowników!'
            ]);
    }
}
