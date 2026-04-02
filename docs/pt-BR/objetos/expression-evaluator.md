# ExpressionEvaluator

Fonte: [`src/ExpressionEvaluator.php`](../../../src/ExpressionEvaluator.php)

## Finalidade

`ExpressionEvaluator` faz o parse e a avaliação do fluxo de tokens produzido por `ExpressionTokenizer`.

## Forma da gramática

Atualmente o avaliador lida com:

- `or` booleano
- `and` booleano
- comparações
- expressões aditivas
- expressões multiplicativas
- menos unário
- agrupamento com parênteses
- literais
- chamadas de função

## Estratégia de parse

O parser é implementado como um avaliador recursivo descendente, com a precedência dos operadores codificada na ordem dos métodos:

1. `parseOrExpression`
2. `parseAndExpression`
3. `parseComparisonExpression`
4. `parseAdditiveExpression`
5. `parseMultiplicativeExpression`
6. `parseUnaryExpression`
7. `parsePrimaryExpression`

## Execução de funções

Quando o avaliador encontra uma chamada de função:

1. resolve o handler pelo `FunctionRegistry`
2. valida a aridade do construtor via reflexão
3. instancia o handler
4. chama `handle()`

## Modelo de erro

O avaliador encapsula falhas de parse e execução em `RuntimeException`, preservando a expressão original na mensagem.

## Por que isso substituiu o `eval()`

O avaliador atual entende apenas o dialeto suportado pela biblioteca. Ele não executa código PHP arbitrário, o que reduz a superfície de risco e torna o comportamento mais previsível.
