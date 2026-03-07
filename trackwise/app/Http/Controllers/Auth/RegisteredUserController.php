<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  Request $request the incoming HTTP request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Seed default income categories for new user
        $incomeCategories = ['Salary', 'Freelance', 'Investment', 'Gift'];
        foreach ($incomeCategories as $name) {
            $user->categories()->create([
                'name'  => $name,
                'type'  => 'income',
                'color' => '#10B981',
            ]);
        }

        // Seed default expense categories for new user
        $expenseCategories = ['Food', 'Transport', 'Housing', 'Utilities',
            'Healthcare', 'Entertainment', 'Shopping'];
        foreach ($expenseCategories as $name) {
            $user->categories()->create([
                'name'  => $name,
                'type'  => 'expense',
                'color' => '#EF4444',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}