<?php
// =====================================================
// OBTER INSCRIÇÕES DO USUÁRIO
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar login
$usuario_id = verificar_login();

// Buscar inscrições do usuário
$stmt = $conexao->prepare("
    SELECT 
        i.id as inscricao_id,
        i.data_inscricao,
        i.status,
        a.id as aula_id,
        a.nome,
        a.instrutor,
        a.horario,
        a.nivel,
        a.descricao
    FROM inscricoes i
    JOIN aulas_spinning a ON i.aula_id = a.id
    WHERE i.usuario_id = ? AND i.status = 'confirmado'
    ORDER BY a.horario ASC
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
