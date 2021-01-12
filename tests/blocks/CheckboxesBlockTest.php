<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\CheckboxesBlock;
use luya\forms\tests\BlockTestCase;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class CheckboxesBlockTest extends BlockTestCase
{
    public $blockClass = CheckboxesBlock::class;

    public function testFrontend()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
            'values' => [],
        ]);
        $this->assertInstanceOf(ActiveField::class, $this->renderFrontend());
        $this->assertNotEmpty($this->block->admin());
        $this->assertNotEmpty($this->block->icon());
        $this->assertNotEmpty($this->block->name());
        $this->assertNotEmpty($this->block->blockGroup());
        $this->assertNotEmpty($this->block->config());

        $this->app->forms->form->end();
    }
}
