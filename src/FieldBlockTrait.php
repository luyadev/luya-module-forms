<?php

namespace luya\forms;

use luya\cms\helpers\BlockHelper;

trait FieldBlockTrait
{
    public $defaultRule = 'safe';

    public $varAttribute = 'attribute';

    public $varLabel = 'label';

    public $varHint = 'hint';

    public $varRule = 'rule';

    public $varIsRequired = 'isRequired';

    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                 ['var' => $this->varAttribute, 'label' => 'Attribute', 'type' => self::TYPE_TEXT],
                 ['var' => $this->varLabel, 'label' => 'Label', 'type' => self::TYPE_TEXT],
                 ['var' => $this->varHint, 'label' => 'Hint', 'type' => self::TYPE_TEXTAREA],
                 ['var' => $this->varRule, 'label' => 'Validation Regel', 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                     'safe' => 'Alles (Safe)',
                     'string' => 'String',
                     'number' => 'Number',
                     'integer' => 'Integer',
                     'email' => 'Email',
                 ])],
                 ['var' => $this->varIsRequired, 'label' => 'Required', 'type' => self::TYPE_CHECKBOX],
            ],
        ];
    }
}