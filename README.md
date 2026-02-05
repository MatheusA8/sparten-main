# 🏋️ Sparten — Sistema Web da Academia

Projeto web desenvolvido para a **Sparten**, unindo site institucional moderno com **sistema de login**, **dashboard de usuários** e **painel administrativo protegido**.

O foco do projeto é entregar uma experiência visual forte, simples de navegar e com separação clara entre **usuário comum** e **administrador**.

---

## 🚀 Funcionalidades

### 🔐 Autenticação
- Login seguro com PHP + MySQL
- Senhas criptografadas
- Sessões protegidas
- Redirecionamento automático por tipo de usuário

### 👤 Usuário comum
- Acesso ao **Dashboard**
- Área protegida por sessão
- Sem acesso a rotas administrativas

### 🛠️ Administrador
- Acesso exclusivo ao **Painel Admin**
- Proteção contra acesso direto por URL
- Botão de admin visível apenas para admins

### 🌐 Site institucional
- Página inicial moderna
- Seções informativas (estrutura, equipe, planos, localização etc.)
- Layout pensado para impacto visual e clareza

---

## 🧠 Tecnologias Utilizadas

- **HTML5**
- **CSS3**
- **JavaScript (Fetch API)**
- **PHP (procedural)**
- **MySQL / MariaDB**
- **XAMPP (ambiente local)**

---

## 📂 Estrutura do Projeto

```text
sparten-main/
├── api/
│   ├── login.php
│   └── logout.php
├── style/
│   ├── style.css
│   └── script.js
├── images/
├── index.html
├── login.html
├── cadastro.html
├── dashboard.php
├── admin.php
├── config.php
└── README.md
