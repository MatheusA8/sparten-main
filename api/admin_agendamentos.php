<?php
// =====================================================
// ADMIN - GERENCIAR AGENDAMENTOS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é GET ou POST
$metodo = $_SERVER['REQUEST_METHOD'];
$acao = isset($_GET['acao']) ? sanitizar($_GET['acao']) : '';

// GET - Obter agendamentos
if ($metodo === 'GET') {

    $filtro = isset($_GET['filtro']) ? sanitizar($_GET['filtro']) : 'confirmado';

    $sql = "
        SELECT 
            ag.id,
            ag.codigo_unico,
            ag.status,
            ag.data_criacao,
            u.nome,
            u.email,
            u.telefone,
            a.nome AS nome_aula,
            a.horario
        FROM agendamentos_teste ag
        LEFT JOIN usuarios u ON ag.usuario_id = u.id
        LEFT JOIN aulas a ON ag.aula_id = a.id
    ";

    if ($filtro !== 'todos') {
        $sql .= " WHERE ag.status = '$filtro'";
    }

    $sql .= " ORDER BY ag.data_criacao DESC";

    $resultado = $conexao->query($sql);

    if (!$resultado) {
        resposta_json(false, 'Erro ao buscar agendamentos: ' . $conexao->error);
    }

    $agendamentos = [];
    while ($agendamento = $resultado->fetch_assoc()) {
        $agendamentos[] = $agendamento;
    }

    resposta_json(true, 'Agendamentos carregados com sucesso', $agendamentos);
}


// POST - Marcar como realizado ou cancelar
if ($metodo === 'POST') {
    
    // Marcar como realizado
    if ($acao === 'realizado') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID do agendamento inválido');
        }

        $stmt = $conexao->prepare("
            SELECT 
                ag.id,
                ag.codigo_unico,
                ag.status,
                ag.data_criacao,
                u.nome AS nome,
                u.email AS email,
                u.telefone AS telefone,
                a.nome AS nome_aula,
                a.horario AS horario
            FROM agendamentos_teste ag
            LEFT JOIN usuarios u ON ag.usuario_id = u.id
            LEFT JOIN aulas a ON ag.aula_id = a.id
            ORDER BY ag.data_criacao DESC
        ");




        if ($stmt->execute()) {
            resposta_json(true, 'Agendamento marcado como realizado!');
        } else {
            resposta_json(false, 'Erro ao atualizar: ' . $conexao->error);
        }
    }

    // Cancelar agendamento
    if ($acao === 'cancelar') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID do agendamento inválido');
        }

        $stmt = $conexao->prepare("UPDATE agendamentos_teste SET status = 'cancelado' WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Agendamento cancelado!');
        } else {
            resposta_json(false, 'Erro ao cancelar: ' . $conexao->error);
        }
    }

    // Deletar agendamento
    if ($acao === 'deletar') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID do agendamento inválido');
        }

        $stmt = $conexao->prepare("DELETE FROM agendamentos_teste WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Agendamento deletado!');
        } else {
            resposta_json(false, 'Erro ao deletar: ' . $conexao->error);
        }
    }
}

resposta_json(false, 'Ação inválida');

$conexao->close();

?>
