<?php
require_once 'config.php';

header('Content-Type: application/json');

$usuario_id = verificar_login();

$stmt = $conexao->prepare("SELECT nome, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($usuario = $resultado->fetch_assoc()) {
    echo json_encode([
        "sucesso" => true,
        "dados" => $usuario
    ]);
} else {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Usuário não encontrado"
    ]);
}

$stmt->close();
$conexao->close();
