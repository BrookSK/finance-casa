<?php
class FaturaController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $faturas = (new Fatura())->getByMonth($mes, $ano);
        $this->view('faturas/index', compact('faturas', 'mes', 'ano'));
    }

    public function marcarPaga(string $id): void
    {
        (new Fatura())->update((int) $id, ['status' => 'paga']);
        setFlash('success', 'Fatura marcada como paga.');
        redirect('/faturas');
    }
}
