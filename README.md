
# 🏦 **Financial Wallet**

## 📋 Sobre o Projeto
O objetivo deste projeto é desenvolver uma interface funcional que simule uma carteira financeira digital, permitindo aos usuários realizar operações como transferências de saldo e depósitos de maneira intuitiva e eficiente.

## 🛠 Requisitos
- **Sistema operacional**: Windows 11
- **Tecnologias utilizadas**:
  - Laravel 12
  - PHP 8.2
  - MySQL
  - Laravel Sanctum
  - Blade (Front-end)
  - Docker

## ⚙️ Instalação
### 1. Instalando o Docker no Windows
Se você ainda não possui o Docker instalado, siga os passos abaixo:
- Baixe o [Docker Desktop](https://www.docker.com/products/docker-desktop/).
- Instale o Docker e reinicie o sistema, se necessário.
- Certifique-se de que a virtualização está ativada no BIOS.

### 2. Configurando o ambiente Laravel
🔔 **Obs 1:** O Docker deve estar em execução antes de prosseguir.  
🔔 **Obs 2:** Se desejar conectar com o banco de dados utilizando um SGBD comum, use a porta 3307.

#### 🚀 Subindo os containers com Docker
```sh
docker-compose up -d --build
```

#### 📦 Instalando dependências do Laravel
```sh
docker exec -it laravel_php composer install
```

#### 🔑 Gerando a chave da aplicação
```sh
docker exec -it laravel_php php artisan key:generate
```

#### ⚙️ Configurando o `.env`
- Copie o arquivo `.env.example` e renomeie para `.env`.
- No `.env`, altere as variáveis para os seguintes valores:
```env
DB_HOST=laravel_mysql
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

#### 🗂 Criando as tabelas no banco de dados
```sh
docker exec -it laravel_php php artisan migrate
```

#### 🗂 Populando o banco de dados
```sh
docker exec -it laravel_php php artisan db:seed
```

## 🔐 Credenciais de Acesso
```json
{
    "email": "test@example.com",
    "password": "test_password"
}
```

## 🧪 Executando Testes
Para rodar os testes, utilize:
```sh
php artisan test
```

## 💻 Abrir o projeto no navegador
- http://localhost:8000/

## 🚀 Funcionalidades
- ✅ Login
- ✅ Logout
- ✅ Criar Usuário
- ✅ Listar Usuários
- ✅ Realizar Transferência
- ✅ Realizar Depósito
- ✅ Reverter Depósito/Transferência
- ✅ Listar Transferências

## 💻 Tecnologias utilizadas
  - PHPUnit - Testes Unitários e de Controller
  - Utilizando conceitos como DTOs, Contracts, Repositories, Resources, Factories, entre outros.

## 📌 Documentação das Rotas
As rotas da API estão disponíveis no Postman.

## 🔗 Dica
  - Para testar as rotas, utilize a extensão [Postman](https://www.postman.com/) ou o comando `curl` no terminal. 
  - [JSON do Postman para importação, com rotas e documentação](https://drive.google.com/file/d/1RohXUUZLz8E_urzRVjLBst0VU_KdaEUJ/view?usp=sharing)

---

📝 **Dúvidas ou contribuições?** Sinta-se à vontade para abrir uma issue ou enviar um PR! 🚀
