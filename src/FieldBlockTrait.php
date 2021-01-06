<?php

namespace luya\forms;

use luya\cms\helpers\BlockHelper;
use Yii;

/**
 * A trait for form field blocks.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
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
                ['var' => $this->varAttribute, 'label' => Yii::t('forms', 'Attribute'), 'required' => true, 'type' => self::TYPE_TEXT],
                ['var' => $this->varLabel, 'label' => Yii::t('forms', 'Label'), 'required' => true, 'type' => self::TYPE_TEXT],
                ['var' => $this->varHint, 'label' => Yii::t('forms', 'Hint'), 'type' => self::TYPE_TEXTAREA],
                ['var' => $this->varIsRequired, 'label' => Yii::t('forms', 'Required'), 'type' => self::TYPE_CHECKBOX],
                ['var' => $this->varRule, 'label' => Yii::t('forms', 'Validation Rule'), 'required' => true, 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    'safe' => Yii::t('forms', 'Everything allowed'),
                    'string' => Yii::t('forms', 'String'),
                    'number' => Yii::t('forms', 'Number'),
                    'email' => Yii::t('forms', 'Email'),
                    'boolean' => Yii::t('forms', 'Boolean'),
                    'date' => Yii::t('forms', 'Date'),
                 ])],
                ['var' => $this->varFormatAs, 'label' => Yii::t('forms', 'Formatting'), 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    null => Yii::t('forms', 'Automatically'),
                    'boolean' => Yii::t('forms', 'Boolean (Yes/No)'),
                    'date' => Yii::t('forms', 'Date'),
                    'datetime' => Yii::t('forms', 'Date & Time'),
                    'ntext' => Yii::t('forms', 'Multiline Text'),
                    'url' => Yii::t('forms', 'URL/Link'),
                    'image' => Yii::t('forms', 'Image'),
                ])]
            ],
        ];
    }
}
