<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// OBTER AGENDAMENTOS DO USUÁRIO
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar login
$usuario_id = verificar_login();

// Buscar agendamentos do usuário
$stmt = $conexao->prepare("
    SELECT 
        ag.id,
        ag.codigo_unico,
        ag.status,
        ag.data_criacao,
        a.nome AS nome_aula,
        a.dias_semana,
        a.horario,
        a.nivel
    FROM agendamentos_teste ag
    LEFT JOIN aulas a ON ag.aula_id = a.id
    WHERE ag.usuario_id = ?
    ORDER BY ag.id DESC
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$agendamentos = [];
while ($agendamento = $resultado->fetch_assoc()) {
    $agendamentos[] = $agendamento;
}

resposta_json(true, 'Agendamentos carregados com sucesso', $agendamentos);

$stmt->close();
$conexao->close();

?>
