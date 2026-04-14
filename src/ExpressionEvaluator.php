<?php

namespace Icmbio\ValidateXpathExpression;

use ReflectionClass;
use RuntimeException;
use Throwable;

/**
 * @phpstan-import-type ExpressionToken from ExpressionTokenizer
 */
class ExpressionEvaluator
{
    /** @var list<ExpressionToken> */
    protected array $tokens = [];
    protected int $position = 0;

    public function __construct(
        protected FunctionRegistry $functionRegistry,
        protected ExpressionTokenizer $tokenizer
    ) {
    }

    public function evaluate(string $expression): mixed
    {
        try {
            $this->tokens = $this->tokenizer->tokenize($expression);
            $this->position = 0;

            $result = $this->parseExpression();

            if (!$this->isAtEnd()) {
                $token = $this->currentToken();
                throw new RuntimeException("Token inesperado: {$token['value']}");
            }

            return $result;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                "Erro ao avaliar expressão: {$expression}. " . $exception->getMessage()
            );
        }
    }

    protected function parseExpression(): mixed
    {
        return $this->parseOrExpression();
    }

    protected function parseOrExpression(): mixed
    {
        $left = $this->parseAndExpression();

        while ($this->matchValue('||')) {
            $right = $this->parseAndExpression();
            $left = $left || $right;
        }

        return $left;
    }

    protected function parseAndExpression(): mixed
    {
        $left = $this->parseComparisonExpression();

        while ($this->matchValue('&&')) {
            $right = $this->parseComparisonExpression();
            $left = $left && $right;
        }

        return $left;
    }

    protected function parseComparisonExpression(): mixed
    {
        $left = $this->parseAdditiveExpression();

        while ($token = $this->matchValues(['==', '!=', '<', '<=', '>', '>='])) {
            $right = $this->parseAdditiveExpression();
            if ($token['value'] === '==') {
                $left = $left == $right;
                continue;
            }

            if ($token['value'] === '!=') {
                $left = $left != $right;
                continue;
            }

            $comparison = $this->compareOrderableValues($left, $right);

            if ($comparison === null) {
                $left = false;
                continue;
            }

            if ($token['value'] === '<') {
                $left = $comparison < 0;
                continue;
            }

            if ($token['value'] === '<=') {
                $left = $comparison <= 0;
                continue;
            }

            if ($token['value'] === '>') {
                $left = $comparison > 0;
                continue;
            }

            $left = $comparison >= 0;
        }

        return $left;
    }

    protected function compareOrderableValues(mixed $left, mixed $right): ?int
    {
        if ($this->isNumericValue($left) && $this->isNumericValue($right)) {
            return ($left <=> $right);
        }

        if (is_string($left) && is_string($right)) {
            return strcmp($left, $right);
        }

        if (is_bool($left) && is_bool($right)) {
            return ($left <=> $right);
        }

        return null;
    }

    protected function isNumericValue(mixed $value): bool
    {
        return is_int($value) || is_float($value) || (is_string($value) && is_numeric($value));
    }

    protected function parseAdditiveExpression(): mixed
    {
        $left = $this->parseMultiplicativeExpression();

        while ($token = $this->matchValues(['+', '-'])) {
            $right = $this->parseMultiplicativeExpression();
            $left = $token['value'] === '+' ? $left + $right : $left - $right;
        }

        return $left;
    }

    protected function parseMultiplicativeExpression(): mixed
    {
        $left = $this->parseUnaryExpression();

        while ($token = $this->matchValues(['*', '/', '%'])) {
            $right = $this->parseUnaryExpression();
            if ($token['value'] === '*') {
                $left = $left * $right;
                continue;
            }

            if ($token['value'] === '/') {
                $left = $left / $right;
                continue;
            }

            $left = $left % $right;
        }

        return $left;
    }

    protected function parseUnaryExpression(): mixed
    {
        if ($this->matchValue('-')) {
            return -$this->parseUnaryExpression();
        }

        return $this->parsePrimaryExpression();
    }

    protected function parsePrimaryExpression(): mixed
    {
        $token = $this->currentToken();

        if ($token === null) {
            throw new RuntimeException('Expressao incompleta');
        }

        if ($token['type'] === 'number' || $token['type'] === 'string') {
            $this->position++;
            return $token['value'];
        }

        if ($this->matchValue('(')) {
            $value = $this->parseExpression();
            $this->consume(')', 'Esperado ")"');
            return $value;
        }

        if ($token['type'] === 'identifier') {
            return $this->parseIdentifierExpression();
        }

        throw new RuntimeException("Token inesperado: {$token['value']}");
    }

    protected function parseIdentifierExpression(): mixed
    {
        $identifier = $this->consumeType('identifier', 'Esperado identificador')['value'];
        $keyword = strtolower($identifier);

        if ($this->matchValue('(')) {
            $arguments = [];

            if (!$this->checkValue(')')) {
                do {
                    $arguments[] = $this->parseExpression();
                } while ($this->matchValue(','));
            }

            $this->consume(')', 'Esperado ")" ao fechar chamada de funcao');

            return $this->invokeFunction($identifier, $arguments);
        }

        return match ($keyword) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => throw new RuntimeException("Identificador inesperado: {$identifier}"),
        };
    }

    /**
     * @param list<mixed> $arguments
     */
    protected function invokeFunction(string $name, array $arguments): mixed
    {
        $handler = $this->functionRegistry->resolve($name);
        $this->assertValidFunctionArity($handler, $arguments, $name);

        return (new $handler(...$arguments))->handle();
    }

    /**
     * @param class-string $handler
     * @param list<mixed> $arguments
     */
    protected function assertValidFunctionArity(string $handler, array $arguments, string $functionName): void
    {
        $constructor = (new ReflectionClass($handler))->getConstructor();

        if ($constructor === null) {
            if (count($arguments) > 0) {
                throw new RuntimeException("Quantidade invalida de argumentos para a funcao: {$functionName}");
            }

            return;
        }

        $requiredParameters = $constructor->getNumberOfRequiredParameters();
        $maximumParameters = $constructor->getNumberOfParameters();
        $argumentCount = count($arguments);

        if ($argumentCount < $requiredParameters || $argumentCount > $maximumParameters) {
            throw new RuntimeException("Quantidade invalida de argumentos para a funcao: {$functionName}");
        }
    }

    /**
     * @return ExpressionToken
     */
    protected function consume(string $value, string $message): array
    {
        if ($this->checkValue($value)) {
            return $this->tokens[$this->position++];
        }

        throw new RuntimeException($message);
    }

    /**
     * @return ExpressionToken
     */
    protected function consumeType(string $type, string $message): array
    {
        $token = $this->currentToken();

        if ($token !== null && $token['type'] === $type) {
            $this->position++;
            return $token;
        }

        throw new RuntimeException($message);
    }

    protected function matchValue(string $value): bool
    {
        if ($this->checkValue($value)) {
            $this->position++;
            return true;
        }

        return false;
    }

    /**
     * @param list<string> $values
     * @return ExpressionToken|null
     */
    protected function matchValues(array $values): ?array
    {
        foreach ($values as $value) {
            if ($this->checkValue($value)) {
                return $this->tokens[$this->position++];
            }
        }

        return null;
    }

    protected function checkValue(string $value): bool
    {
        $token = $this->currentToken();

        return $token !== null && $token['value'] === $value;
    }

    /**
     * @return ExpressionToken|null
     */
    protected function currentToken(): ?array
    {
        return $this->tokens[$this->position] ?? null;
    }

    protected function isAtEnd(): bool
    {
        return $this->position >= count($this->tokens);
    }
}
