# 📘 API - Guia de Design e Convenções

> Este documento define os padrões obrigatórios para o desenvolvimento da API. Ele deve ser seguido por todos os devs para garantir consistência, manutenibilidade e escalabilidade.

---

## 📐 Estrutura e Formatação de Código

### 🌿 Estilo

* **Indentação:** 1 Tab - a cada subsequencia.
* **Chaves:** Sempre na mesma linha da declaração.

  ```php
  if ($condicao) {
      // Código
  }
  ```
* **Tipagem Obrigatória:** Todas as funções, métodos e parâmetros DEVEM utilizar tipagem estática.

```php
function soma(int $a, int $b): int {
    return $a + $b;
}
```

**Condições simples DEVEM ser escritas em uma única linha!**


* **IF simples:**

  ```php
  if (! $user) return response()->json(['error' => 'Usuário não encontrado'], 404);
  ```

* **Validação simples:**

  ```php
  if (! isset($dados['nome'])) return false;
  ```

* **Verificação com operador ternário:**

  ```php
  $status = $ativo ? 'ativo' : 'inativo';
  ```

* **Filtro de array simples com função anônima:**

  ```php
  $filtrados = array_filter($usuarios, fn($status) => $status['ativo']);
  ```

* **Early return simples:**

  ```php
  if ($token !== $esperado) return $this->erro401();
  ```

> Observação: Caso a condição contenha ELSE ou múltiplas ações, usar bloco com chaves.

### 🔀 Uso de SWITCH / CASE

* Sempre usar `break` (ou `return`) para evitar fall-through.
* O `default` é obrigatório.

  ```php
  switch ($tipo) {
      case 'admin':
          processarAdmin();
          break;

      case 'cliente':
          processarCliente();
          break;

      default:
          throw new Exception('Tipo inválido');
  }
  ```
## 🔢 Manipulação de Arrays

### Exemplo base:

```php
$users = [
    ['id' => 1, 'name' => 'João', 'email' => 'joao@email.com', 'active' => true, "data" => "2023-10-01 00:00:00"],
    ['id' => 2, 'name' => 'Maria', 'email' => 'maria@email.com', 'active' => false, "data" => "2023-10-01 00:00:00"],
    ['id' => 3, 'name' => 'Pedro', 'email' => 'pedro@email.com', 'active' => true, "data" => "2023-10-01 00:00:00"],
];
```

### 1. Filtrar usuários ativos

```php
$activeUsers = array_filter($users, fn($user) => $user['active']);
$firstActive = current(array_filter($users, fn($user) => $user['active']));
```

### 2. Transformar nomes para maiúsculo

```php
$names = array_map(fn($user) => strtoupper($user['name']), $users);
```

### 3. Criar array transformado

```php
$transform_array = [
    'label' => 'Usuários do sistema',
    'users' => array_map(fn($user) => [
        'name' => $user['name'],
        'email' => (string) $user['email'],
        'data' => (new DateTime($user['data']))->format('d/m/Y'),
    ], $users),
];
```

### 4. Buscar usuário por ID

```php
$user = current(array_filter($users, fn($id) => $id['id'] === 2));
```

### 5. Extrair e-mails

```php
$emails = array_column($users, 'email');
```

### 6. Criar mapa Email => Nome

```php
$map = array_column($users, 'name', 'email');
```

### Cenário com aninhamento:

```php
$usuarios = [
    [
        'nome' => 'João',
        'posts' => [
            ['titulo' => 'Post 1', 'publicado' => true],
            ['titulo' => 'Post 2', 'publicado' => false],
        ],
    ],
    [
        'nome' => 'Maria',
        'posts' => [
            ['titulo' => 'Post 3', 'publicado' => true],
        ],
    ],
];

// 1. Obter nomes com ao menos 1 post publicado
$comPostsPublicados = array_filter($usuarios, fn($usuario) =>
    !empty(array_filter($usuario['posts'], fn($posts) => $posts['publicado']))
);

// 2. Obter todos os títulos publicados (flatmap)
$titulos = array_merge(...array_map(
    fn($usuario) => array_map(
        fn($publicados) => $publicados['titulo'],
        array_filter($usuario['posts'], fn($publicados) => $publicados['publicado'])
    ),
    $usuarios
));

// 3. Contar posts publicados por usuário
$contagem = array_map(fn($usuario) => [
    'nome' => $usuario['nome'],
    'publicados' => count(array_filter($usuario['posts'], fn($post) => $post['publicado']))
], $usuarios);
```
---

## 🔁 Boas Práticas

### 🧠 Lógica de Negócio

* Não colocar lógica de negócio em controllers.
* Use *services* para conter regras de negócio.

### 🔄 Iterações

* `foreach` só quando necessário. Evite loops em excesso.
* Utilize coleções/filters/mappers sempre que possível.

### 🧩 Interfaces

* Toda classe principal (services, repositórios) deve implementar uma interface correspondente.
* Proibido depender de classes concretas em outras classes: **sempre usar interfaces.**

---

## 🚫 Regras de Proibição

* ❌ **SQL direto no código:** Proibido. Use o motor de SQL do projeto.
* ❌ **Código duplicado:** Reutilize métodos comuns.
* ❌ **Stack trace para o cliente:** Nunca deve ser retornado em produção.

---

## 🔐 Segurança

* Todos os inputs devem ser validados e sanitizados.
* CSRF, XSS e injeção SQL devem ser prevenidos via middleware ou helpers.

---

## 📤 Formato de Respostas JSON

### ✅ Resposta de Sucesso

```json
{
  "status": "success",
  "result": {}
}
```

### ❌ Resposta de Erro

```json
{
  "status": "error",
  "message": "Descrição do erro"
}
```

---

## 🌍 Suporte a Idiomas (LANG)

* Usar `Accept-Language` no header.
* Utilizar sistema de tradução com fallback para `pt-BR`.
* Exemplo:

  ```php
  __('api.erro.nao_encontrado')
  ```

---

## 🧪 Testes

* **Cobertura mínima:** 80%.
* **Tipos obrigatórios:**

  * Unitário (services/helpers)
  * Integração (controllers)

---

## 📊 Logs e Monitoramento

* Todas as exceções devem ser logadas.
* Logs críticos devem ser enviados para serviços externos (Ex: Sentry).

---

## 🧭 Versionamento

* A versão da API deve estar contida na URL:

  * Exemplo: `/api/v1/clientes`

---

## 📄 Documentação

* Todas as rotas devem estar documentadas via Swagger ou Postman.
* Toda nova feature deve vir acompanhada de documentação.
* Padrão de commit semântico obrigatório.

---

> Mantenha este documento atualizado a cada nova regra ou padrão adotado.
