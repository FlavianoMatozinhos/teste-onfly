# Gestão de Usuários e Despesas

## DESCRIÇÃO


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

![banco](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/bbe72092-e6d3-41f4-84f0-a63e096ac51c)

- E configurações de e-mail.
![email](https://github.com/FlavianoMatozinhos/teste-onfly/assets/56773752/3ec550d0-f5a6-46b7-9e96-58c0677cf651)


3. **Instalação das Dependências**
- composer install
- npm install
- npm install axios

4. **Geração da Chave de Aplicação**
- php artisan key:generate

5. **Migração do Banco de Dados**
- php artisan migrate

6. **Instalação do Passport**
- composer require laravel/passport

6. **Instalação do Sanctum**
- composer require laravel/sanctum


7. **Instalação do Swagger**
- composer require darkaonline/l5-swagger
- php artisan l5-swagger:generate

8. **Execução do Worker de Filas**
- php artisan queue:work --queue=default

9. **Compilação dos Assets (para projetos Vue.js)**
- npm run serve ou npm run dev

## USO

