<?php
class AuthController extends Controller
{
    public function login()
    {
        // If POST, attempt login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username']];
                header('Location: /HydroAlert/');
                exit;
            }

            // Login failed — re-render with error
            $this->render('login', ['error' => 'Invalid credentials', 'username' => $username]);
            return;
        }

        // GET — show login form
        $this->render('login');
    }

    public function logout()
    {
        unset($_SESSION['user']);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        header('Location: /HydroAlert/');
        exit;
    }
}
