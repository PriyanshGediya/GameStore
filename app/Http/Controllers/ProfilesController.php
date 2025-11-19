<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\SendOtpMail;

class ProfilesController extends Controller
{
    // ------------------------------
    // Password OTP Workflow
    // ------------------------------
    public function requestPasswordOtp(Request $request)
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);

        // Store OTP in session
        session([
            'otp_for' => 'password_change',
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return back()->with('status', 'An OTP has been sent to your email to begin the password change process.');
    }

    public function verifyPasswordOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (session('otp_for') !== 'password_change' || now()->gt(session('otp_expires_at'))) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired.']);
        }

        if ($request->otp == session('otp')) {
            // OTP verified
            $request->session()->forget(['otp_for', 'otp', 'otp_expires_at']);
            session(['password_otp_verified' => true]);

            return back()->with('status', 'OTP verified. You can now set your new password.');
        }

        return back()->withErrors(['otp' => 'The OTP you entered is incorrect.']);
    }

    public function updatePasswordAfterOtp(Request $request)
    {
        if (!session('password_otp_verified')) {
            return redirect()->route('customer.profile.edit')
                ->withErrors(['error' => 'Please verify with an OTP before changing your password.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        // Forget the verification flag
        $request->session()->forget('password_otp_verified');

        return redirect()->route('customer.profile.edit')->with('success', 'Password changed successfully!');
    }

    // ------------------------------
    // Email Change Workflow
    // ------------------------------
    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = Auth::user();
        $otp = rand(100000, 999999);

        // Store email change OTP and new email in session
        session([
            'otp_for' => 'email_change',
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'new_email' => $request->email
        ]);

        // Send OTP to the new email
        Mail::to($request->email)->send(new SendOtpMail($otp));

        return back()->with('status', 'An OTP has been sent to your new email. Please verify to change your email.');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (session('otp_for') !== 'email_change' || now()->gt(session('otp_expires_at'))) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired.']);
        }

        if ($request->otp == session('otp')) {
            $user = Auth::user();
            $newEmail = session('new_email');

            $user->update(['email' => $newEmail]);

            // Clear session data
            $request->session()->forget(['otp_for', 'otp', 'otp_expires_at', 'new_email']);

            return redirect()->route('customer.profile.edit')->with('success', 'Email changed successfully!');
        }

        return back()->withErrors(['otp' => 'The OTP you entered is incorrect.']);
    }

    // ------------------------------
    // Profile Management
    // ------------------------------
    public function edit()
    {
        $profile = Auth::user();
        return view('Profile.profile_customer', compact('profile'));
    }

    public function myProfile()
    {
        $profile = Auth::user();

        if (!$profile) {
            abort(404, 'User profile not found.');
        }

        return view('Profile.profile_customer', compact('profile'));
    }

    public function updateProfileView()
{
    $profile = Auth::user(); // use logged-in admin info

    if (!$profile) {
        abort(404, 'User not found.');
    }

    return view('Profile.profile_admin', compact('profile'));
}

    
    public function updatePicture(Request $request)
{
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]); 

    $user = Auth::user();

    if ($request->hasFile('profile_picture')) {
        $image = $request->file('profile_picture');
        $name = time() . '_' . $image->getClientOriginalName();

        // Store in storage/app/public/profile_pictures
        $path = $image->storeAs('profile_pictures', $name, 'public');

        // Optionally delete old picture
        if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
            \Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = $path; // store relative path like 'profile_pictures/filename.png'
        $user->save();
    }

    return redirect()->back()->with('success', 'Profile picture updated successfully!');
}

    public function updateProfileLogic($id, Request $request)
    {
        $profile = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
        ]);

        $profile->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('Profile.profile_admin', ['id' => $profile->id])
            ->with('success', 'Profile updated successfully!');
    }
    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:Male,Female,Other',
    ]);

    $user->update([
        'name' => $request->name,
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
    ]);

    return redirect()->back()->with('success', 'Profile updated successfully!');
}
// Step 1: Email Input Page
    public function forgotPasswordPage()
    {
        return view('auth.forgot_password');
    }

    // Step 2: Send OTP
    public function sendPasswordOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        $otp = rand(100000, 999999);

        session([
            'reset_email' => $user->email,
            'reset_otp'   => $otp,
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('forgot_password.verify_page')->with('success', 'OTP sent to your email.');
    }

    // Step 3: Show OTP Verification Page
    public function verifyPasswordOtpPage()
    {
        return view('auth.verify_password_otp');
    }

    // Step 4: Verify OTP
    public function verifyForgotPasswordOtp(Request $request)
{
    $request->validate(['otp' => 'required|numeric']);

    if ($request->otp == session('reset_otp')) {
        return view('auth.reset_password'); // Show reset form
    }

    return back()->withErrors(['otp' => 'Invalid OTP.']);
}

    // Step 5: Reset Password
    public function resetPassword(Request $request)
    {
        // Validate passwords
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Find the user by email in session or token (depends on your workflow)
        $email = session('reset_email'); // assuming you stored email after OTP verification
        if (!$email) {
            return redirect()->route('forgot_password.request')->with('error', 'Email not found. Please try again.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('forgot_password.request')->with('error', 'User not found.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear the session
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successfully. You can now login.');
    }

}
