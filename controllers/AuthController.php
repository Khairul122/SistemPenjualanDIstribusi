<?php
class AuthController {
    private $authModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ?controller=dashboard');
            exit;
        }
        loadView('auth/login');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $user = $this->authModel->login($username, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];
                
                header('Location: ?controller=dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Username atau password salah';
                header('Location: ?controller=auth');
                exit;
            }
        } else {
            $this->index();
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: ?controller=auth');
        exit;
    }
}
?>