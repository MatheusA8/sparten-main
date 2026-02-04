<?php
// =====================================================
// AGENDAMENTO DE AULA TESTE
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
$data_agendamento = isset($_POST['data']) ? sanitizar($_POST['data']) : '';
$horario = isset($_POST['horario']) ? sanitizar($_POST['horario']) : '';
$nivel = isset($_POST['nivel']) ? sanitizar($_POST['nivel']) : '';

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

if (empty($data_agendamento)) {
    resposta_json(false, 'Data obrigatória');
}

// Validar data (não pode ser no passado)
$data_agendamento_obj = DateTime::createFromFormat('Y-m-d', $data_agendamento);
if (!$data_agendamento_obj || $data_agendamento_obj->format('Y-m-d') !== $data_agendamento) {
    resposta_json(false, 'Data inválida');
}

$hoje = new DateTime();
if ($data_agendamento_obj < $hoje) {
    resposta_json(false, 'Data não pode ser no passado');
}

if (empty($horario)) {
    resposta_json(false, 'Horário obrigatório');
}

if (empty($nivel) || !in_array($nivel, ['Iniciante', 'Intermediário', 'Avançado'])) {
    resposta_json(false, 'Nível inválido');
}

// Gerar código único
$codigo_unico = gerar_codigo_unico_valido($conexao);

// Verificar se usuário está logado
$usuario_id = null;
iniciar_sessao_segura();
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
}

// Inserir agendamento
$stmt = $conexao->prepare("INSERT INTO agendamentos_teste (codigo_unico, usuario_id, nome, email, telefone, data_agendamento, horario, nivel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssss", $codigo_unico, $usuario_id, $nome, $email, $telefone, $data_agendamento, $horario, $nivel);

if ($stmt->execute()) {
    $agendamento_id = $stmt->insert_id;
    
    resposta_json(true, 'Agendamento realizado com sucesso!', [
        'agendamento_id' => $agendamento_id,
        'codigo_unico' => $codigo_unico,
        'nome' => $nome,
        'email' => $email,
        'data' => $data_agendamento,
        'horario' => $horario,
        'nivel' => $nivel,
        'mensagem' => 'Seu código de agendamento é: ' . $codigo_unico . '. Guarde-o bem!'
    ]);
} else {
    resposta_json(false, 'Erro ao agendar: ' . $conexao->error);
}

$stmt->close();
$conexao->close();

?>
