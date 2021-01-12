<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\SummaryBlock;
use luya\forms\tests\BlockTestCase;
use yii\widgets\ActiveForm;

class SummaryBlockTest extends BlockTestCase
{
    public $blockClass = SummaryBlock::class;

    public function testFrontend()
    {
        $this->app->forms->startForm(ActiveForm::begin());
        $this->block->setVarValues([
            'attribute' => 'firstname',
            'values' => [],
        ]);
        $this->assertEmpty($this->renderFrontend());
        $this->assertNotEmpty($this->block->admin());
        $this->assertNotEmpty($this->block->icon());
        $this->assertNotEmpty($this->block->name());
        $this->assertNotEmpty($this->block->blockGroup());
        $this->assertNotEmpty($this->block->config());

        $this->app->forms->form->end();
    }
}
