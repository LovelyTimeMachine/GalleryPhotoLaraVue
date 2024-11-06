<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Photo;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage; // <-- Add this line
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function updatePicture(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Check if the user has an existing profile picture
        if ($user->profile_picture) {
            // Delete the old profile picture
            Storage::delete($user->profile_picture);
        }
    
        // Store the new profile picture in the specified directory
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    
        // Update the user's profile picture path to include '/storage/'
        $user->profile_picture = '/storage/' . $path; // Prepend 'storage/' to the path
        $user->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }


public function show(User $user)
{
    // Fetch photos related to the user
    $photos = $user->photos()->with('user')->get(); // Ensure the relationship is eager loaded if needed

    return Inertia::render('Profile/UserProfile', [
        'user' => $user,
        'photos' => $photos, // Pass the photos array to the component
    ]);
}


public function index()
{
    $users = User::all();
    return Inertia::render('UserManagement', [
        'users' => $users,
        'user' => auth()->user(),
    ]);
}


public function handle($request, Closure $next)
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request);
    }
    return redirect('/'); // Redirect jika bukan admin
}



 // Method to fetch all users
 public function getUsers()
 {
     \Log::info('Fetching users');
     return response()->json(User::all());
 }
 

    // Delete a user by ID
    public function adminDestroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

}