<?php
// =====================================================
// CANCELAR AGENDAMENTO DE AULA TESTE
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resposta_json(false, 'Método não permitido');
}

// Verificar login
$usuario_id = verificar_login();

// Receber dados
$agendamento_id = isset($_POST['agendamento_id']) ? (int)$_POST['agendamento_id'] : 0;

if (empty($agendamento_id)) {
    resposta_json(false, 'Agendamento inválido');
}

// Verificar se agendamento existe e pertence ao usuário
$stmt = $conexao->prepare("SELECT id, codigo_unico FROM agendamentos_teste WHERE id = ? AND usuario_id = ? AND status = 'confirmado'");
$stmt->bind_param("ii", $agendamento_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    resposta_json(false, 'Agendamento não encontrado ou já cancelado');
}

$agendamento = $resultado->fetch_assoc();

// Cancelar agendamento
$stmt = $conexao->prepare("UPDATE agendamentos_teste SET status = 'cancelado' WHERE id = ?");
$stmt->bind_param("i", $agendamento_id);

if ($stmt->execute()) {
    resposta_json(true, 'Agendamento cancelado com sucesso!', [
        'codigo_cancelado' => $agendamento['codigo_unico']
    ]);
} else {
    resposta_json(false, 'Erro ao cancelar agendamento: ' . $conexao->error);
}

$stmt->close();
$conexao->close();

?>
