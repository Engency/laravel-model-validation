<?php

namespace Engency\ModelValidation;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Validation\ValidationException;

trait Validatable
{
    /**
     * Fill the model with an array of attributes.
     *
     * @param array $attributes
     *
     * @return $this
     * @throws MassAssignmentException
     *
     */
    abstract public function fill(array $attributes);

    /**
     * @param array $options
     *
     * @return mixed
     */
    abstract public function save(array $options = []);

    /**
     * @return ValidatesModel
     */
    protected function getModelValidator() : ValidatesModel
    {
        return self::modelValidatorFactory()->getForModel($this);
    }

    /**
     * @param array       $data
     * @param string|null $occasion
     *
     * @return static
     * @throws ValidationException
     */
    public static function validateAndCreateNew(array $data, ?string $occasion = 'default')
    {
        $instance = new static();

        $validated = $instance->getModelValidator()->validate($data, $occasion);

        // validation succeeded and passed data is stored in $validated

        $instance->fill($validated);
        $instance->save();
        $instance->fill($validated);

        return $instance;
    }

    /**
     * @param array       $data
     * @param string|null $occasion
     *
     * @return static
     * @throws ValidationException
     */
    public function validateAndUpdate(array $data, ?string $occasion = 'default')
    {
        $validated = $this->getModelValidator()->validate($data, $occasion, $this);

        // validation succeeded and passed data is stored in $validated

        $this->fill($validated);
        $this->save();

        return $this;
    }

    /**
     * @return ModelValidatorFactory
     */
    private static function modelValidatorFactory() : ModelValidatorFactory
    {
        return new ModelValidatorFactory();
    }
}
