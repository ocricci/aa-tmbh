<?php

namespace App\Actions\Validators;

class ProductValidator
{
    public array $required = [
        "species" => ['pine', 'spruce', 'fir', 'birch', 'apple_tree'],
        "grading_system" => ['nordic_blue', 'tegernseer'],
        "grade" => "string",
        "drying" => ['fresh', 'kiln_dried', 'air_dried'],
        "treatment" => ['heat', 'anti_strain', 'null'], // optional
        "thickness" => "int",
        "width" => "int",
        "length" => "int",
    ];

    protected bool $isValid = true;

    protected string $message = '';

    public function validate(array $product = [])
    {
        foreach ($this->required as $field => $rules) {
            if (empty($product[$field])) {
                $this->message = "The field {$field} is required";
                $this->isValid = false;
                break;
            }

            if (is_array($rules)) {
                if (false == in_array(trim($product[$field]), $rules)) {
                    $this->message = sprintf(
                        "Unknown value [%s] for [%s], supported values are [%s]",
                        trim($product[$field]),
                        $field,
                        implode(', ', $rules)
                    );
                    $this->isValid = false;
                    break;
                }
            }

            if ($rules == "int" && false == is_int($product[$field])) {
                $this->message = "The field {$field} must be int";
                $this->isValid = false;
                break;
            }

            if ($rules == "string" && false == is_string($product[$field])) {
                $this->message = "The field {$field} must be string";
                $this->isValid = false;
                break;
            }
        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}

