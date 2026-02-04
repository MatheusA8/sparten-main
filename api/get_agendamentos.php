<?php
// =====================================================
// OBTER AGENDAMENTOS DO USUÁRIO
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar login
$usuario_id = verificar_login();

// Buscar agendamentos do usuário
$stmt = $conexao->prepare("SELECT id, codigo_unico, nome, email, telefone, data_agendamento, horario, nivel, status, data_criacao FROM agendamentos_teste WHERE usuario_id = ? ORDER BY data_agendamento DESC");
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
