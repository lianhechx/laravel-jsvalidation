<?php

namespace Opbol\JsValidation\Support;


class UseDelegatedValidatorTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testGetterAndSetter()
    {
        $mockTrait = $this->getMockForTrait('Opbol\JsValidation\Support\UseDelegatedValidatorTrait');
        $mockDelegated = $this->getMockBuilder('Opbol\JsValidation\Support\DelegatedValidator')
            ->disableOriginalConstructor()
            ->getMock();

        $mockTrait->setDelegatedValidator($mockDelegated);
        $value = $mockTrait->getDelegatedValidator($mockDelegated);

        $this->assertEquals($mockDelegated, $value);
    }

}
