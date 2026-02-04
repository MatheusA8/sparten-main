<?php
// =====================================================
// OBTER AULAS DE SPINNING
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Buscar todas as aulas ativas
$sql = "SELECT id, nome, instrutor, horario, nivel, capacidade, descricao FROM aulas_spinning WHERE ativo = 'sim' ORDER BY horario ASC";
$resultado = $conexao->query($sql);

if (!$resultado) {
    resposta_json(false, 'Erro ao buscar aulas: ' . $conexao->error);
}

$aulas = [];
while ($aula = $resultado->fetch_assoc()) {
    // Contar inscrições
    $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM inscricoes WHERE aula_id = ? AND status = 'confirmado'");
    $stmt->bind_param("i", $aula['id']);
    $stmt->execute();
    $count_resultado = $stmt->get_result();
    $count = $count_resultado->fetch_assoc();
    
    $aula['inscritos'] = $count['total'];
    $aula['vagas_disponiveis'] = $aula['capacidade'] - $count['total'];
    
    $aulas[] = $aula;
}

resposta_json(true, 'Aulas carregadas com sucesso', $aulas);

$conexao->close();

?>
