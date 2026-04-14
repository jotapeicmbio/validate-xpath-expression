<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\ExpressionEvaluator;
use Icmbio\ValidateXpathExpression\ExpressionTokenizer;
use Icmbio\ValidateXpathExpression\FunctionRegistry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExpressionEvaluatorTest extends TestCase
{
    #[Test]
    public function it_documents_expression_evaluator_behavior(): void
    {
        $evaluator = new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer());

        $this->assertSame(4, $evaluator->evaluate('2 + 2'));
    }

    #[Test]
    public function it_wraps_expression_evaluation_errors(): void
    {
        $this->expectException(RuntimeException::class);

        (new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer()))
            ->evaluate('invalid php syntax ???');
    }

    #[Test]
    public function it_returns_false_for_order_comparisons_with_non_numeric_strings(): void
    {
        $evaluator = new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer());

        $this->assertFalse($evaluator->evaluate("'dez' >= 18"));
    }
}
