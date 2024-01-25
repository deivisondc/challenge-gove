# Frontend Next.js

Frontend desenvolvido em Next.js, que utiliza App Router para navegação, Tailwind para estilização utilizando as cores temas da empresa e alguns componentes do repositório shadcn-ui, além de componentes próprios.

## Estrutura do Projeto
```scss
/app
│   ... (outros arquivos e pastas)
│
└───src
    └───app
    │   │   page.tsx -- Página principal (/)
    │   │   layout.tsx
    │   │
    │   └───components
    |       | ... (específicos da página)
    |   │   
    │   └───[id]
    │       │   page.tsx -- Detalhes (/123)
    │       |   
    │       └───components
    |           | ... (específicos da página)
    └───components
    |   | ... (componentes genéricos)
    |   |
    │   └───ui (shadcn-ui)
    │       │   ...
    │
    └───services
    │   │   api.js
    │
    └───types
    │   │   FileImportType.js
    │   │   FileImportErrorType.js
    │   │   NotificationsType.js
    │   │   ...
    │
    └───assets
        | Tempalte.xlsx
        │   ... (arquivos estáticos)
```

## Páginas Principais

### 1. Files
Página onde é possível:

- Fazer o download de um arquivo XLSX template.
- Fazer o upload dos arquivos XLSX que serão processados.
- Visualizar uma tabela com os arquivos importados e algumas informações sobre eles.
- Clicar em um dos itens da tabela para ver detalhes do arquivo importado.
- Visualizar duas tabelas adicionais com as notificações e os erros gerados durante a importação.

### 2 Detalhes do arquivo
Pagina onde é apresentado detalhes do arquivo como status, tabela de notificações e de erros (caso houver).

#### 2.1 Tabela de notificações

Esta tabela mostra detalhes das notificações e permite algumas ações do usuário:

- **Edit**: Permite editar a data para quando está agendada a notificação. Ao mudar a data, o backend irá processar essa notificação imediatamente (caso não haja nada na fila). Processar pode significar não fazer nada porque foi agendado para o futuro, por exemplo.
- **Retry**: Permite tentar processar novamente. Ao clicar, é enviado para o backend reprocesar a notificação imediatamente.
- **Cancel**: Cancelar que uma notificação seja enviada. Esta opção só estará habilitada para registros que não foram processados ainda (status `IDLE` ou `QUEUED`).

## Componentes

#### [Skeleton](src/components/DataTable/Skeleton.tsx)
Enquanto não há dados nas tabelas, é apresentado um esqueleto (Skeleton) para proporcionar uma experiência de carregamento.

#### [ExceptionBoundary](src/components/ExceptionBoundary/index.tsx)
Caso haja algum erro durante a requisição dos dados, este componente apresenta o erro ao usuário.

## Estrutura de Diretórios
- **pages**: Contém as páginas principais da aplicação.
- **pages/\*\*/components**: Contém componentes específicos da página.
- **src/components**: Contém componentes genéricos da aplicação.
- **src/components/ui**: Contém componentes do repositório shadcn-ui.
- **src/services**: Contém uma abstração do fetch para evitar repetições digitando a URL base da API e tratamento de erros.
- **src/types**: Contém os arquivos de tipagem utilizados no projeto.
- **src/assets**: Contém arquivos estáticos, incluindo o template XLSX.

## Execução via Docker
O frontend será servido via Docker no container chamado gove-app. Certifique-se de ter executado os comandos descritos [aqui](../README.md#passos-para-execução)

Acesse a aplicação em http://localhost:3000.

Lembre-se de ajustar os comandos e configurações conforme necessário para o seu ambiente.

## Melhorias futuras

- [ ] Criar página de Dashboard com estatísticas
- [ ] ReactQuery para trabalhar com estados assíncronos
- [ ] Storybook para documentar componentes
- [ ] Testes unitários