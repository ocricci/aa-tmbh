<?php

namespace App\Actions\Validators;

use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Entities\Association;

class AssociationValidator
{
    protected bool $isValid = true;

    protected string $message = '';

    protected int $httpCode = 422;

    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository,
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function validate(int $sid, int $pid, array $association = [])
    {
        if (false == $this->validateEntities($sid, $pid)) {
            return;
        }

        foreach (Association::$required as $field => $rules) {
            if (empty($association[$field])) {
                $this->isValid = false;
                $this->message = "Field [{$field}] is required";
                break;
            }

            if ($rules == "int" && !is_int($association[$field])) {
                $this->message = "The field {$field} must be int";
                $this->isValid = false;
                break;
            }

            // using the SQLite3 type as reference
            if ($rules == "num"
                && !is_int($association[$field])
                && !is_float($association[$field])
            ) {
                $this->message = "The field {$field} must be num";
                $this->isValid = false;
                break;
            }

            if ($association[$field] < 0) {
                $this->message = "The field {$field} must be greater than or equal to zero";
                $this->isValid = false;
                break;
            }
        }
    }

    public function validateEntities(int $sid, int $pid): bool
    {
        return $this->validateSupplier($sid) && $this->validateProduct($pid);
    }

    public function validateSupplier(int $sid): bool
    {
        $supplier = $this->supplierRepository->getById($sid);
        if (empty($supplier['id'])) {
            $this->isValid = false;
            $this->message = "Supplier entity not found for id [{$sid}]";
            $this->httpCode = 404;
            return false;
        }

        return true;
    }

    public function validateProduct(int $pid): bool
    {
        $product = $this->productRepository->getById($pid);
        if (empty($product['id'])) {
            $this->isValid = false;
            $this->message = "Product entity not found for id [{$pid}]";
            $this->httpCode = 404;
            return false;
        }

        return true;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}

