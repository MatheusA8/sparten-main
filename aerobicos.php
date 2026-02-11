<?php
session_start();
$tipo = $_GET['tipo'] ?? 'normal';
$isAvulsa = ($tipo === 'avulsa');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>STEP DANCE - Sparten</title>
    <style>
        .spinning-section {
            min-height: 100vh;
            padding: 100px 20px 50px;
        }

        .spinning-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .spinning-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .spinning-header h1 {
            font-size: 3rem;
            color: #ff0000;
            margin: 0 0 10px;
        }

        .spinning-header p {
            color: #fff;
            font-size: 1.2rem;
            opacity: 0.8;
            margin: 0;
        }

        .spinning-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .aula-card {
            background: rgba(255, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid #ff0000;
            border-radius: 15px;
            padding: 30px;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .aula-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(255, 0, 0, 0.3);
        }

        .aula-card h3 {
            font-size: 1.8rem;
            color: #ff0000;
            margin: 0 0 15px;
            text-transform: uppercase;
        }

        .aula-info {
            flex-grow: 1;
        }

        .aula-info p {
            color: #fff;
            margin: 10px 0;
            opacity: 0.9;
        }

        .aula-info strong {
            color: #ff0000;
        }

        .nivel-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .nivel-iniciante {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .nivel-intermediario {
            background: rgba(255, 152, 0, 0.2);
            color: #ff9800;
            border: 1px solid #ff9800;
        }

        .nivel-avancado {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
            border: 1px solid #f44336;
        }

        .vagas-info {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
        }

        .vagas-info p {
            margin: 5px 0;
            font-size: 0.9rem;
        }

        .vagas-numero {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0f0;
        }

        .btn-inscrever {
            background: #ff0000;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }

        .btn-inscrever:hover {
            background: #ff3333;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
            transform: translateY(-2px);
        }

        .btn-inscrever:disabled {
            background: #666;
            cursor: not-allowed;
            opacity: 0.5;
        }


        .cta-section h2 {
            font-size: 2rem;
            color: #ff0000;
            margin: 0 0 20px;
        }

        .cta-section p {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn-agendar-teste {
            background: #0f0;
            color: #000;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-agendar-teste:hover {
            background: #0f0;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
        }

        .loading {
            text-align: center;
            color: #ff0000;
            padding: 40px 20px;
            font-size: 1.2rem;
        }

        .erro {
            text-align: center;
            color: #ff3333;
            padding: 40px 20px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .spinning-header h1 {
                font-size: 2rem;
            }

            .spinning-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cta-section {
                padding: 30px 20px;
            }

            .cta-section h2 {
                font-size: 1.5rem;
            }
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

    display: block;
    margin: 60px auto 0 auto; /* empurra pra baixo e centraliza */
    text-align: center;
}

.btn-sair:hover {
    background: #ff3333;
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
}


        .codigo-resultado {
            background: rgba(0, 255, 0, 0.1);
            border: 2px solid #0f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            display: none;
            margin-top: 20px;
        }

        .codigo-resultado h2 {
            color: #0f0;
            margin-bottom: 10px;
        }

        .codigo-resultado .codigo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0f0;
            font-family: monospace;
            padding: 10px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            margin: 10px 0;
        }

        .codigo-resultado p {
            color: #fff;
            margin: 10px 0;
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
            <li><a href="#inicio">INÍCIO</a></li>
            <li><a href="#sobre">SOBRE</a></li>
            <li><a href="#planos">PLANOS E VALORES</a></li>
            <li><a href="#estrutura">ESTRUTURA</a></li>
            <li><a href="#servicos">SERVIÇOS</a></li>
            <li><a href="#equipe">EQUIPE</a></li>
            <li><a href="#clientes">CLIENTES</a></li>
            <li><a href="#localizacao">LOCALIZAÇÃO</a></li>
            <li><a href="#horarios">HORÁRIOS</a></li>
            <li><a href="#contatos">CONTATOS</a></li>
    
            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
                <li><a href="admin.php" class="dashboard-btn">PAINEL ADMIN</a></li>
            <?php else: ?>
                <li><a href="dashboard.php" class="dashboard-btn">DASHBOARD</a></li>
            <?php endif; ?>
            
            <li>
                <a href="api/logout.php" 
                   class="logout-btn" 
                   onclick="return confirm('Tem certeza que deseja Deslogar?')">
                   Deslogar
                </a>
            </li>
        </ul>
    </header>


<section class="spinning-section">
    <div class="spinning-container">
        <div class="spinning-header">
            <h1><?= $isAvulsa ? 'Aula Avulsa - STEP DANCE' : 'STEP DANCE' ?></h1>
            <p>Queime calorias e melhore sua resistência com nossas aulas aeróbicas.</p>
        </div>

        <div id="aulasContainer" class="spinning-grid">
            <div class="loading">Carregando aulas...</div>
        </div>

        <?php if ($isAvulsa): ?>

            <div class="codigo-resultado" id="codigoResultado">
                <h2>✓ Agendamento Confirmado!</h2>
                <p>Seu código de agendamento:</p>
                <div class="codigo" id="codigoTexto">SPIN-XXXXX</div>
                <p>Guarde este código! Você precisará dele na academia.</p>
                <button class="btn-copiar" onclick="copiarCodigo()">COPIAR CÓDIGO</button>
                <p style="margin-top: 15px; font-size: 0.9rem;">Você receberá um email com os detalhes do agendamento.</p>
                <a href="index.php" class="btn-voltar">← Voltar ao início</a>
            </div>

        <?php else: ?>

            <!-- SE REMOVER O SITE NAO FUNCINA -->

        <?php endif; ?>
    </div>
</section>



    <button class="btn-sair" onclick="window.location.href='index.php'">
    SAIR
</button>

<script src="script.js" defer></script>
<script>
    const isAvulsa = new URLSearchParams(window.location.search).get('tipo') === 'avulsa';

    async function carregarAulas() {
        const container = document.getElementById('aulasContainer');

        try {
            const response = await fetch('api/get_aulas.php?modalidade=aerobicos');
            const data = await response.json();

            if (data.sucesso && data.dados.length > 0) {
                let html = '';

                data.dados.forEach(aula => {
                    const nivelClass = 'nivel-' + aula.nivel.toLowerCase().replace('á', 'a');
                    const vagasDisponiveis = aula.vagas_disponiveis > 0;

                    html += `
                        <div class="aula-card">
                            <h3>${aula.nome}</h3>
                            <div class="aula-info">
                                <p><strong>Instrutor:</strong> ${aula.instrutor}</p>
                                <p><strong>Horário:</strong> ${aula.horario}</p>
                                <p><strong>Descrição:</strong> ${aula.descricao}</p>

                                <span class="nivel-badge ${nivelClass}">${aula.nivel}</span>

                                <div class="vagas-info">
                                    <p>Vagas disponíveis:</p>
                                    <p class="vagas-numero">${aula.vagas_disponiveis}/${aula.capacidade}</p>
                                </div>
                            </div>

                            ${vagasDisponiveis ? `
                                <button class="btn-inscrever"
                                    onclick="${isAvulsa
                                        ? `agendarAvulsa(${aula.id}, '${aula.nome}')`
                                        : `inscreverAula(${aula.id}, '${aula.nome}')`}">
                                    ${isAvulsa ? 'AGENDAR AULA AVULSA' : 'INSCREVER-SE'}
                                </button>
                            ` : `
                                <button class="btn-inscrever" disabled>AULA CHEIA</button>
                            `}
                        </div>
                    `;
                });

                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="erro">Nenhuma aula disponível no momento</div>';
            }
        } catch (erro) {
            container.innerHTML = '<div class="erro">Erro ao carregar aulas.</div>';
            console.error(erro);
        }
    }

    async function agendarAvulsa(aulaId, nomeAula) {
        if (!confirm(`Confirmar aula avulsa em "${nomeAula}"?`)) return;

        try {
            const formData = new FormData();
            formData.append('aula_id', aulaId);

            const response = await fetch('api/agendar_teste.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.sucesso) {
                document.getElementById('codigoTexto').innerText = data.dados.codigo_unico;
                document.getElementById('codigoResultado').style.display = 'block';
                document.getElementById('aulasContainer').style.display = 'none';
            } else {
                alert(data.mensagem || 'Erro ao agendar aula avulsa');
            }
        } catch (erro) {
            alert('Erro ao agendar aula avulsa');
            console.error(erro);
        }
    }

    async function inscreverAula(aulaId, nomeAula) {
        if (!confirm(`Deseja se inscrever em "${nomeAula}"?`)) return;

        try {
            const formData = new FormData();
            formData.append('aula_id', aulaId);

            const response = await fetch('api/inscrever.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.sucesso) {
                alert('Inscrição realizada com sucesso!');
                carregarAulas();
            } else {
                alert(data.mensagem || 'Erro ao inscrever');
            }
        } catch (erro) {
            alert('Erro ao inscrever');
            console.error(erro);
        }
    }

    carregarAulas();
</script>

</body>
</html>
