<?php

class AuthController extends Controller
{
    private $userModel;
    private $employeeModel;
    private $auditLog;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->employeeModel = new Employee();
        $this->auditLog = new AuditLog();
    }

    public function showLogin()
    {
        $this->view('auth.login');
    }

    public function login()
    {
        if (!Request::isPost()) {
            $this->redirect('/login');
            return;
        }

        // CSRF Protection (skip for now to avoid breaking existing functionality)
        // TODO: Uncomment when all forms have CSRF tokens
        // Request::validateCsrf();

        $validation = Request::validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation !== true) {
            Session::flash('errors', $validation);
            Session::flash('old', Request::all());
            $this->redirect('/login');
            return;
        }

        $email = Request::input('email');
        $password = Request::input('password');

        $user = $this->userModel->authenticate($email, $password);

        if ($user) {
            Session::regenerate();
            Session::set('user', $user);

            // Get employee profile
            $employee = $this->employeeModel->findByUserId($user['id']);
            if ($employee) {
                Session::set('employee', $employee);
            }

            // Audit log
            $this->auditLog->log($user['id'], 'login', 'user', $user['id']);

            $this->redirect('/dashboard');
        } else {
            Session::flash('error', 'Invalid credentials');
            Session::flash('old', ['email' => $email]);
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        $user = Session::get('user');
        if ($user) {
            try {
                $this->auditLog->log($user['id'], 'logout', 'user', $user['id']);
            } catch (Exception $e) {
                // Silently fail if audit log fails (e.g., user doesn't exist)
                // User logout should still succeed
            }
        }

        Session::destroy();
        $this->redirect('/login');
    }
}
