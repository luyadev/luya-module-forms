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

        

        $this->assertSame(['foo'], $form->model->attributes());
    }
}