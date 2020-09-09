<?php

namespace Engency\ModelValidation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Validator
{

    /**
     * @param ValidatesModel $validator
     * @param array          $data
     * @param string|null    $occasion
     * @param object|null    $instance
     * @return array
     * @throws ValidationException
     */
    public function validate(ValidatesModel $validator, array $data, ?string $occasion, ?object $instance) : array
    {
        $rules = $this->getRulesFromModelValidator($validator, $occasion);
        if ($instance instanceof Model) {
            $rules = $this->getRulesWithExceptCurrentInstance($instance, $rules);
        }

        return $this->validateWithRules($data, $rules);
    }

    /**
     * @param array $data
     * @param array $rules
     * @return array
     * @throws ValidationException
     */
    public function validateWithRules(array $data, array $rules) : array
    {
        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * @param ValidatesModel $validator
     * @param string|null    $occasion
     * @return array
     */
    private function getRulesFromModelValidator(ValidatesModel $validator, ?string $occasion) : array
    {
        if ($occasion !== null && strlen($occasion) > 0 && method_exists($validator, $occasion . 'Rules')) {
            $rules = $validator->{$occasion . 'Rules'}();
            if (is_array($rules)) {
                return $rules;
            }
        }

        return $validator->rules();
    }

    /**
     * @param Model $instance
     * @param array $rules
     *
     * @return array
     */
    private function getRulesWithExceptCurrentInstance(Model $instance, array $rules) : array
    {
        foreach ($rules as &$rule) {
            if (strpos($rule, 'unique') === false) {
                continue;
            }

            $ruleParticles = explode('|', $rule);
            foreach ($ruleParticles as &$ruleParticle) {
                if (strpos($ruleParticle, 'unique') === false || substr_count($ruleParticle, ',') !== 1) {
                    continue;
                }

                $primaryKey   = $instance->getKeyName();
                $ruleParticle .= ',' . $instance->{$primaryKey} . ',' . $primaryKey;
            }
            $rule = implode('|', $ruleParticles);
        }

        return $rules;
    }
}
