# Xpath

Fonte: [`src/Xpath.php`](../../../src/Xpath.php)

## Finalidade

`Xpath` é o ponto de entrada público da biblioteca. Ele armazena o estado de execução e coordena o pipeline completo de validação.

## Métodos públicos

### `__construct(string $expression, mixed $value, array $context = [], bool $returnsBool = true)`

Armazena o estado de execução da expressão no objeto.

### `validate(string $expression, mixed $value, array $context = [], bool $returnsBool = true)`

Método estático de conveniência. Cria uma instância e executa a expressão imediatamente.

### `escapeExpression(string $expr): string`

Escapa `${` para `\${`.

Isso é útil quando você precisa manter o texto literal `${nome}` intacto, sem interpretá-lo como placeholder de contexto.

### `execute()`

Executa o pipeline completo:

1. cria um `ExpressionPreparer`
2. prepara a expressão
3. cria um `ExpressionEvaluator`
4. avalia a expressão preparada
5. retorna um booleano ou o valor bruto

## Observações

- `Xpath` foi mantido propositalmente pequeno
- ele não faz parse diretamente
- ele não sabe como cada função funciona internamente
- essas responsabilidades ficam delegadas a objetos específicos
