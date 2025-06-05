# Projeto Dev Web: Sistema de Cadastro e Gerenciamento de Currículos em PHP

Este projeto foi desenvolvido para a disciplina de **Desenvolvimento Web**, com o objetivo de implementar um sistema completo para cadastro e gerenciamento de currículos utilizando PHP "clássico" para o backend, com funcionalidades CRUD (Create, Read, Update, Delete) integradas a um banco de dados MySQL via PDO.

## Integrantes do Grupo

* Matheus Ribeiro Gomes dos Santos - RA: 1648262
* Flávia Aragão Lopes - RA: 2163501
* Victor Hiromi Maisaka - RA: 2322323
* Caio Marques de Souza - RA: 1303757
* João Vitor Piola - RA: 2472597

## Funcionalidades Implementadas

O sistema permite que usuários se registrem, façam login e gerenciem seus próprios currículos. Administradores possuem um painel com visão geral e controle sobre todos os currículos e usuários cadastrados.

* **Autenticação de Usuários:**
    * Registro de novos usuários com validação de dados (nome, email, senha, confirmação de senha).
    * Verificação de existência de email.
    * Hash de senhas utilizando `password_hash()`.
    * Login seguro utilizando sessões PHP e verificação com `password_verify()`.
    * Logout de usuários.
    * Controle de acesso para páginas restritas (`requireLogin()` e `requireAdmin()`).
* **Gerenciamento de Currículo pelo Usuário:**
    * Limitação de um currículo por usuário.
    * **Criação de Currículo:** Formulário completo com seções para:
        * Dados Pessoais (Nome, CPF, Email, Telefone com DDI/DDD, Data de Nascimento, Gênero, Estado Civil, Nacionalidade, Cidade, Estado).
        * Formação Acadêmica (múltiplas entradas dinâmicas: curso, instituição, ano de início, ano de conclusão).
        * Experiência Profissional (múltiplas entradas dinâmicas: empresa, cargo, tempo, atividades; seção opcional).
        * Resumo Geral da Experiência (campo `textarea` separado).
        * Habilidades (múltiplas, adicionadas como tags dinâmicas).
        * Idiomas (múltiplos, com nível; adicionados dinamicamente).
        * Portfólio (LinkedIn, GitHub, Site Pessoal).
        * Upload de Foto (com validação de tipo e tamanho, e geração de nome único).
    * **Visualização do Currículo:** Dashboard do usuário exibe uma pré-visualização dos dados do currículo.
    * **Edição de Currículo:** Formulário pré-preenchido com todos os dados existentes, permitindo a alteração de qualquer informação, incluindo a substituição da foto (com exclusão da antiga).
    * **Exclusão do Próprio Currículo:** Com confirmação e remoção da foto associada do servidor.
* **Painel Administrativo:**
    * Acesso restrito para usuários com `role` de 'admin'.
    * **Dashboard Admin:** Listagem de todos os currículos cadastrados no sistema com informações resumidas (nome, email, foto, resumo de formação/experiência, data de criação) e ações (Ver Completo, Excluir).
    * **Visualização Detalhada de Currículo:** Admin pode visualizar todos os detalhes de qualquer currículo, incluindo todas as formações, experiências, habilidades, idiomas, portfólio e foto.
    * **Exclusão de Currículos por Administradores:** Com confirmação e remoção da foto.
    * **Gerenciamento de Usuários:**
        * Listagem de todos os usuários do sistema.
        * Exclusão de usuários (que também remove o currículo e foto associados).
        * Alteração do papel (role) de um usuário (user/admin).
    * **Estatísticas (Básico):** Visualização de total de usuários, total de currículos, e contagem por mês.
* **Segurança:**
    * Prevenção contra SQL Injection utilizando Prepared Statements (PDO).
    * Sanitização de output nos dados exibidos (uso de `htmlspecialchars()`).
    * Validação de dados no lado do servidor para formulários.
    * Proteção da pasta de uploads (`uploads/.htaccess`) para restringir acesso e desabilitar execução de scripts.
    * Controle de acesso baseado em sessões PHP e papéis (roles) de usuário.
* **Frontend:**
    * Formulários interativos com adição/remoção dinâmica de campos (Formação, Experiência, Idiomas, Habilidades).
    * Máscaras de input para CPF, DDD, Telefone.
    * Validações client-side básicas (campos obrigatórios, formato de CPF, idade mínima).

## Tecnologias Utilizadas

* **Backend:** PHP (Clássico, com estrutura MVC-like e conexão de banco de dados via PDO)
* **Frontend:** HTML5, CSS3 (com arquivos separados para melhor organização), JavaScript (com jQuery)
* **Banco de Dados:** MySQL / MariaDB
* **Servidor Web (Ambiente de Desenvolvimento):** XAMPP (Apache)

## Estrutura do Projeto

O projeto está organizado da seguinte forma:

* `index.php`: Front Controller, ponto de entrada único da aplicação, responsável pelo roteamento.
* `controllers/`: Contém a lógica de negócio.
    * `AuthController.php`: Autenticação, registro, login, logout, sessões.
    * `UserController.php`: Ações do usuário logado (CRUD de currículo).
    * `AdminController.php`: Ações do painel administrativo.
* `models/`: Interação com o banco de dados.
    * `User.php`: CRUD e lógica para usuários.
    * `Curriculo.php`: CRUD e lógica para currículos.
* `views/`: Arquivos de apresentação (HTML com PHP).
    * `auth/`: Telas de login e registro.
    * `user/`: Dashboard do usuário, formulários de criação/edição de currículo.
    * `admin/`: Dashboard administrativo, visualização de currículos, lista de usuários, estatísticas.
    * `partials/`: Contém `header.php` e `footer.php` para partes reutilizáveis do HTML (links CSS, scripts JS).
* `includes/`: Arquivos de configuração.
    * `db.php`: Configuração da conexão PDO com o banco de dados e configurações de erro.
* `database/`: Scripts SQL.
    * `create_tables.sql`: Schema completo do banco (`users`, `curriculos` com `id_usuario` e `resumo_experiencia`).
* `CSS/`: Arquivos de folha de estilo.
    * `base.css`, `forms.css`, `auth.css`, `dashboard_user.css`, `dashboard_admin.css` (e `style.css` principal).
* `JS/`:
    * `script.js`: Funcionalidades dinâmicas do frontend.
* `uploads/`: Diretório para armazenamento das fotos (protegido por `.htaccess`).
* `scripts/`:
    * `create_admin_user.php`: Script para criar um usuário administrador inicial.
* `.gitignore`: Especifica arquivos e pastas a serem ignorados pelo Git.
* `.htaccess` (na raiz): Configuração do Apache para URLs amigáveis (redireciona para `index.php`).
* `LICENSE`: Arquivo de licença do projeto (MIT).
* `README.md`: Este arquivo.

## Configuração e Instalação

1.  **Pré-requisitos:**
    * Servidor web local com PHP (v7.4+ recomendado para algumas funcionalidades de tipagem, embora o código atual deva ser compatível com versões um pouco anteriores) e MySQL/MariaDB (ex: XAMPP, WAMP, MAMP, Laragon).
    * Navegador web.

2.  **Passos para Instalação:**
    * Clone o repositório ou baixe e extraia os arquivos do projeto para a pasta `htdocs` (ou equivalente) do seu servidor local.
    * Crie a pasta `logs/` na raiz do projeto (ao lado da pasta `includes/`) e certifique-se de que o servidor web tenha permissão de escrita nela (para o `error_log` configurado no `db.php`).
    * Crie a pasta `uploads/` na raiz do projeto e certifique-se de que o servidor web tenha permissão de escrita nela. Coloque o arquivo `.htaccess` (fornecido) dentro da pasta `uploads/`.
    * No seu cliente MySQL (ex: phpMyAdmin), crie um banco de dados chamado `cadastro_curriculo` (ou o nome definido em `includes/db.php`).
    * Importe o arquivo `database/create_tables.sql` para o banco de dados criado. Isso criará as tabelas `users` e `curriculos` com a estrutura correta.
    * Verifique as credenciais de acesso ao banco de dados no arquivo `includes/db.php` (host, nome do banco, usuário, senha). Por padrão no XAMPP, o usuário é `root` e a senha é vazia (mas você solicitou à IA para usar 'admin' como senha em uma etapa, então ajuste conforme sua configuração).
    * Acesse o projeto através do seu navegador (ex: `http://localhost/nome_da_pasta_do_projeto/`).

3.  **Criando um Usuário Administrador:**
    * Você pode usar o script `scripts/create_admin_user.php` via linha de comando (CLI) para criar um usuário administrador inicial:
      Navegue até a pasta `scripts/` no terminal e execute: `php create_admin_user.php`
      (As credenciais padrão no script são `admin@example.com` / `admin123`. Altere no script para uma senha segura).
    * Alternativamente, registre um novo usuário normalmente e depois altere manualmente o valor da coluna `role` para 'admin' na tabela `users` do banco de dados.

## Considerações Finais

Este projeto demonstra a aplicação de conceitos de desenvolvimento web com PHP para criar uma aplicação funcional, desde a modelagem do banco de dados e lógica de backend até a interface do usuário e interações frontend.
