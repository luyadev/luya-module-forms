<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\cms\frontend\blockgroups\ProjectGroup;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;

/**
 * Text Block.
 *
 * File has been created with `block/create` command. 
 */
class TextBlock extends PhpBlock
{
    use FieldBlockTrait;
    
    /**
     * @var string The module where this block belongs to in order to find the view files.
     */
    public $module = 'forms';

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return FormGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Text';
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'message'; // see the list of icons on: https://design.google.com/icons/
    }
    
    /**
     * {@inheritDoc} 
     *
     * @param {{vars.field}}
     * @param {{vars.hint}}
     * @param {{vars.label}}
    */
    public function admin()
    {
        return '<p>{{vars.label}}</p>';
    }
}