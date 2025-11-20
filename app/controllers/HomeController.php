<?php
class HomeController extends Controller
{
    public function index()
    {
        // If user is logged in, show dashboard; otherwise show login page
        if (!empty($_SESSION['user'])) {
            $this->render('dashboard', ['user' => $_SESSION['user']]);
            return;
        }

        // Not logged in — render the Bootstrap login form (reuse login view)
        $this->render('login');
    }
}
