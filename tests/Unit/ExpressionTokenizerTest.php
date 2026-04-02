<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\ExpressionTokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExpressionTokenizerTest extends TestCase
{
    #[Test]
    public function it_documents_expression_tokenization_behavior()
    {
        $tokens = (new ExpressionTokenizer())->tokenize("floor(10.7) == '10'");

        $this->assertSame('identifier', $tokens[0]['type']);
        $this->assertSame('floor', $tokens[0]['value']);
        $this->assertSame('(', $tokens[1]['value']);
        $this->assertSame(10.7, $tokens[2]['value']);
        $this->assertSame('==', $tokens[4]['value']);
        $this->assertSame('10', $tokens[5]['value']);
    }
}
