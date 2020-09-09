<?php

namespace Engency\ModelValidation;

use Illuminate\Validation\ValidationException;

interface ValidatesModel
{

    /**
     * @param array       $data
     * @param string|null $occasion
     * @param null        $instance
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data, ?string $occasion = null, $instance = null) : array;

    /**
     * @return array
     */
    public function rules() : array;
}
