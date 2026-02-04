<?php
// =====================================================
// ADMIN - GERENCIAR AULAS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é GET ou POST
$metodo = $_SERVER['REQUEST_METHOD'];
$acao = isset($_GET['acao']) ? sanitizar($_GET['acao']) : '';

// GET - Obter aulas
if ($metodo === 'GET') {
    $sql = "SELECT id, nome, instrutor, horario, nivel, capacidade, descricao, ativo FROM aulas_spinning ORDER BY horario ASC";
    $resultado = $conexao->query($sql);

    if (!$resultado) {
        resposta_json(false, 'Erro ao buscar aulas: ' . $conexao->error);
    }

    $aulas = [];
    while ($aula = $resultado->fetch_assoc()) {
        $aulas[] = $aula;
    }

    resposta_json(true, 'Aulas carregadas com sucesso', $aulas);
}

// POST - Adicionar, editar ou deletar aula
if ($metodo === 'POST') {
    
    // Adicionar aula
    if ($acao === 'adicionar') {
        $nome = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
        $instrutor = isset($_POST['instrutor']) ? sanitizar($_POST['instrutor']) : '';
        $horario = isset($_POST['horario']) ? sanitizar($_POST['horario']) : '';
        $nivel = isset($_POST['nivel']) ? sanitizar($_POST['nivel']) : '';
        $capacidade = isset($_POST['capacidade']) ? (int)$_POST['capacidade'] : 0;
        $descricao = isset($_POST['descricao']) ? sanitizar($_POST['descricao']) : '';

        // Validações
        if (empty($nome) || empty($instrutor) || empty($horario) || empty($nivel) || $capacidade <= 0) {
            resposta_json(false, 'Preencha todos os campos obrigatórios');
        }

        if (!in_array($nivel, ['Iniciante', 'Intermediário', 'Avançado'])) {
            resposta_json(false, 'Nível inválido');
        }

        // Inserir aula
        $stmt = $conexao->prepare("INSERT INTO aulas_spinning (nome, instrutor, horario, nivel, capacidade, descricao) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $instrutor, $horario, $nivel, $capacidade, $descricao);

        if ($stmt->execute()) {
            resposta_json(true, 'Aula adicionada com sucesso!', ['aula_id' => $stmt->insert_id]);
        } else {
            resposta_json(false, 'Erro ao adicionar aula: ' . $conexao->error);
        }
    }

    // Editar aula
    if ($acao === 'editar') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nome = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
        $instrutor = isset($_POST['instrutor']) ? sanitizar($_POST['instrutor']) : '';
        $horario = isset($_POST['horario']) ? sanitizar($_POST['horario']) : '';
        $nivel = isset($_POST['nivel']) ? sanitizar($_POST['nivel']) : '';
        $capacidade = isset($_POST['capacidade']) ? (int)$_POST['capacidade'] : 0;
        $descricao = isset($_POST['descricao']) ? sanitizar($_POST['descricao']) : '';

        // Validações
        if (empty($id) || empty($nome) || empty($instrutor) || empty($horario) || empty($nivel) || $capacidade <= 0) {
            resposta_json(false, 'Preencha todos os campos obrigatórios');
        }

        // Atualizar aula
        $stmt = $conexao->prepare("UPDATE aulas_spinning SET nome = ?, instrutor = ?, horario = ?, nivel = ?, capacidade = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $instrutor, $horario, $nivel, $capacidade, $descricao, $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Aula atualizada com sucesso!');
        } else {
            resposta_json(false, 'Erro ao atualizar aula: ' . $conexao->error);
        }
    }

    // Deletar aula
    if ($acao === 'deletar') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID da aula inválido');
        }

        // Deletar aula
        $stmt = $conexao->prepare("DELETE FROM aulas_spinning WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Aula deletada com sucesso!');
        } else {
            resposta_json(false, 'Erro ao deletar aula: ' . $conexao->error);
        }
    }

    // Ativar/Desativar aula
    if ($acao === 'toggle') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID da aula inválido');
        }

        // Obter status atual
        $stmt = $conexao->prepare("SELECT ativo FROM aulas_spinning WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $aula = $resultado->fetch_assoc();

        $novo_status = $aula['ativo'] === 'sim' ? 'não' : 'sim';

        // Atualizar status
        $stmt = $conexao->prepare("UPDATE aulas_spinning SET ativo = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_status, $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Status atualizado com sucesso!', ['novo_status' => $novo_status]);
        } else {
            resposta_json(false, 'Erro ao atualizar status: ' . $conexao->error);
        }
    }
}

resposta_json(false, 'Ação inválida');

$conexao->close();

?>
