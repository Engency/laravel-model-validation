<?php

namespace Engency\ModelValidation;

use Exception;

class ModelValidatorFactory
{

    /**
     * @param object $model
     * @return ValidatesModel
     */
    public function getForModel(object $model) : ValidatesModel
    {
        return $this->getForClassName(get_class($model));
    }

    /**
     * @param string $className
     * @return ValidatesModel
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getForClassName(string $className) : ValidatesModel
    {
        $className          = last(explode('\\', $className));
        $validatorClassName = '\\App\\ModelValidators\\' . $className . 'ModelValidator';

        if (class_exists($validatorClassName)) {
            $instance = new $validatorClassName;
            if ($instance instanceof ValidatesModel) {
                return $instance;
            }
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception('Validator not found for model "' . $className . '".');
    }
}
