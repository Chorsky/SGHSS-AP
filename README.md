🏥 SGHSS-VidaPlus API - Sistema de Gestão Hospitalar e de Serviços de Saúde

📄 Introdução

Este repositório contém o código-fonte do protótipo da API RESTful do Sistema de Gestão Hospitalar e de Serviços de Saúde (SGHSS) para o estudo de caso VidaPlus, desenvolvido como projeto final da disciplina de Projetos Multidisciplinares.

O objetivo da API é fornecer uma camada de serviço segura e eficiente para o gerenciamento de pacientes e autenticação de usuários (Administradores e Profissionais de Saúde), seguindo os princípios do desenvolvimento framework-less em PHP Orientado a Objetos (POO).

🛠️ Stack Tecnológica

    Linguagem: PHP 8.2+

    Banco de Dados: MySQL/MariaDB

    Servidor Web: Apache (necessário mod_rewrite ativo)

    Padrão de Código: PHP Puro, Sem Frameworks (MVC Simplificado)

    Segurança: PDO (Prepared Statements), Criptografia de Senhas (password_hash).

⚙️ Configuração e Instalação

Siga os passos abaixo para configurar o ambiente localmente.

1. Requisitos Prévios

    PHP 8.2+ instalado e configurado.

    Servidor MySQL/MariaDB ativo (XAMPP, WAMP, Docker, etc.).

    Apache com módulo mod_rewrite habilitado.

2. Clonar o Repositório

Bash

git clone [INSIRA O LINK DO SEU REPOSITÓRIO AQUI]
cd SGHSS-API

3. Configuração do Banco de Dados

    Crie um banco de dados chamado vidaplus_db.

    Importe o script SQL inicial (localizado na raiz do repositório) para criar as tabelas users e patients.
    SQL

    -- Exemplo de criação de usuário inicial (para testes)
    INSERT INTO users (name, email, password_hash, role) 
    VALUES ('Administrador Teste', 'admin@vidaplus.com', '$2y$10$YourHashHere...', 'admin');
    -- A senha do usuário inicial é '123456' (Use o hash correto!)

    Ajuste a Conexão: Edite o arquivo /config/database.php com suas credenciais de acesso ao banco de dados ($user e $pass).

4. Configuração do Servidor Web (URLs Amigáveis)

Para que o roteamento (index.php) funcione corretamente, o arquivo /public/.htaccess deve estar configurado:

    Se estiver em um subdiretório (ex: http://localhost/projeto/public/), ajuste a linha: RewriteBase /projeto/public/

🧭 Arquitetura e Roteamento

A API segue o padrão MVC (Model-View-Controller) simplificado, com um único ponto de entrada (/public/index.php) que utiliza a classe Router.php para direcionar a requisição ao Controller correto.

📁 Estrutura do Código

    /config: Contém as configurações críticas (ex: conexão DB).

    /src/Models: Lógica de acesso e persistência de dados (CRUD).

    /src/Controllers: Lógica de negócios e gerenciamento da requisição/resposta HTTP.

    /src/Services: Componentes de infraestrutura (ex: Router.php).

    /public: Ponto de acesso público, contém o front controller (index.php) e o .htaccess.

🧪 Roteiro de Testes da API (Endpoints)

Utilize ferramentas como Postman ou Insomnia para realizar os testes.

1. Teste de Autenticação (Setup)

Método	Endpoint	Objetivo
POST	/api/auth/login	Obter o token de acesso

Payload Exemplo (JSON Body):
JSON

{
  "email": "admin@vidaplus.com",
  "password": "123456" 
}

Resultado: Status 200 OK e o valor do token (ex: "sghss_token_XYZ..."). Guarde este token para os próximos testes, inserindo-o no Header: Authorization: Bearer [TOKEN_AQUI].

2. Gestão de Pacientes (CRUD)

Método	Endpoint	Ação	Status Esperado	Notas
POST	/api/patients	Cadastra um novo paciente	201 Created	Enviar dados de paciente no Body (JSON).
GET	/api/patients	Lista todos os pacientes	200 OK	Deve estar autenticado.
GET	/api/patients/1	Consulta paciente por ID	200 OK	Substituir 1 pelo ID real.
PUT	/api/patients/1	Atualiza paciente por ID	200 OK	Enviar Body JSON completo com dados atualizados.
DELETE	/api/patients/1	Exclui paciente por ID	204 No Content	Sucesso na exclusão.
GET	/api/patients/999	Consulta ID inexistente	404 Not Found	Teste de erro.

3. Teste de Segurança (Não Funcional)

Método	Endpoint	Objetivo	Status Esperado	Observação
GET	/api/patients	Acesso sem o Header Authorization	401 Unauthorized	Valida o Controle de Acesso (RF02).
POST	/api/patients	Enviar dados inválidos	400 Bad Request	Valida a manipulação de entrada de dados.
