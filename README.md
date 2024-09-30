# Diet App

## Descrição
O **Diet App** é uma aplicação voltada para ajudar usuários a gerenciar sua alimentação diária de maneira personalizada. A plataforma oferece ferramentas para calcular as necessidades calóricas, sugerir refeições baseadas em preferências e restrições alimentares, além de fornecer feedback para que os usuários possam atingir seus objetivos de saúde e bem-estar.

## Funcionalidades Principais

- **Cadastro de Usuário**:  
  Permite que os usuários se registrem e armazenem informações pessoais, como:
    - Idade
    - Peso
    - Altura
    - Objetivos (perder peso, manter peso, ganhar massa muscular)
    - Restrições alimentares (intolerâncias, alergias)

- **Cálculo de Necessidades Calóricas**:  
  O app utiliza fórmulas científicas, como a **Mifflin-St Jeor**, para calcular a quantidade de calorias que o usuário precisa diariamente, considerando o metabolismo basal e o nível de atividade física.

- **Sugestões de Refeições**:  
  O app sugere refeições saudáveis e adequadas com base nas preferências e restrições alimentares do usuário, ajudando a atingir os objetivos de saúde estabelecidos.

- **Registo de Alimentos**:  
  Os usuários podem registrar o que consumiram ao longo do dia, com o cálculo automático da ingestão calórica. Isso inclui:
    - Base de dados de alimentos com valores nutricionais
    - Rastreamento em tempo real das calorias ingeridas

- **Feedback e Relatórios**:  
  O app gera relatórios diários ou semanais com feedbacks sobre a ingestão calórica, comparando o consumo com as metas definidas. O feedback inclui:
    - Total de calorias ingeridas
    - Comparação com a meta calórica diária
    - Sugestões para melhorar os hábitos alimentares

## Tecnologias Utilizadas

- **Frontend**: React.js com Tailwind CSS
- **Backend**: Laravel (API RESTful)
- **Banco de Dados**: MySQL
- **Autenticação**: JWT (JSON Web Token)

## Como Executar o Projeto

### Pré-requisitos

Antes de começar, certifique-se de ter o [Node.js](https://nodejs.org/) e o [Composer](https://getcomposer.org/) instalados.

### Frontend (React.js)

1. Clone o repositório:
    ```bash
    git clone https://github.com/Eng-M10/diet-app.git
    cd diet-app/frontend
    ```

2. Instale as dependências:
    ```bash
    npm install
    ```

3. Execute o app:
    ```bash
    npm start
    ```

O frontend estará rodando em `http://localhost:3000`.

### Backend (Laravel)

1. Navegue para a pasta do backend:
    ```bash
    cd ../backend
    ```

2. Instale as dependências do Laravel:
    ```bash
    composer install
    ```

3. Configure o arquivo `.env` com as credenciais de banco de dados e JWT:

4. Execute as migrações do banco de dados:
    ```bash
    php artisan migrate
    ```

5. Inicie o servidor do Laravel:
    ```bash
    php artisan serve
    ```

O backend estará rodando em `http://localhost:8000`.

## Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para enviar pull requests ou relatar problemas na aba de [issues](https://github.com/seu-usuario/diet-app/issues).

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

