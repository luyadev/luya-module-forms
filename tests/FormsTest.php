<?php

namespace luya\forms\tests;

use luya\forms\Forms;
use luya\forms\models\Form;
use luya\forms\models\Submission;
use luya\forms\models\SubmissionValue;
use luya\testsuite\fixtures\NgRestModelFixture;
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

    public function testLoadModelAndSave()
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

        // save test

        new NgRestModelFixture([
            'modelClass' => Form::class,
            'fixtureData' => [
                1 => [
                    'id' => 1,
                    'title' => '{"de":"Titel", "en":"Title"}',
                    'subject' => '{"de":"Subject", "en":"Subject"}',
                    'recipients' => '[{"value":"john@luya.io"},{"value":"foobar@luya.io"}]'
                ]
            ]
        ]);
        $this->createAdminLangFixture([
            1 => [
                'id' => 1,
                'name' => 'en',
                'short_code' => 'en',
                'is_deleted' => 0,
                'is_default' => 1,
            ]
        ]);

        new NgRestModelFixture([
            'modelClass' => SubmissionValue::class,
            'fixtureData' => [
                1 => [
                    'id' => 1,
                    'submission_id' => 1,
                    'attribute' => 'xyz',
                    'label' => 'XYZ',
                    'value' => 'value',
                ],
                2 => [
                    'id' => 2,
                    'submission_id' => 1,
                    'attribute' => 'xyz',
                    'label' => 'XYZ',
                    'value' => 'value',
                ],
            ]
        ]);

        new NgRestModelFixture([
            'modelClass' => Submission::class,
            'fixtureData' => [
                1 => [
                    'id' => 1,
                    'form_id' => 1,
                ]
            ]
        ]);
        $this->createAdminNgRestLogFixture();

        $this->expectException('luya\Exception');
        $this->assertTrue($form->save(Form::findOne(1)));
    }
}
