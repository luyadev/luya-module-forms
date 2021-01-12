<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\DatepickerBlock;
use luya\forms\tests\BlockTestCase;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class DatepickerBlockTest extends BlockTestCase
{
    public $blockClass = DatepickerBlock::class;

    public function testFrontend()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
        ]);
        $this->assertInstanceOf(ActiveField::class, $this->renderFrontend());
        $this->assertNotEmpty($this->block->admin());
        $this->assertNotEmpty($this->block->icon());
        $this->assertNotEmpty($this->block->name());
        $this->assertNotEmpty($this->block->blockGroup());
        $this->assertNotEmpty($this->block->config());

        $this->app->forms->form->end();
    }

    public function testMultiRows()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
            'isTextarea' => 1,
        ]);
        $this->block->setCfgValues([
            'textareaRows' => 5,
        ]);
        $this->assertStringContainsString('<input type="date"', $this->block->frontend()->__toString());

        $this->app->forms->form->end();
    }
}
