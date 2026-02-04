<?php
// =====================================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// =====================================================

// Dados de conexão
$host = 'localhost';
$usuario_db = 'root';
$senha_db = '';
$nome_db = 'sparten_academia';

// Criar conexão
$conexao = new mysqli($host, $usuario_db, $senha_db);

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Selecionar banco de dados (criar se não existir)
if (!$conexao->select_db($nome_db)) {
    // Se não existir, criar o banco
    $sql_criar_db = "CREATE DATABASE IF NOT EXISTS $nome_db";
    if ($conexao->query($sql_criar_db) === TRUE) {
        $conexao->select_db($nome_db);
        
        // Executar script SQL para criar tabelas
        $sql_script = file_get_contents('../database.sql');
        if ($sql_script) {
            // Dividir por ponto e vírgula e executar cada comando
            $comandos = array_filter(array_map('trim', explode(';', $sql_script)));
            foreach ($comandos as $comando) {
                if (!empty($comando)) {
                    $conexao->query($comando);
                }
            }
        }
    }
}

// Configurar charset
$conexao->set_charset("utf8mb4");

// Definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

// =====================================================
// FUNÇÕES AUXILIARES
// =====================================================

/**
 * Gerar código único para agendamento
 * Formato: SPIN-XXXXX (ex: SPIN-A7K2M9)
 */
function gerar_codigo_unico() {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = 'SPIN-';
    for ($i = 0; $i < 6; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

/**
 * Verificar se código já existe no banco
 */
function codigo_existe($codigo, $conexao) {
    $stmt = $conexao->prepare("SELECT id FROM agendamentos_teste WHERE codigo_unico = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0;
}

/**
 * Gerar código único garantindo que não existe
 */
function gerar_codigo_unico_valido($conexao) {
    do {
        $codigo = gerar_codigo_unico();
    } while (codigo_existe($codigo, $conexao));
    return $codigo;
}

/**
 * Validar email
 */
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validar telefone (apenas números)
 */
function validar_telefone($telefone) {
    $telefone_limpo = preg_replace('/\D/', '', $telefone);
    return strlen($telefone_limpo) >= 10 && strlen($telefone_limpo) <= 11;
}

/**
 * Hash de senha
 */
function hash_senha($senha) {
    return password_hash($senha, PASSWORD_BCRYPT);
}

/**
 * Verificar senha
 */
function verificar_senha($senha, $hash) {
    return password_verify($senha, $hash);
}

/**
 * Sanitizar entrada
 */
function sanitizar($dados) {
    global $conexao;
    return $conexao->real_escape_string(trim($dados));
}

/**
 * Resposta JSON
 */
function resposta_json($sucesso, $mensagem, $dados = null) {
    header('Content-Type: application/json');
    $resposta = [
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ];
    if ($dados !== null) {
        $resposta['dados'] = $dados;
    }
    echo json_encode($resposta);
    exit;
}

/**
 * Iniciar sessão segura
 */
function iniciar_sessao_segura() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        session_regenerate_id(true);
    }
}

/**
 * Verificar se usuário está logado
 */
function verificar_login() {
    iniciar_sessao_segura();
    if (!isset($_SESSION['usuario_id'])) {
        resposta_json(false, 'Usuário não autenticado');
    }
    return $_SESSION['usuario_id'];
}

?>
