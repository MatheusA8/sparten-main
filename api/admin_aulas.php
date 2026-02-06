<?php
// =====================================================
// ADMIN - GERENCIAR AULAS
// =====================================================

require_once 'config.php';

header('Content-Type: application/json');

// Método e ação
$metodo = $_SERVER['REQUEST_METHOD'];
$acao   = isset($_GET['acao']) ? sanitizar($_GET['acao']) : '';

// =====================================================
// GET - Obter aulas
// =====================================================
if ($metodo === 'GET') {

    $sql = "SELECT 
                id,
                modalidade,
                nome,
                instrutor,
                horario,
                nivel,
                capacidade,
                descricao,
                ativo
            FROM aulas
            ORDER BY horario ASC";

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

// =====================================================
// POST - Ações do admin
// =====================================================
if ($metodo === 'POST') {

    // =================================================
    // ADICIONAR AULA
    // =================================================
    if ($acao === 'adicionar') {

        $modalidade = isset($_POST['modalidade']) ? sanitizar($_POST['modalidade']) : '';
        $nome       = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
        $instrutor  = isset($_POST['instrutor']) ? sanitizar($_POST['instrutor']) : '';
        $horario    = isset($_POST['horario']) ? sanitizar($_POST['horario']) : '';
        $nivel      = isset($_POST['nivel']) ? sanitizar($_POST['nivel']) : '';
        $capacidade = isset($_POST['capacidade']) ? (int)$_POST['capacidade'] : 0;
        $descricao  = isset($_POST['descricao']) ? sanitizar($_POST['descricao']) : '';

        // Validações
        if (
            empty($modalidade) ||
            empty($nome) ||
            empty($instrutor) ||
            empty($horario) ||
            empty($nivel) ||
            $capacidade <= 0
        ) {
            resposta_json(false, 'Preencha todos os campos obrigatórios');
        }

        if (!in_array($modalidade, ['spinning', 'aerobicos', 'funcional'])) {
            resposta_json(false, 'Modalidade inválida');
        }

        if (!in_array($nivel, ['Iniciante', 'Intermediário', 'Avançado'])) {
            resposta_json(false, 'Nível inválido');
        }

        // Inserir aula
        $stmt = $conexao->prepare("
            INSERT INTO aulas
                (modalidade, nome, instrutor, horario, nivel, capacidade, descricao)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssis",
            $modalidade,
            $nome,
            $instrutor,
            $horario,
            $nivel,
            $capacidade,
            $descricao
        );

        if ($stmt->execute()) {
            resposta_json(true, 'Aula adicionada com sucesso!', [
                'aula_id' => $stmt->insert_id
            ]);
        } else {
            resposta_json(false, 'Erro ao adicionar aula: ' . $conexao->error);
        }
    }

    // =================================================
    // EDITAR AULA
    // =================================================
    if ($acao === 'editar') {

        $id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $modalidade = isset($_POST['modalidade']) ? sanitizar($_POST['modalidade']) : '';
        $nome       = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
        $instrutor  = isset($_POST['instrutor']) ? sanitizar($_POST['instrutor']) : '';
        $horario    = isset($_POST['horario']) ? sanitizar($_POST['horario']) : '';
        $nivel      = isset($_POST['nivel']) ? sanitizar($_POST['nivel']) : '';
        $capacidade = isset($_POST['capacidade']) ? (int)$_POST['capacidade'] : 0;
        $descricao  = isset($_POST['descricao']) ? sanitizar($_POST['descricao']) : '';

        if (
            empty($id) ||
            empty($modalidade) ||
            empty($nome) ||
            empty($instrutor) ||
            empty($horario) ||
            empty($nivel) ||
            $capacidade <= 0
        ) {
            resposta_json(false, 'Preencha todos os campos obrigatórios');
        }

        $stmt = $conexao->prepare("
            UPDATE aulas
            SET
                modalidade = ?,
                nome = ?,
                instrutor = ?,
                horario = ?,
                nivel = ?,
                capacidade = ?,
                descricao = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssisi",
            $modalidade,
            $nome,
            $instrutor,
            $horario,
            $nivel,
            $capacidade,
            $descricao,
            $id
        );

        if ($stmt->execute()) {
            resposta_json(true, 'Aula atualizada com sucesso!');
        } else {
            resposta_json(false, 'Erro ao atualizar aula: ' . $conexao->error);
        }
    }

    // =================================================
    // DELETAR AULA
    // =================================================
    if ($acao === 'deletar') {

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID da aula inválido');
        }

        $stmt = $conexao->prepare("DELETE FROM aulas WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Aula deletada com sucesso!');
        } else {
            resposta_json(false, 'Erro ao deletar aula: ' . $conexao->error);
        }
    }

    // =================================================
    // ATIVAR / DESATIVAR AULA
    // =================================================
    if ($acao === 'toggle') {

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if (empty($id)) {
            resposta_json(false, 'ID da aula inválido');
        }

        $stmt = $conexao->prepare("SELECT ativo FROM aulas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $aula = $resultado->fetch_assoc();

        $novo_status = ($aula['ativo'] === 'sim') ? 'não' : 'sim';

        $stmt = $conexao->prepare("UPDATE aulas SET ativo = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_status, $id);

        if ($stmt->execute()) {
            resposta_json(true, 'Status atualizado com sucesso!', [
                'novo_status' => $novo_status
            ]);
        } else {
            resposta_json(false, 'Erro ao atualizar status: ' . $conexao->error);
        }
    }
}

resposta_json(false, 'Ação inválida');

$conexao->close();
