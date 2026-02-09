<?php
// =====================================================
// AGENDAMENTO DE AULA AVULSA
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resposta_json(false, 'Método não permitido');
}

// Iniciar sessão e validar login
iniciar_sessao_segura();

if (!isset($_SESSION['usuario_id'])) {
    resposta_json(false, 'Usuário não autenticado');
}

$usuario_id = $_SESSION['usuario_id'];

// Receber aula
$aula_id = isset($_POST['aula_id']) ? (int) $_POST['aula_id'] : 0;

if ($aula_id <= 0) {
    resposta_json(false, 'Aula inválida');
}

// Buscar aula
$stmt = $conexao->prepare("
    SELECT id, nome, horario, capacidade, ativo
    FROM aulas
    WHERE id = ? AND ativo = 'sim'
");
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    resposta_json(false, 'Aula não encontrada ou inativa');
}

$aula = $result->fetch_assoc();
$stmt->close();

// Contar inscritos fixos
$stmt = $conexao->prepare("
    SELECT COUNT(*) AS total
    FROM inscricoes
    WHERE aula_id = ? AND status = 'confirmado'
");
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$fixos = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Contar avulsos
$stmt = $conexao->prepare("
    SELECT COUNT(*) AS total
    FROM agendamentos_teste
    WHERE aula_id = ? AND status = 'confirmado'
");
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$avulsos = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Validar vagas
if (($fixos + $avulsos) >= $aula['capacidade']) {
    resposta_json(false, 'Essa aula já está lotada');
}

// Gerar código único
$codigo_unico = gerar_codigo_unico_valido($conexao);

// Inserir agendamento avulso
$stmt = $conexao->prepare("
    INSERT INTO agendamentos_teste (codigo_unico, usuario_id, aula_id)
    VALUES (?, ?, ?)
");

$stmt->bind_param("sii", $codigo_unico, $usuario_id, $aula_id);

if ($stmt->execute()) {

    resposta_json(true, 'Aula avulsa agendada com sucesso!', [
        'codigo_unico' => $codigo_unico,
        'aula' => $aula['nome'],
        'horario' => $aula['horario'],
        'mensagem' => 'Apresente este código na portaria da academia.'
    ]);

} else {
    resposta_json(false, 'Erro ao agendar aula avulsa');
}

$stmt->close();
$conexao->close();
