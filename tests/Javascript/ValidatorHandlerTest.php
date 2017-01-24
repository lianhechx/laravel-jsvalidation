<?php

namespace Opbol\JsValidation\Tests\Javascript;


use Opbol\JsValidation\Javascript\RuleParser;
use Opbol\JsValidation\Javascript\ValidatorHandler;

class ValidatorHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testValidationData() {

        $attribute = 'field';
        $rule = 'required_if:field2,value2';

        $rules = ['field'=>['required','array']];

        $mockDelegated = $this->getMockBuilder('Opbol\JsValidation\Support\DelegatedValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getRules','hasRule','parseRule','getRule','isImplicit'])
            ->getMock();

        $mockDelegated->expects($this->any())
            ->method('getRules')
            ->willReturn([$attribute=>[$rule]]);

        $mockDelegated->expects($this->any())
            ->method('hasRule')
            ->with($attribute, ValidatorHandler::JSVALIDATION_DISABLE)
            ->willReturn(false);

        $mockDelegated->expects($this->once())
            ->method('parseRule')
            ->with($rule)
            ->willReturn(['RequiredIf',['field2','value2']]);

        $mockDelegated->expects($this->once())
            ->method('isImplicit')
            ->with('RequiredIf')
            ->willReturn(false);


        $mockRule = $this->getMockBuilder('Opbol\JsValidation\Javascript\RuleParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockRule->expects($this->once())
            ->method('getRule')
            ->with($attribute, 'RequiredIf', ['field2','value2'])
            ->willReturn([$attribute, RuleParser::JAVASCRIPT_RULE, ['field2','value2']]);

        $mockMessages = $this->getMockBuilder('Opbol\JsValidation\Javascript\MessageParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockMessages->expects($this->once())
            ->method('getMessage')
            ->with($attribute, 'RequiredIf', ['field2','value2'])
            ->willReturn('Field is required if');


        $handler = new ValidatorHandler($mockRule, $mockMessages);
        $handler->setDelegatedValidator($mockDelegated);

        $data = $handler->validationData();
        $expected = [
            'rules' => array('field'=>['laravelValidation'=>[['RequiredIf',['field2','value2'],'Field is required if',false]]]),
            'messages' =>  array(),
        ];

        $this->assertEquals($expected, $data);


    }


    public function testValidationDataDisabled() {

        $attribute = 'field';
        $rule = 'required_if:field2,value2|no_js_validation';

        $rules = ['field'=>['required','array']];

        $mockDelegated = $this->getMockBuilder('Opbol\JsValidation\Support\DelegatedValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getRules','hasRule','parseRule','getRule','isImplicit'])
            ->getMock();

        $mockDelegated->expects($this->any())
            ->method('getRules')
            ->willReturn([$attribute=>explode('|',$rule)]);

        $mockDelegated->expects($this->any())
            ->method('hasRule')
            ->with($attribute, ValidatorHandler::JSVALIDATION_DISABLE)
            ->willReturn(true);

        $mockRule = $this->getMockBuilder('Opbol\JsValidation\Javascript\RuleParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockMessages = $this->getMockBuilder('Opbol\JsValidation\Javascript\MessageParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();


        $handler = new ValidatorHandler($mockRule, $mockMessages);
        $handler->setDelegatedValidator($mockDelegated);

        $data = $handler->validationData();
        $expected = [
            'rules' => array(),
            'messages' =>  array(),
        ];

        $this->assertEquals($expected, $data);


    }

    public function testSometimes() {

        $attribute = 'field';
        $rule = 'required_if:field2,value2';

        $mockDelegated = $this->getMockBuilder('Opbol\JsValidation\Support\DelegatedValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getRules','hasRule','parseRule','getRule','isImplicit','sometimes','explodeRules'])
            ->getMock();

        $mockDelegated->expects($this->once())
            ->method('sometimes')
            ->willReturn(null);


        $mockDelegated->expects($this->any())
            ->method('getRules')
            ->willReturn([$attribute=>[$rule]]);

        $mockDelegated->expects($this->any())
            ->method('hasRule')
            ->with($attribute, ValidatorHandler::JSVALIDATION_DISABLE)
            ->willReturn(false);

        $mockDelegated->expects($this->once())
            ->method('parseRule')
            ->with($rule)
            ->willReturn(['RequiredIf',['field2','value2']]);

        $mockDelegated->expects($this->once())
            ->method('isImplicit')
            ->with('RequiredIf')
            ->willReturn(false);


        $mockRule = $this->getMockBuilder('Opbol\JsValidation\Javascript\RuleParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockRule->expects($this->once())
            ->method('getRule')
            ->with($attribute, 'RequiredIf', ['field2','value2'])
            ->willReturn([$attribute, RuleParser::REMOTE_RULE, ['field2','value2']]);

        $mockMessages = $this->getMockBuilder('Opbol\JsValidation\Javascript\MessageParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockMessages->expects($this->once())
            ->method('getMessage')
            ->with($attribute, 'RequiredIf', ['field2','value2'])
            ->willReturn('Field is required if');


        $handler = new ValidatorHandler($mockRule, $mockMessages);
        $handler->setDelegatedValidator($mockDelegated);
        $handler->sometimes($attribute, $rule);

        $data = $handler->validationData();
        $expected = [
            'rules' => array('field'=>['laravelValidationRemote'=>[['RequiredIf',['field2','value2'],'Field is required if',false]]]),
            'messages' =>  array(),
        ];

        $this->assertEquals($expected, $data);
    }


    public function testDisableRemote() {

        $attribute = 'field';
        $rule = 'active_url';

        $mockDelegated = $this->getMockBuilder('Opbol\JsValidation\Support\DelegatedValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getRules','hasRule','parseRule','getRule','isImplicit','sometimes','explodeRules'])
            ->getMock();




        $mockDelegated->expects($this->any())
            ->method('getRules')
            ->willReturn([$attribute=>[$rule]]);

        $mockDelegated->expects($this->any())
            ->method('hasRule')
            ->with($attribute, ValidatorHandler::JSVALIDATION_DISABLE)
            ->willReturn(false);

        $mockDelegated->expects($this->once())
            ->method('parseRule')
            ->with($rule)
            ->willReturn(['ActiveUrl',['token',false,false]]);



        $mockRule = $this->getMockBuilder('Opbol\JsValidation\Javascript\RuleParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();

        $mockRule->expects($this->once())
            ->method('getRule')
            ->with($attribute, 'ActiveUrl', ['token',false,false])
            ->willReturn([$attribute, RuleParser::REMOTE_RULE, ['token',false,false]]);

        $mockMessages = $this->getMockBuilder('Opbol\JsValidation\Javascript\MessageParser')
            ->setConstructorArgs([$mockDelegated] )
            ->getMock();




        $handler = new ValidatorHandler($mockRule, $mockMessages);
        $handler->setDelegatedValidator($mockDelegated);
        $handler->setRemote(false);
        //$handler->sometimes($attribute, $rule);

        $data = $handler->validationData();
        $expected = [
            'rules' => array(),
            'messages' =>  array(),
        ];

        $this->assertEquals($expected, $data);
    }


}
