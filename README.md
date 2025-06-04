# Projeto Dev Web: Sistema de Cadastro e Gerenciamento de Currículos em PHP

Este projeto foi desenvolvido para a matéria de **Desenvolvimento Web**, com o objetivo de implementar um sistema completo para cadastro e gerenciamento de currículos utilizando PHP "clássico" para o backend, com funcionalidades CRUD (Create, Read, Update, Delete) integradas a um banco de dados MySQL.

## Integrantes do Grupo

* Matheus Ribeiro Gomes dos Santos - RA: 1648262
* Flávia Aragão Lopes - RA: 2163501
* Victor Hiromi Maisaka - RA: 2322323
* Caio Marques de Souza - RA: 1303757
* João Vitor Piola - RA: 2472597

## Funcionalidades Principais

O sistema permite que usuários se registrem, façam login e gerenciem seus próprios currículos. Administradores possuem um painel com visão geral e controle sobre todos os currículos e usuários cadastrados.

* **Autenticação de Usuários:**
    * Registro de novos usuários com validação de dados e hash de senha.
    * Login seguro utilizando sessões PHP.
    * Logout.
* **Gerenciamento de Currículo pelo Usuário:**
    * Criação de um currículo por usuário, com campos para:
        * Dados Pessoais (Nome, CPF, Email, Telefone, Data de Nascimento, Gênero, etc.).
        * Formação Acadêmica (múltiplas entradas: curso, instituição, período).
        * Experiência Profissional (múltiplas entradas: empresa, cargo, tempo, atividades).
        * Habilidades (múltiplas).
        * Idiomas (múltiplos, com nível).
        * Portfólio (LinkedIn, GitHub, Site Pessoal).
        * Upload de Foto.
    * Visualização do currículo cadastrado.
    * Edição completa do currículo, incluindo todas as seções e a foto.
    * Exclusão do próprio currículo.
* **Painel Administrativo:**
    * Acesso restrito para usuários com perfil de 'admin'.
    * Dashboard com listagem de todos os currículos cadastrados.
    * Visualização detalhada de qualquer currículo.
    * Exclusão de currículos por administradores.
    * Listagem de todos os usuários do sistema.
    * Possibilidade de alterar o papel (role) de um usuário (user/admin).
    * Deleção de usuários (que também remove o currículo associado).
    * Visualização de estatísticas básicas (total de usuários, total de currículos, etc.).
* **Segurança:**
    * Prevenção contra SQL Injection (uso de Prepared Statements com PDO/MySQLi).
    * Sanitização de output nos dados exibidos (uso de `htmlspecialchars`).
    * Validação de dados no lado do servidor para formulários.
    * Proteção da pasta de uploads (`uploads/.htaccess`) para evitar execução de scripts e permitir acesso apenas a imagens.
    * Hash seguro de senhas utilizando `password_hash()` e `password_verify()`.
    * Controle de acesso baseado em sessões PHP e papéis (roles) de usuário.

## Tecnologias Utilizadas

* **Backend:** PHP (Clássico, com estrutura inspirada em MVC)
* **Frontend:** HTML5, CSS3, JavaScript (com jQuery para manipulação do DOM e funcionalidades dinâmicas nos formulários)
* **Banco de Dados:** MySQL / MariaDB
* **Servidor Web (Ambiente de Desenvolvimento):** XAMPP (Apache)

## Estrutura do Projeto

O projeto está organizado da seguinte forma:

* `index.php`: Front Controller, ponto de entrada único da aplicação, responsável pelo roteamento das requisições.
* `controllers/`: Camada de controle, responsável pela lógica de negócio e por intermediar as interações entre os Models e as Views.
    * `AuthController.php`: Gerencia autenticação, registro, login, logout e sessões.
    * `UserController.php`: Gerencia as ações do usuário logado relacionadas ao seu currículo (CRUD).
    * `AdminController.php`: Gerencia as ações do painel administrativo (visualização e gerenciamento de currículos e usuários).
* `models/`: Camada de modelo, responsável pela interação com o banco de dados e pela lógica de dados.
    * `User.php`: Operações CRUD e lógica para usuários (autenticação, etc.).
    * `Curriculo.php`: Operações CRUD e lógica para currículos.
* `views/`: Camada de visualização, contém os arquivos HTML (com PHP embutido) para a interface do usuário.
    * `auth/`: Telas de login e registro.
    * `user/`: Telas do dashboard do usuário, formulários de criação e edição de currículo.
    * `admin/`: Telas do painel administrativo, visualização de currículos e usuários.
* `includes/`: Arquivos auxiliares e de configuração.
    * `db.php`: Configuração da conexão com o banco de dados.
* `database/`: Contém o script SQL para a criação da estrutura do banco de dados.
    * `create_tables.sql`: Schema do banco de dados.
* `CSS/`: Arquivos de folha de estilo (CSS).
    * `style.css`: Estilização principal da aplicação.
* `JS/`: Arquivos JavaScript.
    * `script.js`: Funcionalidades dinâmicas do frontend (máscaras, adição de campos, validações client-side).
* `uploads/`: Diretório para armazenamento das fotos de perfil enviadas pelos usuários.
    * `.htaccess`: Arquivo de configuração para proteger a pasta de uploads.
* `scripts/` (se houver): Scripts utilitários, como `create_admin_user.php`.
* `.htaccess` (na raiz): Arquivo de configuração do Apache (se utilizado para reescrita de URL ou outras diretivas globais).

## Configuração e Instalação

1.  **Pré-requisitos:**
    * Servidor web local que suporte PHP e MySQL (ex: XAMPP, WAMP, MAMP, Laragon).
    * Um navegador web.

2.  **Passos para Instalação:**
    * Clone o repositório ou baixe os arquivos do projeto.
    * Coloque a pasta do projeto no diretório raiz do seu servidor web (ex: `htdocs` no XAMPP).
    * Acesse o phpMyAdmin (ou outro cliente MySQL) e crie um banco de dados chamado `cadastro_curriculo` (ou o nome definido em `includes/db.php`).
    * Importe o arquivo `database/create_tables.sql` para o banco de dados recém-criado. Isso criará as tabelas `users` e `curriculos`.
    * Verifique e, se necessário, ajuste as credenciais de acesso ao banco de dados no arquivo `includes/db.php` (host, nome do banco, usuário, senha) para que correspondam à sua configuração local.
    * Certifique-se de que a pasta `uploads/` exista na raiz do projeto e que o servidor web tenha permissão de escrita nela.
    * Acesse o projeto através do seu navegador (ex: `http://localhost/nome_da_pasta_do_projeto/`).

3.  **Criando um Usuário Administrador:**
    * Registre um novo usuário através da interface de registro do sistema.
    * Após o registro, acesse o banco de dados (ex: via phpMyAdmin), localize o usuário recém-criado na tabela `users` e altere o valor da coluna `role` de 'user' para 'admin'.
    * (Alternativamente, se o script `scripts/create_admin_user.php` estiver configurado, ele pode ser executado via linha de comando ou acessado diretamente (com as devidas precauções de segurança) para criar um usuário admin).

## Considerações Finais

Este projeto visa aplicar os conhecimentos de desenvolvimento web com PHP para criar uma aplicação funcional e bem estruturada, cobrindo desde a interação com o banco de dados até a implementação de funcionalidades de usuário e administrativas.
