<?php

namespace App\Test\TestCase\Actions\Validators;

use App\Actions\Validators\AssociationValidator;
use App\Test\Repositories\SupplierMemoryRepository;
use App\Test\Repositories\ProductMemoryRepository;
use PHPUnit\Framework\TestCase;

class AssociationValidatorTest extends TestCase 
{
    protected ?AssociationValidator $validator = null;

    protected function boot()
    {
        if (false == ($this->validator instanceof AssociationValidator)) {
            $this->validator = new AssociationValidator(
                new SupplierMemoryRepository(),
                new ProductMemoryRepository()
            );
        }

        return $this->validator;
    }

    public function testValidateStockField()
    {
        $this->boot();
        $this->validator->validate(1, 1, ['price' => 10]);
        $this->assertSame($this->validator->getHttpCode(), 422);
        $this->assertSame($this->validator->getMessage(), "Field [stock] is required");
    }

    // I will not implement the full test coverage but you can get the idea of how it would work
}

