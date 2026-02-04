<?php
// =====================================================
// INSCREVER EM AULA DE SPINNING
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
$aula_id = isset($_POST['aula_id']) ? (int)$_POST['aula_id'] : 0;

if (empty($aula_id)) {
    resposta_json(false, 'Aula inválida');
}

// Verificar se aula existe
$stmt = $conexao->prepare("SELECT id, capacidade FROM aulas_spinning WHERE id = ?");
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    resposta_json(false, 'Aula não encontrada');
}

$aula = $resultado->fetch_assoc();

// Contar inscrições
$stmt = $conexao->prepare("SELECT COUNT(*) as total FROM inscricoes WHERE aula_id = ? AND status = 'confirmado'");
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$count_resultado = $stmt->get_result();
$count = $count_resultado->fetch_assoc();

if ($count['total'] >= $aula['capacidade']) {
    resposta_json(false, 'Aula cheia! Sem vagas disponíveis');
}

// Verificar se já está inscrito
$stmt = $conexao->prepare("SELECT id FROM inscricoes WHERE usuario_id = ? AND aula_id = ? AND status = 'confirmado'");
$stmt->bind_param("ii", $usuario_id, $aula_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    resposta_json(false, 'Você já está inscrito nesta aula');
}

// Inserir inscrição
$stmt = $conexao->prepare("INSERT INTO inscricoes (usuario_id, aula_id, status) VALUES (?, ?, 'confirmado')");
$stmt->bind_param("ii", $usuario_id, $aula_id);

if ($stmt->execute()) {
    resposta_json(true, 'Inscrição realizada com sucesso!', [
        'inscricao_id' => $stmt->insert_id,
        'aula_id' => $aula_id
    ]);
} else {
    resposta_json(false, 'Erro ao inscrever: ' . $conexao->error);
}

$stmt->close();
$conexao->close();

?>
