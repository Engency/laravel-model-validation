<?php

namespace Engency\ModelValidation;

use Illuminate\Validation\ValidationException;

abstract class ModelValidator implements ValidatesModel
{
    private Validator  $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * @param array       $data
     * @param string|null $occasion
     * @param object|null $instance
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data, ?string $occasion = null, $instance = null) : array
    {
        return $this->validator->validate($this, $data, $occasion, $instance);
    }
}
