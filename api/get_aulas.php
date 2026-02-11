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
    SELECT id, nome, dias_semana, instrutor, horario, nivel, capacidade, descricao
    FROM aulas
    WHERE ativo = 'sim' AND modalidade = ?
    ORDER BY horario ASC
");

$stmt->bind_param("s", $modalidade);
$stmt->execute();
$resultado = $stmt->get_result();

$aulas = [];

while ($aula = $resultado->fetch_assoc()) {

    // Contar inscritos fixos
    $stmtFixos = $conexao->prepare("
        SELECT COUNT(*) as total
        FROM inscricoes
        WHERE aula_id = ? AND status = 'confirmado'
    ");
    $stmtFixos->bind_param("i", $aula['id']);
    $stmtFixos->execute();
    $fixos = $stmtFixos->get_result()->fetch_assoc()['total'];
    $stmtFixos->close();
    
    // Contar avulsos
    $stmtAvulsos = $conexao->prepare("
        SELECT COUNT(*) as total
        FROM agendamentos_teste
        WHERE aula_id = ? AND status = 'confirmado'
    ");
    $stmtAvulsos->bind_param("i", $aula['id']);
    $stmtAvulsos->execute();
    $avulsos = $stmtAvulsos->get_result()->fetch_assoc()['total'];
    $stmtAvulsos->close();
    
    // Total ocupadas
    $totalOcupadas = $fixos + $avulsos;
    
    // Atualizar dados da aula
    $aula['inscritos'] = $totalOcupadas;
    $aula['vagas_disponiveis'] = $aula['capacidade'] - $totalOcupadas;
    
    $aulas[] = $aula;
    
    }

resposta_json(true, 'Aulas carregadas com sucesso', $aulas);

$conexao->close();
