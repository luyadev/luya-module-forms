<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
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
        return 'message';
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
        return '<p>Text {{vars.label}}</p>';
    }

    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute($this->getVarValue('attribute'), $this->getVarValue('rule', 'safe'), $this->getVarValue('isRequired'));

        return Yii::$app->forms->form->field(Yii::$app->forms->model, $this->getVarValue('attribute'))
            ->textInput()
            ->label($this->getVarValue('label'))
            ->hint($this->getVarValue('hint'));
    }
}