<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\TextBlock;
use luya\forms\Forms;
use luya\forms\tests\BlockTestCase;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class TextBlockTest extends BlockTestCase
{
    public $blockClass = TextBlock::class;

    public function testFrontend()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
        ]);
        $this->assertInstanceOf(ActiveField::class, $this->block->frontend());

        $this->app->forms->form->end();
    }
}