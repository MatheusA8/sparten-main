<?php
// =====================================================
// OBTER INSCRIÇÕES DO USUÁRIO
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar login
$usuario_id = verificar_login();
if (!$usuario_id) {
    resposta_json(false, 'Usuário não logado');
}

// Buscar inscrições do usuário (TODAS as modalidades)
$stmt = $conexao->prepare("
    SELECT 
        i.id AS inscricao_id,
        i.data_inscricao,
        i.status,
        a.id AS aula_id,
        a.nome,
        a.instrutor,
        a.horario,
        a.nivel,
        a.modalidade,
        a.descricao
    FROM inscricoes i
    JOIN aulas a ON i.aula_id = a.id
    WHERE i.usuario_id = ?
      AND i.status = 'confirmado'
    ORDER BY a.modalidade, a.horario
");

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$inscricoes = [];
while ($inscricao = $resultado->fetch_assoc()) {
    $inscricoes[] = $inscricao;
}

resposta_json(true, 'Inscrições carregadas com sucesso', $inscricoes);

$stmt->close();
$conexao->close();
?>
