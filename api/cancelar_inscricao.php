<?php
// =====================================================
// CANCELAR INSCRIÇÃO EM AULA
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
$inscricao_id = isset($_POST['inscricao_id']) ? (int)$_POST['inscricao_id'] : 0;

if (empty($inscricao_id)) {
    resposta_json(false, 'Inscrição inválida');
}

// Verificar se inscrição existe e pertence ao usuário
$stmt = $conexao->prepare("SELECT id FROM inscricoes WHERE id = ? AND usuario_id = ? AND status = 'confirmado'");
$stmt->bind_param("ii", $inscricao_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    resposta_json(false, 'Inscrição não encontrada ou já cancelada');
}

// Cancelar inscrição
$stmt = $conexao->prepare("UPDATE inscricoes SET status = 'cancelado' WHERE id = ?");
$stmt->bind_param("i", $inscricao_id);

if ($stmt->execute()) {
    resposta_json(true, 'Inscrição cancelada com sucesso!');
} else {
    resposta_json(false, 'Erro ao cancelar inscrição: ' . $conexao->error);
}

$stmt->close();
$conexao->close();

?>
