<?php
class ConfigController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $usuarios = (new Usuario())->getAllActive();
        $categorias = (new Categoria())->getActive();
        $this->view('config/index', compact('usuarios', 'categorias'));
    }

    public function updateUsuario(string $id): void
    {
        $this->requireAdmin();
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];
        if (!empty($_POST['senha'])) {
            $data['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }
        (new Usuario())->update((int) $id, $data);
        // Atualizar sessão se for o próprio usuário
        if ((int) $id === $this->user()['id']) {
            $_SESSION['user']['nome'] = $data['nome'];
            $_SESSION['user']['email'] = $data['email'];
        }
        setFlash('success', 'Usuário atualizado.');
        redirect('/configuracoes');
    }

    public function addCategoria(): void
    {
        $this->requireAdmin();
        (new Categoria())->create([
            'nome' => trim($_POST['nome'] ?? ''),
            'tipo' => $_POST['tipo'] ?? 'ambos',
            'cor' => $_POST['cor'] ?? '#6366f1',
            'icone' => trim($_POST['icone'] ?? 'folder'),
        ]);
        setFlash('success', 'Categoria criada.');
        redirect('/configuracoes');
    }

    public function deleteCategoria(string $id): void
    {
        $this->requireAdmin();
        (new Categoria())->update((int) $id, ['ativo' => 0]);
        setFlash('success', 'Categoria desativada.');
        redirect('/configuracoes');
    }
}
