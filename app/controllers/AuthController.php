<?php
class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (AuthMiddleware::check()) {
            redirect('/dashboard');
        }
        $this->viewOnly('auth/login');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['senha'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Preencha todos os campos.');
            redirect('/login');
            return;
        }

        $usuario = new Usuario();
        $user = $usuario->authenticate($email, $password);

        if (!$user) {
            setFlash('error', 'E-mail ou senha incorretos.');
            redirect('/login');
            return;
        }

        if (!$user['ativo']) {
            setFlash('error', 'Usuário desativado.');
            redirect('/login');
            return;
        }

        AuthMiddleware::login($user);
        redirect('/dashboard');
    }

    public function logout(): void
    {
        AuthMiddleware::logout();
        redirect('/login');
    }
}
