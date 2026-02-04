# 🚀 GUIA DE INSTALAÇÃO - Sistema de Spinning

Olá Matheus! Aqui está o guia completo para instalar o sistema de spinning no seu servidor.

---

## ⚙️ REQUISITOS

Antes de começar, certifique-se de ter:
- ✅ PHP 7.4 ou superior
- ✅ MySQL 5.7 ou superior
- ✅ Acesso ao painel de controle do seu servidor (cPanel, Plesk, etc)
- ✅ Acesso ao phpMyAdmin ou similar

---

## 📝 PASSO 1: PREPARAR O BANCO DE DADOS

### Opção A: Via phpMyAdmin (Mais Fácil)

1. **Abra o phpMyAdmin** do seu servidor
   - Geralmente em: `seu-dominio.com.br/phpmyadmin`
   - Faça login com suas credenciais

2. **Crie um novo banco de dados**
   - Clique em "Novo" ou "Create"
   - Nome do banco: `sparten_academia`
   - Collation: `utf8mb4_general_ci`
   - Clique em "Criar"

3. **Execute o script SQL**
   - Clique no banco `sparten_academia`
   - Vá para a aba "SQL"
   - Abra o arquivo `database.sql` (com um editor de texto)
   - Copie TODO o conteúdo
   - Cole no phpMyAdmin
   - Clique em "Executar"

### Opção B: Via Linha de Comando

```bash
# Conectar ao MySQL
mysql -u seu_usuario -p

# Criar banco de dados
CREATE DATABASE sparten_academia;

# Usar o banco
USE sparten_academia;

# Executar o script (dentro do MySQL)
source /caminho/para/database.sql;
```

---

## 🔧 PASSO 2: CONFIGURAR O ARQUIVO PHP

1. **Abra o arquivo `api/config.php`** com um editor de texto

2. **Localize estas linhas:**
```php
$host = 'localhost';
$usuario_db = 'root';
$senha_db = '';
$nome_db = 'sparten_academia';
```

3. **Substitua pelos seus dados:**
   - `$host`: Geralmente é `localhost` (deixe como está)
   - `$usuario_db`: Seu usuário MySQL (geralmente `root` em local, ou seu cPanel user em servidor)
   - `$senha_db`: Sua senha MySQL (deixe vazio `''` se não tiver)
   - `$nome_db`: Deixe como `sparten_academia`

**Exemplo Real:**
```php
$host = 'localhost';
$usuario_db = 'seu_cpanel_user';
$senha_db = 'sua_senha_mysql';
$nome_db = 'sparten_academia';
```

4. **Salve o arquivo**

---

## 📂 PASSO 3: SUBIR OS ARQUIVOS

### Opção A: Via FTP (Mais Comum)

1. **Abra um cliente FTP** (FileZilla, WinSCP, etc)

2. **Conecte ao seu servidor:**
   - Host: seu-dominio.com.br (ou IP do servidor)
   - Usuário: seu_usuario_ftp
   - Senha: sua_senha_ftp

3. **Navegue até a pasta public_html** (ou www)

4. **Crie uma pasta chamada `spinning`** (ou use a raiz se preferir)

5. **Copie TODOS estes arquivos para lá:**
   ```
   index.html
   cadastro.html
   login.html
   spinning.html
   agendar-teste.html
   dashboard.html
   admin.html
   database.sql
   README.md
   GUIA_INSTALACAO.md
   ```

6. **Copie as PASTAS:**
   ```
   style/ (com style.css e script.js)
   api/ (com todos os arquivos .php)
   images/ (com suas imagens)
   ```

### Opção B: Via cPanel File Manager

1. **Abra o File Manager** do cPanel
2. **Navegue até public_html**
3. **Clique em "Upload"**
4. **Selecione todos os arquivos e pastas**
5. **Clique em "Upload"**

---

## ✅ PASSO 4: TESTAR A INSTALAÇÃO

1. **Acesse o site principal:**
   ```
   https://seu-dominio.com.br/spinning/index.html
   ```

2. **Teste cada página:**
   - ✅ `index.html` - Site principal
   - ✅ `spinning.html` - Página de aulas
   - ✅ `cadastro.html` - Cadastro
   - ✅ `login.html` - Login
   - ✅ `agendar-teste.html` - Agendamento
   - ✅ `dashboard.html` - Dashboard
   - ✅ `admin.html` - Admin

3. **Se tudo funcionar, parabéns! 🎉**

---

## 🐛 SOLUÇÃO DE PROBLEMAS

### Erro: "Erro na conexão: Access denied for user"

**Solução:**
- Verifique o usuário e senha em `api/config.php`
- Certifique-se de que o banco `sparten_academia` foi criado
- Teste a conexão MySQL diretamente

### Erro: "Banco de dados não encontrado"

**Solução:**
- Verifique se o banco `sparten_academia` foi criado
- Execute o script `database.sql` novamente

### Erro: "Arquivo não encontrado (404)"

**Solução:**
- Verifique se todos os arquivos foram copiados
- Verifique o caminho das pastas (style/, api/, images/)
- Certifique-se de que as permissões estão corretas (755 para pastas, 644 para arquivos)

### Erro: "Permissão negada"

**Solução:**
- Via FTP: Clique direito na pasta → Propriedades → Permissões → 755
- Via cPanel: File Manager → Clique direito → Change Permissions → 755

---

## 🔐 PASSO 5: SEGURANÇA (IMPORTANTE!)

Depois de instalar, faça isso:

1. **Altere as permissões dos arquivos PHP:**
   ```
   api/ → 755 (pasta)
   *.php → 644 (arquivo)
   ```

2. **Proteja o arquivo config.php:**
   - Via .htaccess, adicione:
   ```apache
   <Files "config.php">
       Order Allow,Deny
       Deny from all
   </Files>
   ```

3. **Use HTTPS** (SSL):
   - Ative SSL no cPanel
   - Redirecione HTTP para HTTPS

---

## 📊 ESTRUTURA FINAL

Seu servidor deve ficar assim:

```
public_html/
├── spinning/
│   ├── index.html
│   ├── cadastro.html
│   ├── login.html
│   ├── spinning.html
│   ├── agendar-teste.html
│   ├── dashboard.html
│   ├── admin.html
│   ├── database.sql
│   ├── README.md
│   ├── GUIA_INSTALACAO.md
│   ├── style/
│   │   ├── style.css
│   │   └── script.js
│   ├── api/
│   │   ├── config.php
│   │   ├── cadastro.php
│   │   ├── login.php
│   │   ├── agendar_teste.php
│   │   ├── get_aulas.php
│   │   ├── inscrever.php
│   │   ├── cancelar_inscricao.php
│   │   ├── get_agendamentos.php
│   │   ├── cancelar_agendamento.php
│   │   ├── get_inscricoes.php
│   │   ├── admin_aulas.php
│   │   └── admin_agendamentos.php
│   └── images/
│       ├── PNGlogo-Photoroom.png
│       └── (suas outras imagens)
```

---

## 🎯 PRÓXIMOS PASSOS

Depois de instalar, você pode:

1. **Adicionar aulas** via `admin.html`
2. **Testar agendamento** em `agendar-teste.html`
3. **Criar usuário de teste** em `cadastro.html`
4. **Customizar cores e textos** conforme necessário

---

## 📞 DÚVIDAS?

Se tiver dúvidas durante a instalação:

1. Verifique o arquivo `README.md` para mais detalhes
2. Consulte a documentação do seu servidor
3. Verifique os logs de erro do PHP

---

## ✨ PRONTO!

Seu sistema de spinning está instalado e pronto para usar! 🚀

**Desenvolvido com ❤️ para Academia Sparten**
