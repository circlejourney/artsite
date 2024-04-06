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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Throwable;

use function PHPUnit\Framework\throwException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $firstuser = User::count() === 0;
        return view('auth.register', ["firstuser" => $firstuser]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $firstuser = User::count() === 0;
        $params = [
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class, 'regex:/^[\w-]*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
        if(!$firstuser) $params['invite_code'] = ['required', 'string', 'exists:invites,id'];
        
        $request->validate($params);
		
		DB::beginTransaction();
		try {
			$invite = Invite::where("id", trim($request->invite_code))->first();
			$inviterID = $invite ? $invite->creator_id : null;
			if($invite) $invite->delete();

			$user = User::create([
				'name' => $request->name,
				'display_name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
				'top_folder_id' => null,
				'invited_by' => $inviterID
			]);
		} catch (Throwable $err) {
			return redirect()->back()->withErrors("Account creation failed; try again with the same invite code. ".$err->getMessage());
		}
		DB::commit();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

	public function messages() {
		return [ 'name.regex' => "Username should contain only alphanumeric characters and hyphens." ];
	}
}
