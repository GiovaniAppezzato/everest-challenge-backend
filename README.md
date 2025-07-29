## 📝 Introdução

Olá! Este repositório contém a minha solução para o teste de Backend/Integrações da Everest.

A proposta consiste em uma API simples desenvolvida em Laravel que consome dados de filmes da OMDb API, armazena essas informações em um banco de dados local e disponibiliza um endpoint RESTful para consulta dos dados.

Também inclui um comando Artisan que permite importar facilmente os dados da OMDb.

Caso queira entrar em contato comigo, estou disponível no LinkedIn, pelo e-mail giovani.appezzato@gmail.com ou pelo telefone (19) 99494-7867.

## 🚀 Ambiente

Siga as **instruções** abaixo para configurar o ambiente e rodar o backend do projeto localmente. Existem duas formas de instalar o projeto: com Docker utilizando Laravel Sail e sem Docker.

### 📋 Pré-requisitos

Antes de começar, verifique se você possui as seguintes dependências instaladas. Caso contrário, faça o download e instale-as para prosseguir:

Requisitos gerais:

* [Git](https://git-scm.com/downloads)
* [Docker (Opcional)](https://www.docker.com/)
* [Composer (Opcional)](https://getcomposer.org/)

Caso opte por não usar Docker:

* [NPM](https://www.npmjs.com/)
* [PHP ^8.4](https://www.php.net/releases/8.2/en.php)
* [PostgreSQL](https://www.docker.com/) ou [MySQL](https://www.mysql.com/)

### 🐳 Instalação (com Docker e Laravel Sail)

Se você optar por rodar o projeto usando Docker, essa é a abordagem recomendada, especialmente se estiver em um ambiente Linux. Para usuários do Windows, é necessário utilizar o [WSL 2 (Windows Subsystem for Linux)](https://learn.microsoft.com/pt-br/windows/wsl/install)  em conjunto com o Docker Desktop. Caso contrário, pule para a instalação do projeto sem o Docker.

1. Clone o repositório:

``` bash
git clone https://github.com/GiovaniAppezzato/everest-challenge-backend
```

2. Navegue até a pasta do projeto e execute o comando para instalar todas as dependências necessárias:

``` bash
composer install
```

Caso não tenha o Composer instalado localmente, você pode utilizar o seguinte comando para instalar as dependências diretamente no container do Laravel Sail:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

3. Crie o arquivo de configuração copiando o exemplo fornecido:

``` bash
cp .env.example .env
```

4. Abra o arquivo `.env` e configure as variáveis de ambiente conforme necessário. Certifique-se de configurar corretamente as informações necessárias para a aplicação:

``` bash
APP_URL=http://localhost
# APP_PORT=80

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
# FORWARD_DB_PORT=5433
```

Descomente a linha FORWARD_DB_PORT caso tenha um banco rodando localmente na porta 5432.

5. Em seguida, configure a chave da OMDb (ou use a chave de teste abaixo):

``` bash
OMDB_API_URL=http://www.omdbapi.com
OMDB_API_KEY=a809e145
```

6. Inicie os containers Docker usando o Laravel Sail:

``` bash
./vendor/bin/sail up -d
```

7. Acesse o terminal do container:

``` bash
./vendor/bin/sail bash
```

8. Crie a APP_KEY do projeto:

``` bash
php artisan key:generate
```

9. Execute as migrations para criar as tabelas no banco de dados:

``` bash
php artisan migrate
```

10. Por fim, execute o comando customizado responsável por importar os dados. Ele fará algumas perguntas interativas para ajudar na busca dos filmes na API.

``` bash
php artisan app:import-movies  
```

11. Pronto! o projeto estará rodando em um ambiente Dockerizado, pronto para ser utilizado localmente acessando o [localhost](http://localhost:80)

### 🔧 Instalação (sem Docker)

1. Clone o repositório:

``` bash
git clone https://github.com/GiovaniAppezzato/everest-challenge-backend
```

2. Instale as dependências necessárias:

``` bash
composer install
```

3. Crie o arquivo de configuração copiando o exemplo fornecido:

``` bash
cp .env.example .env
```

4. Abra o arquivo `.env` e configure as variáveis de ambiente conforme necessário. Certifique-se de configurar corretamente as informações necessárias para a aplicação:

``` bash
DB_CONNECTION=pgsql # Ou mysql
DB_HOST=127.0.0.1
DB_PORT=5432 # Ou 3306 
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Como alternativa, você pode usar o banco de dados local [SQLite](https://www.sqlite.org/):

``` bash
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

5. Em seguida, configure a chave da OMDb (ou use a chave de teste abaixo):

``` bash
OMDB_API_URL=http://www.omdbapi.com
OMDB_API_KEY=a809e145
```

6. Crie a APP_KEY do projeto:

``` bash
php artisan key:generate
```

7. Execute as migrations para criar as tabelas no banco de dados:

``` bash
php artisan migrate
```

8. Execute o comando customizado responsável por importar os dados. Ele fará algumas perguntas interativas para ajudar na busca dos filmes na API.

``` bash
php artisan app:import-movies  
```

9. Por fim, inicie o servidor local do Laravel:

``` bash
php artisan serve
```

10. Pronto! O projeto estará rodando localmente no endereço IP fornecido pelo terminal após a inicialização do servidor.

## 📡 Documentação da API

### `GET /api/movies`

Retorna uma lista paginada de filmes armazenados no banco de dados, permitindo aplicar filtros por título, ano de lançamento e diretor.

#### 🔍 Parâmetros de Consulta (opcionais)

| Parâmetro  | Tipo     | Descrição                                                                  |
|------------|----------|----------------------------------------------------------------------------|
| `title`    | `string` | Filtra filmes pelo título.                                                 |
| `year`     | `string` | Filtra filmes pelo ano de lançamento (formato: `YYYY`).                    |
| `director` | `string` | Filtra filmes pelo nome do diretor.                                        |
| `page`     | `int`    | Paginação. Número da página atual.                                         |

#### ✅ Exemplo de Requisição

```http
GET /api/movies?title=Batman
```

####  📦 Exemplo de Resposta

```json
{
    "data": [
        {
            "id": 10,
            "imdb_id": "tt18689424",
            "title": "Batman v Superman: Dawn of Justice (Ultimate Edition)",
            "year": "2016",
            "poster": "https://m.media-amazon.com/images/M/MV5BOTRlNWQwM2ItNjkyZC00MGI3LThkYjktZmE5N2FlMzcyNTIyXkEyXkFqcGdeQXVyMTEyNzgwMDUw._V1_SX300.jpg",
            "released": "2016-03-23",
            "plot": "N/A",
            "type": "movie",
            "runtime": "182 min",
            "directors": [
                "Zack Snyder"
            ]
        }
    ],
    "links": {
        "first": "http://localhost/api/movies?page=1",
        "last": "http://localhost/api/movies?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost/api/movies?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost/api/movies",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```

## ✅ Execução dos Testes

Para garantir que tudo está funcionando corretamente, execute os testes com o comando abaixo:

```bash
php artisan test
```
