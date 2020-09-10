<?php

namespace luya\forms;

use luya\cms\helpers\BlockHelper;

trait FieldBlockTrait
{
    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                 ['var' => 'attribute', 'label' => 'Attribute', 'type' => self::TYPE_TEXT],
                 ['var' => 'label', 'label' => 'Label', 'type' => self::TYPE_TEXT],
                 ['var' => 'hint', 'label' => 'Hint', 'type' => self::TYPE_TEXTAREA],
                 ['var' => 'rule', 'label' => 'Validation Regel', 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                     'safe' => 'Alles (Safe)',
                     'string' => 'String',
                     'number' => 'Number',
                     'integer' => 'Integer',
                     'email' => 'Email',
                 ])],
                 ['var' => 'isRequired', 'label' => 'Required', 'type' => self::TYPE_CHECKBOX],
            ],
        ];
    }
}