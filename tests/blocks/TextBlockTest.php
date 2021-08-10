<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\TextBlock;
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
        $this->assertStringContainsString('rows="5"></textarea>', $this->block->frontend()->__toString());

        $this->app->forms->form->end();
    }

    public function testHiddenInput()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
            'isTextarea' => 1,
        ]);
        $this->block->setCfgValues([
            'hiddenInputValue' => 'hiddenvalue',
        ]);
        $this->assertStringContainsString('type="hidden"', $this->block->frontend()->__toString());
        $this->assertStringContainsString('value="hiddenvalue"', $this->block->frontend()->__toString());

        $this->app->forms->form->end();
    }
}
