<?php

namespace luya\forms\tests;

use luya\forms\Forms;
use yii\widgets\ActiveForm;

class FormsTest extends BaseTestCase
{
    public function testAttributeConfigure()
    {
        $form = new Forms();
        $form->startForm(ActiveForm::begin());
        $form->form->end();
        $form->autoConfigureAttribute('foo', 'safe', true, 'label', 'hint', 'ntext');
        $form->setAttributeValue('foo', 'value');
        

        $this->assertSame(['foo'], $form->model->attributes());
        $this->assertSame('value', $form->model->foo);
        
        $this->assertFalse($form->loadModel());
        $this->assertEmpty($form->cleanup());
    }

    public function testLoadModel()
    {
        $form = new Forms();
        $form->startForm(ActiveForm::begin());
        $form->form->end();

        $form->autoConfigureAttribute('foo', 'safe', true, 'label', 'hint', 'ntext');

        $this->assertSame('Model', $form->model->formName());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->app->request->setBodyParams([
            'Model' => [
                'foo' => 'value of foo',
            ]
        ]);
        $this->assertTrue($form->loadModel());
        $this->assertTrue($form->loadModel());


        $this->assertSame('value of foo', $form->model->foo);
    }
}