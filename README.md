# 📚 API PHP MVC - SWAPI

## 📌 Sobre o Projeto
 * Primeira etapa do processo seletivo teste da L5 Networks

## ➡️ Requisitos
- **PHP** 7.4 
- **Composer** 2.8.5 ou superior
- **MySQL** 5.7 ou superior

# 🚀 Como Rodar o Projeto
* Instale as dependências do composer (usaremos para ter o autoload)
```sh
composer install
```

* Carregue as classes no autoload do composer
    - OBS: **Importante** usar **-o** para carregar todos os path de formas otimizadas
```sh
composer dump-autoload -o
```

## Vamos configurar o arquivo **.env**
* Configure de acordo com o **MySQL** instalado no seu ambiente.
 - Para debug caso não consiga conectar no banco de dados entre em (**storage/logs/app.log**)
```env
DB_SERVER=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=root
```

## Faça upload do drump .sql, localizado: (**database/db_l5_networks.sql**)
* No drump já esta configurado, **vai criar e usar o banco**.
```sql
CREATE DATABASE `db_l5_networks` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `db_l5_networks`;
```

## 🚀 Vamos inciar o Projeto:
* Iniciando o Backend 
```sh
php -S localhost:8000
```

* Iniciando o FrontEnd 
```sh
php -S localhost:8001 -t public/
```

## 🔧 Tecnologias Utilizadas

- **Back-end**: PHP, MySQL
- **Front-end**: javaScript, HTML, CSS, Bootstrap
- **Banco de Dados**: MySQL[phpMyAdmin 5.2.1]


### 📂 Configurações e Observações finais.

## Configurações do **php.ini**
* **Extension** usadas no projeto, devem serem **habilitadas**
```ini
extension=curl
extension=mbstring
extension=exif
extension=openssl
extension=pdo_mysql
```

## A versão exata do **php** que utilizei
```shell
* Path: (C:\php\php.exe)
PHP version 7.4.0 (cli) (built: Nov 27 2019 10:14:18) ( ZTS Visual C++ 2017 x64 )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
```

## Resolver erro de **SSL certificate problem**
* Caso no ambiente de esse erro, copie o arquivo **config/cacert.pem**, e cole no caminho do php.
* Depois faça a seguinte alteração no **php.ini**, no meu caso (**"C:\php\cacert.pem"**), reinicie seu terminal ou apache.
```ini
curl.cainfo = "C:\php\cacert.pem"
openssl.cafile = "C:\php\cacert.pem"

```
- Esse erro acontece por que estamos tentando fazer uma requisição em uma rota **https**, (**https://swapi.dev/api/**).
- Tambem deixei a opção de usar a rota **http** no arquivo **.env**, caso preferir, use:
```env
BASE_URL=http://swapi.dev
```
