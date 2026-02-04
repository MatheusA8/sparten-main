<?php
// =====================================================
// LOGIN DE USUÁRIOS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resposta_json(false, 'Método não permitido');
}

// Receber dados
$email = isset($_POST['email']) ? sanitizar($_POST['email']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

// Validações
if (empty($email) || !validar_email($email)) {
    resposta_json(false, 'Email inválido');
}

if (empty($senha)) {
    resposta_json(false, 'Senha obrigatória');
}

// Buscar usuário
$stmt = $conexao->prepare("SELECT id, nome, email, telefone, senha FROM usuarios WHERE email = ? AND status = 'ativo'");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    resposta_json(false, 'Email ou senha incorretos');
}

$usuario = $resultado->fetch_assoc();

// Verificar senha
if (!verificar_senha($senha, $usuario['senha'])) {
    resposta_json(false, 'Email ou senha incorretos');
}

// Iniciar sessão
iniciar_sessao_segura();
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_email'] = $usuario['email'];
$_SESSION['usuario_telefone'] = $usuario['telefone'];

resposta_json(true, 'Login realizado com sucesso!', [
    'usuario_id' => $usuario['id'],
    'nome' => $usuario['nome'],
    'email' => $usuario['email']
]);

$stmt->close();
$conexao->close();

?>
