<?php

namespace luya\forms\tests\blocks;

use luya\forms\blocks\FormBlock;
use luya\forms\tests\BaseTestCase;

class FormBlockTest extends BaseTestCase
{
    public function testSetup()
    {
        $block = new FormBlock();
        $block->setup();

        $response = $block->frontend();

        $this->assertContains('<form', $response);
    }
}