<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\FunctionRegistry;
use Icmbio\ValidateXpathExpression\Functions\FloorFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FunctionRegistryTest extends TestCase
{
    #[Test]
    public function it_documents_function_registry_resolution()
    {
        $registry = new FunctionRegistry();

        $this->assertSame(FloorFunction::class, $registry->resolve('floor'));
        $this->assertArrayHasKey('string-length', $registry->all());
    }
}
