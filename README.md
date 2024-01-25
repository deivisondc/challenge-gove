# Desafio Técnico - Gove

Este é um projeto que consiste em duas partes principais: uma API construída em Laravel, localizada na pasta api, e um front-end desenvolvido com Next.js, localizado na pasta app. O projeto é configurado para ser executado em contêineres Docker para garantir a portabilidade e facilidade de configuração.

## Estrutura do Projeto

```vbnet
/project-root
│   README.md
│   docker-compose.yml
└───api
│   │   Dockerfile
│   │   ... (arquivos da API em Laravel)
└───app
    │   Dockerfile
    │   ... (arquivos do front-end em Next.js)
```

## Configuração do Ambiente com Docker

O projeto utiliza Docker e Docker Compose para facilitar a configuração do ambiente de desenvolvimento. Certifique-se de ter o Docker e o Docker Compose instalados em sua máquina.

### Passos para Execução

1\. Clone o projeto via Git:

```bash
git clone git@github.com:deivisondc/challenge-gove.git
```

2\. Navegue até a raiz do projeto:

```bash
cd challenge-gove
```

3\. Execute o seguinte comando para construir e iniciar os contêineres:

```bash
docker-compose up --build
```
Este comando utilizará as configurações do arquivo docker-compose.yml para criar os contêineres necessários.

4\. Aguarde até que os contêineres estejam prontos. Após a conclusão, você poderá acessar a aplicação em http://localhost:3000 para o front-end e http://localhost:8000 para a API.

## Configuração de Filas e Tarefas Assíncronas

As filas neste projeto foram configuradas utilizando o próprio driver do banco de dados por questões de simplicidade. No entanto, é importante observar que, dependendo do volume de tarefas assíncronas, outros drivers, como Redis ou RabbitMQ, devem ser considerados para um melhor desempenho.

### Comandos do Terminal
Para gerenciar as filas e tarefas assíncronas, execute os seguintes comandos dentro do terminal:

#### 1. Escutar Filas "default" e "notifications":

Este comando ficará escutando as filas "default" e "notifications". A fila "default" é reservada apenas para a importação dos arquivos XLSX, enquanto a fila "notifications" é exclusiva para o processamento das notificações.

```bash
docker exec gove-api php artisan queue:work --queue=default,notifications
```

#### 2. Disparar Task Scheduling Diariamente:

Este comando é utilizado para acionar o Task Scheduling e rodar a job diariamente, caso não haja nenhuma interação do usuário.

```bash
docker exec gove-api php artisan schedule:work
```

## Desenvolvimento
API (Laravel): Os arquivos da API Laravel estão localizados na pasta api. Clique [aqui](/api/README.md) para mais detalhes.

Front-end (Next.js): Os arquivos do front-end Next.js estão na pasta app. Clique [aqui](/app/README.md) para mais detalhes.
