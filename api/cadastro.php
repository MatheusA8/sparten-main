<?php
// =====================================================
// CADASTRO DE USUÁRIOS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resposta_json(false, 'Método não permitido');
}

// Receber dados
$nome = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
$email = isset($_POST['email']) ? sanitizar($_POST['email']) : '';
$telefone = isset($_POST['telefone']) ? sanitizar($_POST['telefone']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';

// Validações
if (empty($nome) || strlen($nome) < 3) {
    resposta_json(false, 'Nome deve ter pelo menos 3 caracteres');
}

if (empty($email) || !validar_email($email)) {
    resposta_json(false, 'Email inválido');
}

if (empty($telefone) || !validar_telefone($telefone)) {
    resposta_json(false, 'Telefone inválido (mínimo 10 dígitos)');
}

if (empty($senha) || strlen($senha) < 6) {
    resposta_json(false, 'Senha deve ter pelo menos 6 caracteres');
}

if ($senha !== $confirmar_senha) {
    resposta_json(false, 'Senhas não conferem');
}

// Verificar se email já existe
$stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    resposta_json(false, 'Email já cadastrado');
}

// Hash da senha
$senha_hash = hash_senha($senha);

// Inserir usuário
$stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $telefone, $senha_hash);

if ($stmt->execute()) {
    $usuario_id = $stmt->insert_id;
    
    // Iniciar sessão
    iniciar_sessao_segura();
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    
    resposta_json(true, 'Cadastro realizado com sucesso!', [
        'usuario_id' => $usuario_id,
        'nome' => $nome,
        'email' => $email
    ]);
} else {
    resposta_json(false, 'Erro ao cadastrar usuário: ' . $conexao->error);
}

$stmt->close();
$conexao->close();

?>
