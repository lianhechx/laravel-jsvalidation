<?php

namespace Opbol\JsValidation\Support;

trait UseDelegatedValidatorTrait
{
    /**
     * Delegated validator.
     *
     * \Opbol\JsValidation\Support\DelegatedValidator $validator
     */
    protected $validator;

    /**
     * Sets delegated Validator instance.
     *
     * @param \Opbol\JsValidation\Support\DelegatedValidator $validator
     */
    public function setDelegatedValidator(DelegatedValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     *  Gets current DelegatedValidator instance.
     * @return \Opbol\JsValidation\Support\DelegatedValidator
     */
    public function getDelegatedValidator()
    {
        return $this->validator;
    }
}
