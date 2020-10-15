<?php

namespace luya\forms;

use luya\cms\helpers\BlockHelper;
use Yii;

trait FieldBlockTrait
{
    public $defaultRule = 'safe';

    public $varAttribute = 'attribute';

    public $varLabel = 'label';

    public $varHint = 'hint';

    public $varRule = 'rule';

    public $varIsRequired = 'isRequired';

    public $varFormatAs = 'formatAs';

    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                ['var' => $this->varAttribute, 'label' => Yii::t('forms', 'Attribute'), 'type' => self::TYPE_TEXT],
                ['var' => $this->varLabel, 'label' => Yii::t('forms', 'Label'), 'type' => self::TYPE_TEXT],
                ['var' => $this->varHint, 'label' => Yii::t('forms', 'Hint'), 'type' => self::TYPE_TEXTAREA],
                ['var' => $this->varRule, 'label' => Yii::t('forms', 'Validation Rule'), 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    'safe' => 'Alles (Safe)',
                    'string' => 'String',
                    'number' => 'Number',
                    'email' => 'Email',
                    'boolean' => 'Boolean',
                 ])],
                ['var' => $this->varIsRequired, 'label' => Yii::t('forms', 'Required'), 'type' => self::TYPE_CHECKBOX],
                ['var' => $this->varFormatAs, 'label' => Yii::t('forms', 'Formatting'), 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    null => 'Automatisch',
                    'boolean' => 'Boolean (Ja/Nein)',
                ])]
            ],
        ];
    }
}
