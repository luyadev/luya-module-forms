<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\FormBlock;
use luya\forms\tests\BaseTestCase;

class FormBlockTest extends BaseTestCase
{
    public function testSetup()
    {
        $block = new FormBlock();
        $block->setVarValues(['formId' => 1, 'confirmStep' => 1]);
        $block->setup();

        $response = $block->renderFrontend();

        $this->assertStringContainsString('<form', $response);

        $this->app->forms->cleanup();

        $this->assertFalse($block->isLoadedValidModel());
        $this->assertFalse($block->isSubmit());
        $this->assertSame([
            'isSubmit' => false,
            'invokeSubmitAndStore' => null,
            'isPreview' => false,
        ], $block->extraVars());
    }
}
