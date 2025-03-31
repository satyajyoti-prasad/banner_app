<?php
namespace App\Controllers;

use App\Models\AdminModel;
use Config\Services;

class AuthController extends BaseController
{
    protected $adminModel;

    /**
     * Constructor to initialize models and check admin authentication.
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
        helper(['form', 'url']);  // Load form and URL helpers
    }

    // Display login page
    public function login()
    {
        // If already logged in, redirect to admin dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $data = [];
        // Display logout message if set in cookie
        if (isset($_COOKIE['logout_message'])) {
            $data['logout_message'] = $_COOKIE['logout_message'];
            setcookie('logout_message', '', time() - 3600, '/');  // Clear cookie
        }

        return view('auth/login', $data);
    }

    // Handle login attempt
    public function attemptLogin()
    {
        // Validation rules for login
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Retrieve admin user from database
        $admin = $this->adminModel->where('au_username', $this->request->getPost('username'))->first();

        // Verify password and handle login failure
        if (!$admin || !password_verify($this->request->getPost('password'), $admin['au_password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials');
        }

        // Set session data for logged-in admin
        session()->set([
            'admin_logged_in' => true,
            'admin_id' => $admin['au_id'],
            'admin_username' => $admin['au_username']
        ]);

        return redirect()->to('/admin');
    }

    // Logout admin and destroy session
    public function logout()
    {
        // Set a logout message in a cookie
        setcookie(
            'logout_message',
            'You have been logged out successfully',
            [
                'expires' => time() + 60,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        session()->destroy();  // Destroy session
        return redirect()->to('/login');
    }

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('auth/forgot_password');
    }

    // Process forgot password request
    public function processForgotPassword()
    {
        $validation = Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $admin = $this->adminModel->where('au_username', $email)->first();

        if (!$admin) {
            return redirect()->back()->with('error', "If that email exists in our system, we've sent a password reset link.");
        }

        // Generate reset token and expiry time
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save reset token and expiry in database
        $this->adminModel->update($admin['au_id'], [
            'au_reset_token' => $token,
            'au_reset_expires' => $expires
        ]);

        $this->sendResetEmail($admin['au_username'], $token);

        return redirect()->to('/forgot-password')->with('success', 'If that email exists in our system, we will send a password reset link shortly.');
    }

    // Show reset password form
    public function showResetPassword($token)
    {
        // Validate token
        $admin = $this
            ->adminModel
            ->where('au_reset_token', $token)
            ->where('au_reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$admin) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth/reset_password', [
            'token' => $token,
            'validation' => Services::validation()
        ]);
    }

    // Process password reset request
    public function processResetPassword()
    {
        $validation = Services::validation();
        $validation->setRules([
            'token' => 'required',
            'password' => 'required|min_length[12]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/]',
            'password_confirm' => 'required|matches[password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $token = $this->request->getPost('token');
        $admin = $this
            ->adminModel
            ->where('au_reset_token', $token)
            ->where('au_reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$admin) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        // Update password and remove reset token
        $this->adminModel->update($admin['au_id'], [
            'au_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'au_reset_token' => null,
            'au_reset_expires' => null
        ]);

        return redirect()->to('/login')->with('success', 'Your password has been reset successfully.');
    }

    // Send password reset email
    protected function sendResetEmail($email, $token)
    {
        $emailService = \Config\Services::email();
        $resetLink = site_url("reset-password/$token");

        if (ENVIRONMENT !== 'production') {
            session()->setFlashdata('dev_reset_link', $resetLink);
            return true;
        }

        try {
            $emailService->setTo($email);
            $emailService->setSubject('Password Reset Request');
            $emailService->setMessage(view('auth/email/reset_password', ['resetLink' => $resetLink]));

            return $emailService->send();
        } catch (\Exception $e) {
            log_message('error', "Email sending error: {$e->getMessage()}");
            return false;
        }
    }
}
