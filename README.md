# PHP API Project

php -S localhost:8000 -t src/public
sudo docker-compose -f "docker-compose.yml" up -d --build
docker stop $(docker ps -aq)
docker rm $(docker ps -aq)
docker volume rm $(docker volume ls -q)
docker network rm $(docker network ls -q)


Este projeto é uma API construída em PHP, utilizando o Composer para gerenciamento de dependências. A API é projetada para gerenciar exemplos e fornece endpoints para interagir com os dados.

## Estrutura do Projeto

```
php-api
├── src
│   ├── Controllers
│   │   └── ExampleController.php
│   ├── Models
│   │   └── ExampleModel.php
│   ├── Routes
│   │   └── api.php
│   └── Helpers
│       └── helper.php
├── vendor
├── composer.json
├── composer.lock
└── README.md
```

php-api
├── src
│   ├── Docker
│   │   └── docker-compose.yml
│   │   └── Dockerfile
│   └── public
│       └── index.php
├── vendor
├── composer.json
├── composer.lock
└── README.md



## Instalação

1. Clone o repositório:
   ```
   git clone <URL_DO_REPOSITORIO>
   ```

2. Navegue até o diretório do projeto:
   ```
   cd php-api
   ```

3. Instale as dependências usando o Composer:
   ```
   composer install
   ```

## Uso

Após a instalação, você pode iniciar o servidor embutido do PHP para testar a API:

```
php -S localhost:8000 -t public
```

## Endpoints

- `GET /api/example`: Retorna um exemplo.
- `POST /api/example`: Cria um novo exemplo.

## Contribuição

Sinta-se à vontade para contribuir com melhorias ou correções. Faça um fork do repositório e envie um pull request.

## Licença

Este projeto está licenciado sob a MIT License. Veja o arquivo LICENSE para mais detalhes.