<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\ExpressionEvaluator;
use Icmbio\ValidateXpathExpression\ExpressionTokenizer;
use Icmbio\ValidateXpathExpression\FunctionRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExpressionEvaluatorRejectionTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidExpressionProvider')]
    public function it_rejects_invalid_expressions_during_evaluation(string $expression): void
    {
        $this->expectException(RuntimeException::class);

        $this->evaluator()->evaluate($expression);
    }

    #[Test]
    #[DataProvider('invalidArityProvider')]
    public function it_rejects_function_calls_with_invalid_arity(string $expression): void
    {
        $this->expectException(RuntimeException::class);

        $this->evaluator()->evaluate($expression);
    }

    protected function evaluator(): ExpressionEvaluator
    {
        return new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidExpressionProvider(): array
    {
        return [
            'empty expression' => [''],
            'unknown function' => ['phpinfo()'],
            'unknown identifier' => ['system'],
            'missing closing parenthesis' => ['floor(1'],
            'missing opening parenthesis pair closure' => ['1 + 2)'],
            'operator without right operand' => ['1 +'],
            'operator without left operand' => ['&& 1'],
            'dangling comma in function call' => ['floor(1,)'],
            'leading comma in function call' => ['floor(,1)'],
            'double comma in function call' => ['choose(true(),,1,2)'],
            'empty parenthesis expression' => ['()'],
            'nested empty parenthesis expression' => ['(())'],
            'unterminated function nesting' => ['choose(true(), floor(1), 2'],
            'multiple expressions' => ['1 2'],
            'unsupported ternary syntax' => ['1 ? 2 : 3'],
            'unsupported assignment style input' => ['a = 1'],
            'unsupported property access style input' => ['true().foo'],
            'unsupported static access style input' => ['true()::foo'],
            'unsupported shell style input' => ['`id`'],
            'unsupported variable variable input' => ['$abc'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidArityProvider(): array
    {
        return [
            'floor without args' => ['floor()'],
            'floor with too many args' => ['floor(1, 2)'],
            'uuid with unexpected arg' => ['uuid(1)'],
            'true with unexpected arg' => ['true(1)'],
            'false with unexpected arg' => ['false(1)'],
            'choose with too few args' => ['choose(true(), 1)'],
            'contains with too few args' => ['contains("abc")'],
            'format date time with too few args' => ['format-date-time("2024-01-01")'],
        ];
    }
}
