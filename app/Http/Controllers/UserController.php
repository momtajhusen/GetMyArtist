<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('AdminPanel.Users_Artists.users', compact('users'));
    }
    

    public function store(Request $request)
    {
        // Validation with custom English messages
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user,artist',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be one of: admin, user, or artist.'
        ]);

        // Creating user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Auto-login after registration
        Auth::login($user);

        // Flash a success message before redirecting
        session()->flash('success', 'Registration successful!');

        // Redirect based on role with success message
        return $this->redirectUserBasedOnRole($user);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email-username' => 'required|string',
            'password'       => 'required|min:6',
        ], [
            'email-username.required' => 'Email or Username is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.'
        ]);

        // Determine whether input is email or username
        $credentials = filter_var($request->input('email-username'), FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->input('email-username'), 'password' => $request->password]
            : ['name' => $request->input('email-username'), 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectUserBasedOnRole(Auth::user());
        }

        return back()->with('error', 'Invalid email/username or password.');
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role;
    
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.login')->with('success', 'Logout successful!');
            case 'artist':
                return redirect()->route('artist.login')->with('success', 'Logout successful!');
            default:
                return redirect()->route('user.login')->with('success', 'Logout successful!');
        }
    }
    

    // Updated redirect logic based on folder structure and defined routes
    private function redirectUserBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard.overview')->with('success', 'Welcome Admin!');
            case 'artist':
                return redirect()->route('artist.dashboard')->with('success', 'Welcome Artist!');
            case 'user':
            default:
                return redirect()->route('user.dashboard')->with('success', 'Welcome User!');
        }
    }    
}
