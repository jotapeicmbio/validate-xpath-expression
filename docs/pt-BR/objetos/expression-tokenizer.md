# ExpressionTokenizer

Fonte: [`src/ExpressionTokenizer.php`](../../../src/ExpressionTokenizer.php)

## Finalidade

`ExpressionTokenizer` faz a análise léxica.

Ele lê a expressão normalizada e gera tokens como:

- números
- strings
- identificadores
- operadores
- símbolos

## Responsabilidades

- dividir a entrada em um fluxo de tokens
- decodificar strings entre aspas
- rejeitar padrões léxicos não suportados

## Comportamento importante de rejeição

O tokenizador rejeita entradas com tokens não suportados, como:

- `->`
- `..`
- backticks
- `$` cru
- strings não terminadas
- pontuação não suportada

## Categorias de tokens suportadas

### Números

Exemplos:

- `10`
- `10.7`

### Strings

Exemplos:

- `'abacate'`
- `"abc"`

### Identificadores

Exemplos:

- `floor`
- `string-length`
- `format-date-time`

### Operadores e símbolos

Exemplos:

- `&&`
- `||`
- `==`
- `(`
- `)`
- `,`

## Relação com o avaliador

O tokenizador não tenta entender a estrutura gramatical. Ele apenas produz tokens validados. O `ExpressionEvaluator` é quem interpreta essa sequência.
