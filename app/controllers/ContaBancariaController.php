<?php
class ContaBancariaController extends Controller
{
    public function index(): void
    {
        $model = new ContaBancaria();
        $contas = $model->getAllGrouped();
        $usuarios = (new Usuario())->getAllActive();
        $this->view('contas/index', compact('contas', 'usuarios'));
    }

    public function create(): void
    {
        $this->requireAdmin();
        $usuarios = (new Usuario())->getAllActive();
        $this->view('contas/form', ['conta' => null, 'usuarios' => $usuarios]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $data = $this->validateInput();
        (new ContaBancaria())->create($data);
        setFlash('success', 'Conta cadastrada.');
        redirect('/contas');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $conta = (new ContaBancaria())->findById((int) $id);
        if (!$conta) { redirect('/contas'); return; }
        $usuarios = (new Usuario())->getAllActive();
        $this->view('contas/form', compact('conta', 'usuarios'));
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $data = $this->validateInput();
        (new ContaBancaria())->update((int) $id, $data);
        setFlash('success', 'Conta atualizada.');
        redirect('/contas');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new ContaBancaria())->update((int) $id, ['ativo' => 0]);
        setFlash('success', 'Conta desativada.');
        redirect('/contas');
    }

    private function validateInput(): array
    {
        return [
            'usuario_id' => !empty($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null,
            'nome' => trim($_POST['nome'] ?? ''),
            'banco' => trim($_POST['banco'] ?? ''),
            'tipo' => $_POST['tipo'] ?? 'pessoal',
            'proprietario' => trim($_POST['proprietario'] ?? ''),
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
    }
}
