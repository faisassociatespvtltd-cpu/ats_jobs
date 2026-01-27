<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeProfile;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Services\ResumeParserService;
use App\Mail\LoginOtpMail;

class AuthController extends Controller
{
    /**
     * Show the welcome/landing page with signup options.
     */
    public function welcome()
    {
        return view('auth.welcome');
    }

    /**
     * Show employee signup form (step 1: email).
     */
    public function showEmployeeSignup()
    {
        return view('auth.employee.signup-step1');
    }

    /**
     * Handle employee signup step 1 (email).
     */
    public function employeeSignupStep1(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $verificationToken = Str::random(64);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'employee',
            'email_verification_token' => $verificationToken,
        ]);

        // Send verification email
        $this->sendVerificationEmail($user);

        Auth::login($user);

        return redirect()->route('employee.signup.step2');
    }

    /**
     * Show employee signup step 2 (CV upload).
     */
    public function showEmployeeSignupStep2()
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            return redirect()->route('employee.signup');
        }

        return view('auth.employee.signup-step2');
    }

    /**
     * Handle employee signup step 2 (CV upload).
     */
    public function employeeSignupStep2(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $user = Auth::user();

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $user->cv_path = $cvPath;
            $user->save();
        }

        return redirect()->route('employee.signup.step3');
    }

    /**
     * Show employee signup step 3 (personal details).
     */
    public function showEmployeeSignupStep3()
    {
        if (!Auth::check() || !Auth::user()->isEmployee() || !Auth::user()->cv_path) {
            return redirect()->route('employee.signup.step2');
        }

        $user = Auth::user();
        $parsedData = [];

        if ($user->cv_path) {
            try {
                $parser = new ResumeParserService();
                $parsedData = $parser->parseFromStorage($user->cv_path);
            } catch (\Exception $e) {
                \Log::error('Failed to parse resume during signup', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        return view('auth.employee.signup-step3', compact('parsedData'));
    }

    /**
     * Handle employee signup step 3 (personal details).
     */
    public function employeeSignupStep3(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            return redirect()->route('employee.signup');
        }

        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:20',
            'expected_salary' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:2000',
            'experience' => 'nullable|string|max:2000',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $profileData = [
            'user_id' => $user->id,
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'cnic' => $request->cnic,
            'expected_salary' => $request->expected_salary,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'skills' => $request->skills,
            'experience' => $request->experience,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ];

        $profile = EmployeeProfile::create($profileData);

        $user->name = $request->name;
        $user->save();

<<<<<<< HEAD
        // --- OTP Logic Start ---
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store in Database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send Email
        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email: " . $e->getMessage());
        }
        
        // Logout & Prepare for OTP Verify
        Auth::logout();
        session(['login_user_id' => $user->id]);

        return redirect()->route('otp.verify');
        // --- OTP Logic End ---
=======
        $this->sendOtpEmail($user);
        session([
            'login_user_id' => $user->id,
            'login_remember' => false,
        ]);

        return redirect()->route('otp.verify')->with('toast', [
            'type' => 'info',
            'message' => 'OTP sent to your email. Please verify to continue.',
        ]);
>>>>>>> dd1538a4cef0190e6616c85e2baf39ddc4242a83
    }

    /**
     * Show employee profile completion page.
     */
    public function showEmployeeProfileComplete()
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            return redirect()->route('employee.signup');
        }

        return view('auth.employee.profile-complete');
    }

    /**
     * Show employer signup form (step 1: email).
     */
    public function showEmployerSignup()
    {
        return view('auth.employer.signup-step1');
    }

    /**
     * Handle employer signup step 1 (email).
     */
    public function employerSignupStep1(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $verificationToken = Str::random(64);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'employer',
            'email_verification_token' => $verificationToken,
        ]);

        // Send verification email
        $this->sendVerificationEmail($user);

        Auth::login($user);

        return redirect()->route('employer.signup.step2');
    }

    /**
     * Show employer signup step 2 (logo upload).
     */
    public function showEmployerSignupStep2()
    {
        if (!Auth::check() || !Auth::user()->isEmployer()) {
            return redirect()->route('employer.signup');
        }

        return view('auth.employer.signup-step2');
    }

    /**
     * Handle employer signup step 2 (logo upload).
     */
    public function employerSignupStep2(Request $request)
    {
        $request->validate([
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            $user->company_logo_path = $logoPath;
            $user->save();
        }

        return redirect()->route('employer.signup.step3');
    }

    /**
     * Show employer signup step 3 (company details).
     */
    public function showEmployerSignupStep3()
    {
        if (!Auth::check() || !Auth::user()->isEmployer() || !Auth::user()->company_logo_path) {
            return redirect()->route('employer.signup.step2');
        }

        return view('auth.employer.signup-step3');
    }

    /**
     * Handle employer signup step 3 (company details).
     */
    public function employerSignupStep3(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'contact_person_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'website_url' => 'nullable|url',
            'company_description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'company_type' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
        ]);

        $user = Auth::user();

        EmployerProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'contact_person_name' => $request->contact_person_name,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'website_url' => $request->website_url,
            'company_description' => $request->company_description,
            'industry' => $request->industry,
            'company_size' => $request->company_size,
            'license_number' => $request->license_number,
            'registration_number' => $request->registration_number,
            'tax_number' => $request->tax_number,
            'company_type' => $request->company_type,
            'founded_year' => $request->founded_year,
        ]);

        $user->name = $request->company_name;
        $user->save();

<<<<<<< HEAD
        // --- OTP Logic Start ---
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store in Database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send Email
        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email: " . $e->getMessage());
        }

        // Logout & Prepare for OTP Verify
        Auth::logout();
        session(['login_user_id' => $user->id]);

        return redirect()->route('otp.verify');
        // --- OTP Logic End ---
=======
        $this->sendOtpEmail($user);
        session([
            'login_user_id' => $user->id,
            'login_remember' => false,
        ]);

        return redirect()->route('otp.verify')->with('toast', [
            'type' => 'info',
            'message' => 'OTP sent to your email. Please verify to continue.',
        ]);
>>>>>>> dd1538a4cef0190e6616c85e2baf39ddc4242a83
    }

    /**
     * Show employer profile completion page.
     */
    public function showEmployerProfileComplete()
    {
        if (!Auth::check() || !Auth::user()->isEmployer()) {
            return redirect()->route('employer.signup');
        }

        return view('auth.employer.profile-complete');
    }

    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

            if (!$user->email_verified_at) {
                $this->sendOtpEmail($user);
                session([
                    'login_user_id' => $user->id,
                    'login_remember' => $request->boolean('remember'),
                ]);
                return redirect()->route('otp.verify')->withErrors([
                    'email' => 'Please verify your email with the OTP sent to your inbox.',
                ]);
            }

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                if (Auth::user()->isEmployee()) {
                    return redirect()->intended(route('employee.profile'))->with('toast', [
                        'type' => 'success',
                        'message' => 'Login successful! Welcome back.',
                    ]);
                }

                return redirect()->intended(route('employer.profile'))->with('toast', [
                    'type' => 'success',
                    'message' => 'Login successful! Welcome back.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show OTP verify form.
     */
    public function showOtpVerify()
    {
        if (!session()->has('login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    /**
     * Handle OTP verification.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (!session()->has('login_user_id')) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $userId = session('login_user_id');
        $user = User::find($userId);

        if (!$user) {
            session()->forget(['login_user_id', 'login_remember']);
            return redirect()->route('login')->with('error', 'User not found.');
        }

        if (!$user->otp || !$user->otp_expires_at || $user->otp !== $request->otp || $user->otp_expires_at < now()) {
             return back()->withErrors(['otp' => 'Invalid or expired OTP code. Please try again.']);
        }

        $remember = session('login_remember', false);
        Auth::login($user, $remember);
        
        // Clear OTP
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);
        
        session()->forget(['login_user_id', 'login_remember']);
        $request->session()->regenerate();

<<<<<<< HEAD
        // Redirect to Dashboard
        return redirect()->intended(route('dashboard'));
=======
        // Redirect based on user type
        if (Auth::user()->isEmployee()) {
            return redirect()->intended(route('employee.profile'))->with('toast', [
                'type' => 'success',
                'message' => 'OTP verified successfully! Welcome.',
            ]);
        }

        return redirect()->intended(route('employer.profile'))->with('toast', [
            'type' => 'success',
            'message' => 'OTP verified successfully! Welcome.',
        ]);
>>>>>>> dd1538a4cef0190e6616c85e2baf39ddc4242a83
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    /**
     * Verify email.
     */
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid verification token.');
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Email verified successfully. You can now login.');
    }

    /**
     * Send verification email.
     */
    private function sendVerificationEmail(User $user)
    {
        $verificationUrl = route('email.verify', ['token' => $user->email_verification_token]);

        // For now, we'll just log it. In production, use Mail::send()
        \Log::info("Verification email for {$user->email}: {$verificationUrl}");

        // Uncomment below to send actual emails (requires mail configuration)
        /*
        Mail::send('emails.verify', ['url' => $verificationUrl], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Verify Your Email Address');
        });
        */
    }

    private function sendOtpEmail(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        \Log::info("OTP for {$user->email}: {$otp}");

        // Uncomment to send actual emails (requires mail configuration)
        /*
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Your OTP Code');
        });
        */
    }
}
