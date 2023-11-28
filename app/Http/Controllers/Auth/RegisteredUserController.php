<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class, 'regex:/^[\w-]*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'invite_code' => ['required', 'string', 'size:10'],
        ]);
		
		$invite = Invite::where("id", trim($request->invite_code))->firstOrFail();
		$inviter = $invite->creator_id;
		$invite_hold = $invite->only(["id", "creator_id"]);
		$invite->delete();

		try {
			$user = User::create([
				'name' => $request->name,
				'display_name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
				'top_folder_id' => null,
				'invited_by' => $inviter
			]);
		} catch(Throwable $e) {
			$message = $e->getMessage();
			Invite::create($invite_hold);
			return redirect()->back()->withErrors("Account creation failed: $message. You may try again with the same invite code.");
		}

		$inviter->update([
			"invitee_count" => $inviter->invitee_count + 1
		]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

	public function messages() {
		return [ 'name.regex' => "Username should contain only alphanumeric characters and dashes." ];
	}
}
