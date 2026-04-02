<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\ExpressionPreparer;
use Icmbio\ValidateXpathExpression\FunctionRegistry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExpressionPreparerTest extends TestCase
{
    #[Test]
    public function it_documents_expression_preparation_behavior()
    {
        $preparer = new ExpressionPreparer(new FunctionRegistry());

        $expression = $preparer->prepare('. >= ${min} and floor(.) = 10', 10.7, ['min' => 1]);

        $this->assertStringContainsString('10.7 >= 1', $expression);
        $this->assertStringContainsString('floor(10.7)', $expression);
        $this->assertStringContainsString('&&', $expression);
        $this->assertStringContainsString('== 10', $expression);
    }
}
