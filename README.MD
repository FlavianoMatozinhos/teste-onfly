# Gestão de Usuários e Despesas

## DESCRIÇÃO
- Este sistema foi desenvolvido para gerenciar usuários e despesas de forma eficiente e organizada. 
- Permite registrar, visualizar, atualizar e excluir informações de usuários, além de controlar o registro de despesas com descrição, valor e data específica.
- Utiliza Laravel no backend para a lógica de negócios e Vue.js no frontend para uma interface interativa e responsiva.

## INSTALAÇÃO

### Pré-requisitos
Certifique-se de ter as seguintes ferramentas instaladas em seu sistema:
- PHP ^8
- Composer
- Node.js e npm (para projetos Vue.js, se aplicável)

### Passo a Passo

1. **Clone o Repositório**
- git clone https://github.com/seu-usuario/teste-onfly.git
- cd teste-onfly


2. **Configuração do Ambiente**
- Copie o arquivo `.env.example` para `.env`:
  ```
  cp .env.example .env
  ```

- Configure seu arquivo `.env` com as informações necessárias, incluindo configurações de banco de dados
- ![banco](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/bbe72092-e6d3-41f4-84f0-a63e096ac51c)

- E configurações de e-mail.
- ![email](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/3ec550d0-f5a6-46b7-9e96-58c0677cf651)


3. **Instalação das Dependências**
    ```
        - composer install (No arquivo BackEnd - api)
    ```

    ```
        - npm install (No arquivo FrontEnd - client)
    ```

    ```
        - npm install axios (No arquivo FrontEnd - client)
    ```


4. **Geração da Chave de Aplicação**
    ```
        - php artisan key:generate - (No arquivo BackEnd - api)
    ```

5. **Migração do Banco de Dados**
    ```
        - php artisan migrate - (No arquivo BackEnd - api)
    ```

6. **Instalação do Passport**
    ```
        - composer require laravel/passport - (No arquivo BackEnd - api)
    ```

7. **Instalação do Passport pt2**
    ```
    - php artisan passport:install
    ```
OBS(Irá aparecer a opção de criar migrations, digitar YES /  Irá aparecer a opção de Criar KEYS para o Client, digitar YES) - (No arquivo BackEnd - api)


8. **Instalação do Sanctum**
    ```
        - composer require laravel/sanctum - (No arquivo BackEnd - api)
    ```


9. **Instalação do Swagger**
    ```
        - composer require darkaonline/l5-swagger - (No arquivo BackEnd - api)
    ```
    ```
         - php artisan l5-swagger:generate - (No arquivo BackEnd - api)
    ```


10. **Execução do Worker de Filas**
    ```
        - php artisan queue:work --queue=default - (No arquivo BackEnd - api)
    ```

11. **Compilação dos Assets (para projetos Vue.js)**
    ```
        - npm run serve ou npm run dev - (No arquivo FrontEnd - client)
    ```

## TESTES UNITARIOS DO BACKEND (arquivo api)
- Para que os testes ocorram de forma correta

1. Precisa ter os arquivos .env.testing.
    ```
        APP_ENV=testing
        APP_KEY=base64:
        DB_CONNECTION=sqlite
        DB_DATABASE=:memory:
        CACHE_DRIVER=array
        SESSION_DRIVER=array
    ```

2. É preciso criar uma chave para o env de teste.
    ```
        php artisan key:generate --env=testing
    ```

3. Ajustar o arquivo phpunit.xml para que os testes ocorram em um banco de dados "ficticio".
    ```
        <php>
            <env name="APP_ENV" value="testing"/>
            <env name="BCRYPT_ROUNDS" value="4"/>
            <env name="CACHE_DRIVER" value="array"/>
            <env name="DB_CONNECTION" value="sqlite"/>
            <env name="DB_DATABASE" value=":memory:"/>
            <env name="MAIL_MAILER" value="array"/>
            <env name="QUEUE_CONNECTION" value="sync"/>
            <env name="SESSION_DRIVER" value="array"/>
            <env name="TELESCOPE_ENABLED" value="false"/>
        </php>
    ```
4. Criar as tabelas do banco de dados.
    ```
        php artisan migrate --env=testing  
    ```

5. Agora é só rodar os teste
    ```
        php artisan test 
    ```

## USO
- Acessando a tela principal, você será redirecionado para a tela de Login, basta informar email e senha.
![login](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/19f90a0c-3d69-431a-bb1c-fcf5d4aeb585)


- Caso não tenha usuario cadastro, ir através da barra de navegação e clicar em Register, e assim que criar o usuario, será redirecionado para a tela de login.
![register](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/1e78b24b-ce6e-4ca6-ac86-41c8a711b6e2)


- Após feito login, será redirecionado para a pagina de Home, que só é acessivel através do Login.
![home s-despesa](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/7482560b-0f42-4347-a3b8-8ab48c012168)


- Na tela de home você terá acesso ao gerenciador dos usuarios e cadastrar alguma despesa.
![despesa-usuarios](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/23bbfc75-f8da-4ca2-af69-bb07de802abf)

- Acessando a guia de Gerenciar Usuarios, você terá acesso aos usuarios cadastrados ao seu sistema, sendo possivel criar, editar e excluir um usuario.
![usuario-1](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/42c750e7-f840-4bf3-9edd-b07d858a65f6)


- Clicando em Editar um usuario, você terá acesso a um formulario de edição.
![edit-usuario-2](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/97f90c06-0b93-4ae4-a76f-f03f06601f11)


- Clicando em Excluir um usuario, aparecerá um alerta perguntando se deseja mesmo fazer essa exclusão.
![excluir-usuario-3](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/70b86318-acdb-4104-863a-98d68f11f1b4)


- Para voltar a tela de Home, basta seguir a barra de navegação e clicar em Home.
![Home-logout](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/8b6d16e8-4aa0-40ca-8cc2-81fe51195c0f)


- Acessando a tela de Home novamente e indo em Cadastrar Despesa, você terá acesso a está tela.
![despesa-1](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/6e326865-e104-49fa-847f-e481cb188d22)


- Para cadastrar uma despesa você precisa de uma descrição, o valor e a data que ocorreu a despesa.
![data-despesa](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/fa490a69-d500-4a22-9c35-b91a7ef13108)


- Cadastrando a Despesa, será possivel visualizar a mesma na tela de Home.
![home-c-despesa](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/de8349dd-7c55-454a-843c-afa339315fb6)


- Na tela de home é possivel excluir e editar a despesa cadastrada. 
![edit-despesa-2](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/1676322a-8438-4a1c-a39d-0d8ee4d2e7ec)


- Clicando em Excluir despesa, aparecerá um alerta perguntando se deseja mesmo excluir.
![excluir-despesa-3](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/a9d037f1-926c-4521-ae2d-a790952a5775)


- Para fazer Logout, basta clicar na barra de navegação "Logout"


## CURLS da API
- post /api/register
    ```
        curl --location 'http://127.0.0.1:8000/api/register' \
        --header 'Accept: application/json' \
        --header 'Content-Type: application/json' \
        --data-raw '{
            "name": "teste",
            "email": "teste@gmail.com",
            "password": "123456789"
        }'
    ```


- post /api/login
    ```
        curl --location 'http://localhost:8000/api/login' \
        --header 'Content-Type: application/json' \
        --data-raw '{
        "email": "teste@gmail.com",
            "password": "123456789"
        }'
    ```


- post /api/logout
    ```
        curl --location --request POST 'http://localhost:8000/api/logout' \
        --header 'Authorization: Bearer {seu_token}' \
        --header 'Accept: application/json' \
        --data ''
    ```


- get /api/users
    ```
        curl --location 'http://localhost:8000/api/users' \
        --header 'Accept: application/json'
    ```


- get /api/expenses
    ```
        curl --location 'http://localhost:8000/api/expenses' \
        --header 'Accept: application/json' \
        --header 'Authorization: ••••••' \
        --data ''
    ```


- post /api/expenses
    ```
        curl --location 'http://localhost:8000/api/expenses' \
        --header 'Accept: application/json' \
        --header 'Content-Type: application/json' \
        --header 'Authorization: ••••••' \
        --data '{
            "descriptions": "teste",
            "price": "12345678",
            "expense_date": "03/12/2023"
        }'
    ```

- put /api/expenses/{id}
    ```
        curl --location --request PUT 'http://localhost:8000/api/expenses/1' \
        --header 'Accept: application/json' \
        --header 'Content-Type: application/json' \
        --header 'Authorization: ••••••' \
        --data '{
            "descriptions": "teste",
            "price": "1111",
            "expense_date": "05/01/2024"
        }'
    ```


- delete /api/expenses/{id}
    ```
        curl --location --request DELETE 'http://localhost:8000/api/expenses/1' \
        --header 'Accept: application/json' \
        --header 'Authorization: ••••••' \
        --data ''
    ```
