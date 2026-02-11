<?php
session_start();
require_once __DIR__ . '/api/auth.php';
verificar_login();


// se não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.html");
    exit;
}

// se estiver logado mas NÃO for admin
if ($_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Painel Admin - Sparten</title>
    <style>
        .admin-container {
            min-height: 100vh;

            padding: 100px 20px 50px;
        }

        .admin-header {
            max-width: 1200px;
            margin: 0 auto 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .admin-header h1 {
            font-size: 2.5rem;
            color: #ff0000;
            margin: 0;
        }

        .btn-sair {
            background: #ff0000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-sair:hover {
            background: #ff3333;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        }

        .admin-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid #ff0000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .tab-btn.ativo {
            background: #ff0000;
            color: #fff;
        }

        .tab-btn:hover {
            background: #ff0000;
            color: #fff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.ativo {
            display: block;
        }

        .form-aula {
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid #ff0000;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #fff;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #ff0000;
            border-radius: 5px;
            color: #fff;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }

        .btn-submit {
            background: #ff0000;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            background: #ff3333;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .tabela th {
            background: rgba(255, 0, 0, 0.2);
            color: #ff0000;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ff0000;
        }

        .tabela td {
            color: #fff;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 0, 0, 0.2);
        }

        .tabela tr:hover {
            background: rgba(255, 0, 0, 0.15);
        }

        .btn-acao {
            background: #ff0000;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn-acao:hover {
            background: #ff3333;
        }

        .btn-acao.verde {
            background: #0f0;
            color: #000;
        }

        .btn-acao.verde:hover {
            background: #0f0;
        }

        .btn-acao.vermelho {
            background: #f44336;
        }

        .btn-acao.vermelho:hover {
            background: #d32f2f;
        }

        .filtro-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filtro-container button {
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid #ff0000;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filtro-container button.ativo {
            background: #ff0000;
        }

        .filtro-container button:hover {
            background: #ff0000;
        }

        .loading {
            text-align: center;
            color: #ff0000;
            padding: 20px;
        }

        .mensagem {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .mensagem.sucesso {
            background: rgba(0, 255, 0, 0.2);
            color: #0f0;
            border: 1px solid #0f0;
        }

        .mensagem.erro {
            background: rgba(255, 0, 0, 0.2);
            color: #ff3333;
            border: 1px solid #ff3333;
        }

        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tabela {
                font-size: 0.9rem;
            }

            .tabela th,
            .tabela td {
                padding: 8px;
            }

            .btn-acao {
                padding: 4px 8px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 768px) {
        
            table {
                display: none;
            }
        
            .agendamento-card {
                background: #111;
                border: 1px solid red;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                color: white;
            }
        
            .agendamento-card p {
                margin: 5px 0;
                font-size: 14px;
            }
        
            .card-buttons {
                margin-top: 10px;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
        
        }

        
    </style>
</head>
<body>
    <!-- Logo flutuante -->
    <img src="images/PNGlogo-Photoroom.png" alt="Logo" class="logo-flutuante-global">

    <!-- Header fixo -->
    <header>    
        <button class="menu-btn">•••</button>
        <ul class="menu">
            <li><a href="index.php#inicio">INÍCIO</a></li>
            <li><a href="index.php#sobre">SOBRE</a></li>
            <li><a href="index.php#planos">PLANOS E VALORES</a></li>
            <li><a href="index.php#estrutura">ESTRUTURA</a></li>
            <li><a href="index.php#equipe">EQUIPE</a></li>
            <li><a href="index.php#clientes">CLIENTES</a></li>
            <li><a href="index.php#localizacao">LOCALIZAÇÃO</a></li>
            <li><a href="index.php#horarios">HORÁRIOS</a></li>
            <li><a href="index.php#contatos">CONTATOS</a></li>
        </ul>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1>PAINEL ADMIN</h1>
            <button class="btn-sair" onclick="sair()">SAIR</button>
        </div>

        <div class="admin-content">
            <div class="mensagem" id="mensagem"></div>

            <!-- Abas -->
            <div class="admin-tabs">
                <button class="tab-btn ativo" onclick="abrirAba('aulas')">Gerenciar Aulas</button>
                <button class="tab-btn" onclick="abrirAba('agendamentos')">Agendamentos</button>
            </div>

            <!-- AULAS -->
            <div id="aulas" class="tab-content ativo">
                <div class="form-aula">
                    <h2 style="color: #ff0000; margin-top: 0;">Adicionar Nova Aula</h2>
                    <div class="form-group">
                        <label>Modalidade</label>
                        <select id="modalidade" required>
                            <option value="">Selecione</option>
                            <option value="spinning">Spinning</option>
                            <option value="aerobicos">Step Dance</option>
                            <option value="funcional">Funcional</option>
                        </select>
                    </div>
                    <form id="formAula">
                        <div class="form-group">
                            <label>Nome da Aula</label>
                            <input type="text" id="nome" placeholder="Ex: Power Spin" required>
                        </div>
                        <div class="form-group">
                            <label>Instrutor</label>
                            <input type="text" id="instrutor" placeholder="Nome do instrutor" required>
                        </div>
                        <div class="form-group">
                            <label>Horário</label>
                            <input type="text" id="horario" placeholder="Ex: 06:00 - 07:00" required>
                        </div>
                        <div class="form-group">
                            <label>Nível</label>
                            <select id="nivel" required>
                                <option value="">Selecione</option>
                                <option value="Iniciante">Iniciante</option>
                                <option value="Intermediário">Intermediário</option>
                                <option value="Avançado">Avançado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Capacidade (máximo de alunos)</label>
                            <input type="number" id="capacidade" min="1" placeholder="Ex: 20" required>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea id="descricao" placeholder="Descreva a aula..."></textarea>
                        </div>
                        <button type="submit" class="btn-submit">ADICIONAR AULA</button>
                    </form>
                </div>

                <h2 style="color: #ff0000; margin-top: 40px;">Aulas Existentes</h2>
                <div class="loading" id="loadingAulas">Carregando aulas...</div>
                <div id="aulasTabela"></div>
            </div>

            <!-- AGENDAMENTOS -->
            <div id="agendamentos" class="tab-content">
                <div class="filtro-container">
                    <button class="ativo" onclick="filtrarAgendamentos('confirmado')">Confirmados</button>
                    <button onclick="filtrarAgendamentos('realizado')">Realizados</button>
                    <button onclick="filtrarAgendamentos('cancelado')">Cancelados</button>
                    <button onclick="filtrarAgendamentos('todos')">Todos</button>
                </div>

                <div class="loading" id="loadingAgendamentos">Carregando agendamentos...</div>
                <div id="agendamentosTabela"></div>
            </div>
        </div>
    </div>

    <script src="style/script.js" defer></script>
    <script>
        const formAula = document.getElementById('formAula');

    formAula.addEventListener('submit', async (e) => {
        e.preventDefault();
    
        const modalidade = document.getElementById('modalidade').value;
        const nome = document.getElementById('nome').value;
        const instrutor = document.getElementById('instrutor').value;
        const horario = document.getElementById('horario').value;
        const nivel = document.getElementById('nivel').value;
        const capacidade = document.getElementById('capacidade').value;
        const descricao = document.getElementById('descricao').value;
    
        try {
            const formData = new FormData();
            formData.append('modalidade', modalidade);
            formData.append('nome', nome);
            formData.append('instrutor', instrutor);
            formData.append('horario', horario);
            formData.append('nivel', nivel);
            formData.append('capacidade', capacidade);
            formData.append('descricao', descricao);
    
            const response = await fetch('api/admin_aulas.php?acao=adicionar', {
                method: 'POST',
                body: formData
            });
    
            const data = await response.json();
    
            if (data.sucesso) {
                mostrarMensagem('Aula adicionada com sucesso!', 'sucesso');
                formAula.reset();
                carregarAulas();
            } else {
                mostrarMensagem(data.mensagem, 'erro');
            }
        } catch (erro) {
            mostrarMensagem('Erro ao adicionar aula', 'erro');
            console.error(erro);
        }
    });
    

        async function carregarAulas() {
            const container = document.getElementById('aulasTabela');
            const loading = document.getElementById('loadingAulas');

            try {
                const response = await fetch('api/admin_aulas.php');
                const data = await response.json();

                loading.style.display = 'none';

                if (data.sucesso && data.dados.length > 0) {
                    let html = '<table class="tabela"><thead><tr><th>Nome</th><th>Instrutor</th><th>Horário</th><th>Nível</th><th>Capacidade</th><th>Status</th><th>Ações</th></tr></thead><tbody>';
                    data.dados.forEach(aula => {
                        html += `
                            <tr>
                                <td>${aula.nome}</td>
                                <td>${aula.instrutor}</td>
                                <td>${aula.horario}</td>
                                <td>${aula.nivel}</td>
                                <td>${aula.capacidade}</td>
                                <td>${aula.ativo === 'sim' ? '<span style="color: #0f0;">Ativo</span>' : '<span style="color: #ff3333;">Inativo</span>'}</td>
                                <td>
                                    <button class="btn-acao verde" onclick="toggleAula(${aula.id})">${aula.ativo === 'sim' ? 'Desativar' : 'Ativar'}</button>
                                    <button class="btn-acao vermelho" onclick="deletarAula(${aula.id})">Deletar</button>
                                </td>
                            </tr>
                        `;
                    });
                    html += '</tbody></table>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p style="color: #fff;">Nenhuma aula cadastrada</p>';
                }
            } catch (erro) {
                loading.style.display = 'none';
                container.innerHTML = '<p style="color: #ff3333;">Erro ao carregar aulas</p>';
                console.error(erro);
            }
        }

        async function toggleAula(aulaId) {
            try {
                const formData = new FormData();
                formData.append('id', aulaId);

                const response = await fetch('api/admin_aulas.php?acao=toggle', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.sucesso) {
                    mostrarMensagem('Status atualizado!', 'sucesso');
                    carregarAulas();
                } else {
                    mostrarMensagem(data.mensagem, 'erro');
                }
            } catch (erro) {
                mostrarMensagem('Erro ao atualizar', 'erro');
            }
        }

        async function deletarAula(aulaId) {
            if (!confirm('Tem certeza que deseja deletar esta aula?')) return;

            try {
                const formData = new FormData();
                formData.append('id', aulaId);

                const response = await fetch('api/admin_aulas.php?acao=deletar', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.sucesso) {
                    mostrarMensagem('Aula deletada!', 'sucesso');
                    carregarAulas();
                } else {
                    mostrarMensagem(data.mensagem, 'erro');
                }
            } catch (erro) {
                mostrarMensagem('Erro ao deletar', 'erro');
            }
        }

        async function carregarAgendamentos(filtro = 'confirmado') {
            const container = document.getElementById('agendamentosTabela');
            const loading = document.getElementById('loadingAgendamentos');

            try {
                const response = await fetch(`api/admin_agendamentos.php?filtro=${filtro}`);
                const data = await response.json();
                console.log(data);


                loading.style.display = 'none';

                if (data.sucesso && data.dados.length > 0) {
                    let html = '<table class="tabela"><thead><tr><th>Código</th><th>Cliente</th><th>Email</th><th>Telefone</th><th>Aula</th><th>Data</th><th>Horário</th><th>Status</th><th>Ações</th></tr></thead><tbody>';
                    data.dados.forEach(agendamento => {
                        html += `
                            <tr>
                                <td><strong>${agendamento.codigo_unico}</strong></td>
                                    <td>${agendamento.nome}</td>
                                    <td>${agendamento.email}</td>
                                    <td>${agendamento.telefone}</td>
                                    <td>${agendamento.nome_aula}</td>
                                    <td>${agendamento.data_criacao}</td>
                                    <td>${agendamento.horario}</td>
                                    <td>${agendamento.status}</td>
                                <td>
                                    ${agendamento.status === 'confirmado' ? `
                                        <button class="btn-acao verde" onclick="marcarRealizado(${agendamento.id})">Realizado</button>
                                        <button class="btn-acao vermelho" onclick="cancelarAgendamento(${agendamento.id})">Cancelar</button>
                                    ` : `
                                        <button class="btn-acao vermelho" onclick="deletarAgendamento(${agendamento.id})">Deletar</button>
                                    `}
                                </td>
                            </tr>

                        `;
                    });
                    html += '</tbody></table>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p style="color: #fff;">Nenhum agendamento encontrado</p>';
                }
            } catch (erro) {
                loading.style.display = 'none';
                container.innerHTML = '<p style="color: #ff3333;">Erro ao carregar agendamentos</p>';
                console.error(erro);
            }
        }

        async function marcarRealizado(agendamentoId) {
            try {
                const formData = new FormData();
                formData.append('id', agendamentoId);

                const response = await fetch('api/admin_agendamentos.php?acao=realizado', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.sucesso) {
                    mostrarMensagem('Marcado como realizado!', 'sucesso');
                    carregarAgendamentos('confirmado');
                } else {
                    mostrarMensagem(data.mensagem, 'erro');
                }
            } catch (erro) {
                mostrarMensagem('Erro ao atualizar', 'erro');
            }
        }

        async function cancelarAgendamento(agendamentoId) {
            if (!confirm('Cancelar este agendamento?')) return;

            try {
                const formData = new FormData();
                formData.append('id', agendamentoId);

                const response = await fetch('api/admin_agendamentos.php?acao=cancelar', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.sucesso) {
                    mostrarMensagem('Agendamento cancelado!', 'sucesso');
                    carregarAgendamentos('confirmado');
                } else {
                    mostrarMensagem(data.mensagem, 'erro');
                }
            } catch (erro) {
                mostrarMensagem('Erro ao cancelar', 'erro');
            }
        }

        async function deletarAgendamento(agendamentoId) {
            if (!confirm('Deletar este agendamento?')) return;

            try {
                const formData = new FormData();
                formData.append('id', agendamentoId);

                const response = await fetch('api/admin_agendamentos.php?acao=deletar', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.sucesso) {
                    mostrarMensagem('Agendamento deletado!', 'sucesso');
                    carregarAgendamentos('todos');
                } else {
                    mostrarMensagem(data.mensagem, 'erro');
                }
            } catch (erro) {
                mostrarMensagem('Erro ao deletar', 'erro');
            }
        }

        function abrirAba(aba) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('ativo'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('ativo'));
            
            document.getElementById(aba).classList.add('ativo');
            event.target.classList.add('ativo');

            if (aba === 'aulas') {
                carregarAulas();
            } else if (aba === 'agendamentos') {
                carregarAgendamentos('confirmado');
            }
        }

        function filtrarAgendamentos(filtro) {
            document.querySelectorAll('.filtro-container button').forEach(btn => btn.classList.remove('ativo'));
            event.target.classList.add('ativo');
            carregarAgendamentos(filtro);
        }

        function mostrarMensagem(texto, tipo) {
            const msg = document.getElementById('mensagem');
            msg.textContent = texto;
            msg.className = 'mensagem ' + tipo;
            msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 3000);
        }

        function sair() {
            if (confirm('Deseja sair?')) {
                window.location.href = 'index.php';
            }
        }

        // Carregar aulas ao abrir
        carregarAulas();
    </script>
</body>
</html>
