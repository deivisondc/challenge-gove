# Backend Laravel
Backend desenvolvido em Laravel, que utiliza o Service Layer e Repository Pattern para estruturação do código. O projeto também incorpora práticas de error handling, jobs e task scheduling para melhor eficiência e escalabilidade.

## Estrutura do Projeto
```scss
/api
│   ... (outros arquivos e pastas)
│
└───app
│   │   ...
│   └───Controllers
│       │   FileImportController.php
│       │   FileImportErrorController.php
│       │   NotificationController.php
│   └───Imports
│       │   ExcelImport.php
│   └───Jobs
│       │   SendNotification.php
│   └───Services
│       │   NotificationService.php
│       │   FileImportService.php
│       │   ... (outros services)
│   └───Repositories
│       │   NotificationRepository.php
│       │   FileImportRepository.php
│       │   ... (outros repositories)
│   └───Exceptions
│       │   ...
│
└───tests
    └───Unit
        │   FileImportServiceTest.php
        │   NotificationServiceTest.php
        │   ... (outros testes unitários)
    └───Feature
        │   ... (faltam alguns Feature Tests)
```

## Controllers

- [FileImportController](app/Http/Controllers/FileImportController.php): Responsável por importar o arquivo XLSX e expor rotas para recuperar dados da importação e atualizar os dados da mesma.
- [FileImportErrorController](app/Http/Controllers/FileImportErrorController.php): Expõe uma rota para recuperar os erros encontrados durante a importação do arquivo XLSX.
- [NotificationController](app/Http/Controllers/NotificationController.php): Expõe rotas para recuperar e atualizar dados das notificações.

## Importação de Arquivo XLSX
O processo de importação é gerenciado pela classe ExcelImport.php localizada em `app/Imports`. O processamento é feito em chunks de 10 mil linhas. Para cada linha, verifica-se se o contato existe (com base no telefone/email). Caso não exista, é salvo. Os registros também são salvos em batch nas tabelas de notificações e erros (caso existam), além de atualizar o status da importação do arquivo.

Ao final desse processo, é disparada uma job ([SendNotification.php](app/Jobs/SendNotification.php)) que recupera todas as notificações com o status `IDLE` e agendadas para serem enviadas até o dia atual. Em seguida, é feita uma atualização em batch, marcando-as como `QUEUED` para processamento. O processamento é feito em chunks de 5000 registros por vez, chamando um método do service de envio (ainda vazio) e atualizando os status para `SUCCESS` ou `ERROR`.

## Task Scheduling
Um Task Scheduling foi configurado para disparar a job SendNotification diariamente às 13:00h.

## Execução via Docker
O backend será servido via Docker no container chamado gove-api. Certifique-se de ter executado os comandos descritos [aqui](../README.md#passos-para-execução)

Acesse a API em http://localhost:8000/api.

### Execução das Migrations
Para rodar as migrations dentro do container Docker:

```bash
docker exec gove-api php artisan migrate
```

### Execução dos Testes
Para rodar os testes dentro do container Docker:

```bash
docker exec gove-api php artisan test
```

Lembre-se de ajustar os comandos e configurações conforme necessário para o seu ambiente.

## Melhorias futuras

- [ ] Testes de feature
