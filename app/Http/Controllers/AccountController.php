<?php

namespace App\Http\Controllers;
use Storage\app\public;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class AccountController extends Controller
{
    // This method shows the register page
    public function register()
    {
        return view('account.register');
    }

    // This method registers a user
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:5',
            'name' => 'required|min:3',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have registered successfully.');
    }

    // This method shows the login page
    public function login()
    {
        return view('account.login');
    }

    // This method authenticates the user
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.profile')->with('success', 'Login successful.');
        }

        return redirect()->route('account.login')->withInput()->with('error', 'Email or password is incorrect.');
    }

    // This method shows the profile page
    public function profile()
    {
        $user = User::find(Auth::id());

        return view('account.profile', compact('user'));
    }

    // This method updates the user profile with image
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate image file types and size
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('uploads/profile/');
            $thumbPath = public_path('uploads/profile/thumb/');

            // Ensure directories exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0755, true);
            }

            // Process and save the image
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->getRealPath());

            // Resize and save thumbnail
            $img->cover(150, 150);
            $img->save($thumbPath . $imageName);

            // Save the main image
            $image->move($uploadPath, $imageName);

            // Delete old image if it exists
            if ($user->image && file_exists($uploadPath . $user->image)) {
                unlink($uploadPath . $user->image);
            }
            if ($user->image && file_exists($thumbPath . $user->image)) {
                unlink($thumbPath . $user->image);
            }

            // Update user image
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }

    // This method logs out the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You have logged out.');
    }
}
