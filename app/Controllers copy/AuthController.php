<?php
namespace App\Controllers;

use App\Models\AdminModel;
use Config\Services;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class AuthController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->userModel = new AdminModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        // $session = session();
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $data = [];

        // Check for logout message cookie
        if (isset($_COOKIE['logout_message'])) {
            $data['logout_message'] = $_COOKIE['logout_message'];

            // Clear the cookie
            setcookie('logout_message', '', time() - 3600, '/');
        }

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $admin = $this->adminModel->where('username', $this->request->getPost('username'))->first();
        // password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        $inputPassword = $this->request->getPost('password');
        $storedHash = $admin['password'];
        // $this->adminModel->verifyPassword($inputPassword, $storedHash);
        if (!$admin || !$this->adminModel->verifyPassword($inputPassword, $storedHash)) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials');
        }

        session()->set([
            'admin_logged_in' => true,
            'admin_id' => $admin['id'],
            'admin_username' => $admin['username']
        ]);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        // 1. Set a plain cookie with the message
        setcookie(
            'logout_message',
            'You have been logged out successfully',
            [
                'expires' => time() + 60,  // 1 minute expiration
                'path' => '/',
                'secure' => false,  // Set to true in production with HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        // 2. Destroy the session
        session()->destroy();

        // 3. Redirect to login
        return redirect()->to('/login');
    }

    public function showForgotPassword()
    {
        return view('auth/forgot_password');
    }

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
        // $userModel = new UserModel();
        $userModel = new AdminModel();

        $user = $userModel->where('username', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', "If that email exists in our system, we've sent a password reset link.");
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token to database

        $userModel->update($user['id'], [
            'reset_token' => $token,
            'reset_expires' => $expires
        ]);
        /* $db = \Config\Database::connect();
        $lastQuery = $db->getLastQuery();
        echo $lastQuery;
        die; */
        // Send email here
        $this->sendResetEmail($user['username'], $token);

        $message = 'If that email exists in our system, we will send a password reset link shortly.';

        // Add development hint if needed
        if (ENVIRONMENT !== 'production' && session()->getFlashdata('dev_reset_link')) {
            $message .= ' Check the displayed link below.';
        }

        return redirect()->to('/forgot-password')->with('success', $message);
    }

    public function showResetPassword($token)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel
            ->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$admin) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        // Pass token to view in an array
        return view('auth/reset_password', [
            'token' => $token,
            'validation' => Services::validation()  // Also pass validation service if needed
        ]);
    }

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
        $adminModel = new AdminModel();
        $admin = $adminModel
            ->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$admin) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        // Get the new password
        $newPassword = $this->request->getPost('password');

        // Update using the model's built-in hashing via beforeUpdate callback
        $adminModel->update($admin['id'], [
            'password' => $newPassword,  // Will be automatically hashed by model callback
            'reset_token' => null,
            'reset_expires' => null
        ]);

        // Verify the new password was set correctly (optional security check)
        $updatedAdmin = $adminModel->find($admin['id']);
        if (!password_verify($newPassword, $updatedAdmin['password'])) {
            log_message('error', 'Password reset failed for admin ID: ' . $admin['id']);
            return redirect()->to('/forgot-password')->with('error', 'Password reset failed. Please try again.');
        }
        // session()->setFlashdata('dev_reset_link', $resetLink);
        return redirect()->to('/login')->with('success', 'Your password has been reset successfully.');
    }

    protected function sendResetEmail($email, $token)
    {
        $emailService = \Config\Services::email();
        $resetLink = site_url("reset-password/$token");

        // For development/local environment
        if (ENVIRONMENT !== 'production') {
            // Store the link in session to display on screen
            session()->setFlashdata('dev_reset_link', $resetLink);

            // Log the link instead of sending email
            // log_message('info', "DEV MODE: Password reset link for {$email}: {$resetLink}");

            // You could also save the email content to a file
            // return view('auth/reset_password', ['resetLink' => $resetLink]);

            // $emailContent = view('auth/reset_password', ['resetLink' => $resetLink]);
            // file_put_contents(WRITEPATH . 'email_logs/latest_reset_email.html', $emailContent);

            return true;  // Pretend email was sent
        }

        // For production environment
        try {
            $emailService->setTo($email);
            $emailService->setSubject('Password Reset Request');
            $emailService->setMessage(view('auth/email/reset_password', [
                'resetLink' => $resetLink
            ]));

            if (!$emailService->send()) {
                log_message('error', "Failed to send password reset email to {$email}");
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', "Email sending error: {$e->getMessage()}");
            return false;
        }
    }
}
