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
    public function it_documents_expression_evaluator_behavior()
    {
        $evaluator = new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer());

        $this->assertSame(4, $evaluator->evaluate('2 + 2'));
    }

    #[Test]
    public function it_wraps_expression_evaluation_errors()
    {
        $this->expectException(RuntimeException::class);

        (new ExpressionEvaluator(new FunctionRegistry(), new ExpressionTokenizer()))
            ->evaluate('invalid php syntax ???');
    }
}
