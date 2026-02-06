<?php
// =====================================================
// OBTER AULAS POR MODALIDADE
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Receber modalidade
$modalidade = isset($_GET['modalidade']) ? sanitizar($_GET['modalidade']) : '';

if (!in_array($modalidade, ['spinning', 'aerobicos', 'funcional'])) {
    resposta_json(false, 'Modalidade inválida');
}

// Buscar aulas ativas da modalidade
$stmt = $conexao->prepare("
    SELECT id, nome, instrutor, horario, nivel, capacidade, descricao
    FROM aulas
    WHERE ativo = 'sim' AND modalidade = ?
    ORDER BY horario ASC
");

$stmt->bind_param("s", $modalidade);
$stmt->execute();
$resultado = $stmt->get_result();

$aulas = [];

while ($aula = $resultado->fetch_assoc()) {

    // Contar inscrições
    $stmtCount = $conexao->prepare("
        SELECT COUNT(*) as total
        FROM inscricoes
        WHERE aula_id = ? AND status = 'confirmado'
    ");
    $stmtCount->bind_param("i", $aula['id']);
    $stmtCount->execute();
    $count_resultado = $stmtCount->get_result();
    $count = $count_resultado->fetch_assoc();

    $aula['inscritos'] = (int)$count['total'];
    $aula['vagas_disponiveis'] = $aula['capacidade'] - $aula['inscritos'];

    $aulas[] = $aula;
}

resposta_json(true, 'Aulas carregadas com sucesso', $aulas);

$conexao->close();
