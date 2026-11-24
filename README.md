# StreamUp — Streaming de Filmes (PHP)

## Tema do sistema

**StreamUp** é um sistema web de catálogo de filmes com avaliações da comunidade.
O usuário faz login, cadastra, edita e exclui filmes, e avalia cada filme com uma nota de 1 a 5 e um comentário.
A página inicial mostra os filmes mais bem avaliados do catálogo.

Feito em PHP puro com MySQL/MariaDB (extensão `mysqli`), HTML, CSS e JavaScript.

## Integrantes do grupo

- Otávio Sanches
- Pablo Gomes
- Pietro Andrade
- Pietro Rodrigues

## Modelagem do banco

Banco: `sistema_streaming` (charset `utf8mb4`). Script completo em [`banco.sql`](banco.sql).

### Tabela `usuarios`

| Campo   | Tipo           | Restrições                  |
|---------|----------------|-----------------------------|
| `id`    | `INT(11)`      | PK, `AUTO_INCREMENT`        |
| `nome`  | `VARCHAR(100)` | `NOT NULL`                  |
| `email` | `VARCHAR(100)` | `NOT NULL`                  |
| `senha` | `VARCHAR(250)` | `NOT NULL`                  |

### Tabela `filmes`

| Campo             | Tipo           | Restrições                                   |
|-------------------|----------------|----------------------------------------------|
| `id`              | `INT(11)`      | PK, `AUTO_INCREMENT`                         |
| `titulo`          | `VARCHAR(150)` | `NOT NULL`                                   |
| `sinopse`         | `TEXT`         | `NOT NULL`                                   |
| `ano_lancamento`  | `YEAR(4)`      | `NOT NULL`                                   |
| `avaliacao`       | `DECIMAL(3,2)` | `NOT NULL` — média das notas recebidas       |
| `duracao_minutos` | `INT(11)`      | `NOT NULL`                                   |
| `genero`          | `VARCHAR(50)`  | `NOT NULL`                                   |

### Tabela `avaliacoes`

| Campo        | Tipo      | Restrições                              |
|--------------|-----------|-----------------------------------------|
| `id`         | `INT(11)` | PK, `AUTO_INCREMENT`                    |
| `usuario_id` | `INT(11)` | `NOT NULL`, FK → `usuarios(id)`         |
| `filme_id`   | `INT(11)` | `NOT NULL`, FK → `filmes(id)`           |
| `Score`      | `INT(5)`  | nota de 1 a 5                           |
| `comentario` | `TEXT`    | opcional                                |

**Relacionamentos:** um usuário faz várias avaliações e um filme recebe várias avaliações (`usuarios 1:N avaliacoes N:1 filmes`).

## Estrutura de pastas

```
StreamingDeFilmes_PHP/
├── avaliacoes/
│   ├── avaliar.php        # formulário de nova avaliação (nota + comentário)
│   ├── excluir.php        # exclui uma avaliação do próprio usuário
│   └── listar.php         # lista todas as avaliações
├── css/
│   └── style.css          # estilo do site (tema escuro)
├── filmes/
│   ├── atualizar.php      # editar filme
│   ├── cadastrar.php      # cadastrar filme
│   ├── excluir.php        # confirmar e excluir filme
│   ├── listar.php         # catálogo com cards e modal de detalhes
│   └── verifica_login.php # bloqueia páginas para quem não está logado
├── js/
│   └── modal-filmes.js    # abre o modal "Ver mais" com detalhes e avaliações
├── banco.sql              # criação do banco, tabelas e dados de exemplo (usuários, filmes e avaliações)
├── cabecalho.php          # topo e menu (incluído em todas as páginas)
├── conexao.php            # conexão com o MySQL
├── index.php              # página inicial com os destaques
├── login.php
├── logout.php
├── rodape.php
└── README.md
```

## Como rodar o projeto

Pré-requisito: [XAMPP](https://www.apachefriends.org/) (testado com XAMPP 8.2: PHP 8.2 e MariaDB 10.4).

1. **Colocar o projeto no `htdocs`.** A pasta precisa se chamar `StreamingDeFilmes_PHP`, porque os links do site usam esse caminho:
   ```
   C:\xampp\htdocs\StreamingDeFilmes_PHP
   ```
   Para clonar direto lá:
   ```bash
   git clone https://github.com/PietroSantos280/StreamingDeFilmes_PHP.git C:\xampp\htdocs\StreamingDeFilmes_PHP
   ```
2. **Iniciar os serviços.** No XAMPP Control Panel, clique em **Start** no **Apache** e no **MySQL**.
3. **Importar o banco.** Acesse `http://localhost/phpmyadmin`, abra a aba **Importar**, escolha o arquivo `banco.sql` e clique em **Executar**. O script cria o banco `sistema_streaming` sozinho.
4. **Configurar o `conexao.php`.** O padrão do XAMPP já funciona sem mudanças:
   ```php
   $host = "localhost";
   $usuario = "root";
   $senha = "";
   $banco = "sistema_streaming";
   ```
   Se o seu MySQL tiver senha no `root`, coloque-a em `$senha`.
5. **Acessar pela URL:**
   ```
   http://localhost/StreamingDeFilmes_PHP/
   ```

## Usuários de teste

Todos já vêm cadastrados no `banco.sql`. Para entrar, use o e-mail e a senha em `http://localhost/StreamingDeFilmes_PHP/login.php`.

| Nome        | E-mail            | Senha      | Avaliações já feitas                              |
|-------------|-------------------|------------|---------------------------------------------------|
| admin       | `admin@email.com` | `123456`   | A Origem, O Senhor dos Anéis: A Sociedade do Anel |
| Maria Silva | `maria@email.com` | `senha123` | Divertida Mente                                   |
| João Souza  | `joao@email.com`  | `abc456`   | Homem-Aranha: No Aranhaverso                      |
| Ana Costa   | `ana@email.com`   | `xyz789`   | nenhuma                                           |

Cada usuário só pode excluir as próprias avaliações. Entrando com usuários diferentes, dá para ver o botão **Excluir** aparecer só nas avaliações de quem está logado.

## Funcionalidades

- **Login e logout** com sessão (`$_SESSION`); páginas de filmes e avaliações só abrem para usuários logados.
- **Listar filmes** em cards, com a média de notas e um modal "Ver mais" que mostra sinopse, gênero e os comentários recebidos.
- **Cadastrar filme** (título, sinopse, ano, duração, avaliação e gênero).
- **Atualizar filme** com o formulário já preenchido com os dados atuais.
- **Excluir filme** com tela de confirmação; as avaliações ligadas ao filme são removidas junto.
- **Avaliar filme** com nota de 1 a 5 e comentário opcional.
- **Média automática:** ao criar ou excluir uma avaliação, a nota do filme é recalculada.
- **Destaques na página inicial:** os 3 filmes com maior nota.
- **Excluir avaliação:** cada usuário só pode excluir as próprias avaliações.
- **Validações:**
  - campos obrigatórios no cadastro de filme;
  - nota entre 1 e 5 na avaliação;
  - limites de ano, nota e tamanho de texto na edição;
  - textos exibidos com `htmlspecialchars` para não quebrar a página.
