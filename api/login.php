<?php
// =====================================================
// LOGIN DE USUÁRIOS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// 🔒 Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido'
    ]);
    exit;
}

// 📩 Dados
$email = isset($_POST['email']) ? sanitizar($_POST['email']) : '';
$senha = $_POST['senha'] ?? '';

// ✅ Validações
if (empty($email) || !validar_email($email)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Email inválido'
    ]);
    exit;
}

if (empty($senha)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Senha obrigatória'
    ]);
    exit;
}

// 🔍 Busca usuário
$stmt = $conexao->prepare("
    SELECT id, nome, email, telefone, senha, tipo
    FROM usuarios
    WHERE email = ? AND status = 'ativo'
");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Email ou senha incorretos'
    ]);
    exit;
}

$usuario = $resultado->fetch_assoc();

// 🔐 Verifica senha
if (!verificar_senha($senha, $usuario['senha'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Email ou senha incorretos'
    ]);
    exit;
}

// 🔒 Sessão
iniciar_sessao_segura();

$_SESSION['logado'] = true;
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_email'] = $usuario['email'];
$_SESSION['usuario_tipo'] = $usuario['tipo'];

// 🚦 Redirecionamento ABSOLUTO (isso resolve 90% dos bugs)
$redirect = '/sparten-main/dashboard.php';

if ($usuario['tipo'] === 'admin') {
    $redirect = '/sparten-main/admin.php';
}

// ✅ Resposta FINAL padronizada
echo json_encode([
    'sucesso' => true,
    'mensagem' => 'Login realizado com sucesso',
    'redirect' => $redirect
]);

$stmt->close();
$conexao->close();
exit;
