<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;
use luya\helpers\ArrayHelper;

/**
 * Text Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class TextBlock extends PhpBlock
{
    use FieldBlockTrait { config as parentConfig; }

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
        return Yii::t('forms', 'Text');
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'message';
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        return ArrayHelper::merge($this->parentConfig(), [
            'vars' => [
                ['var' => 'isTextarea', 'label' => Yii::t('forms', 'Multiline Input'), 'type' => self::TYPE_CHECKBOX],
            ],
            'cfgs' => [
                ['var' => 'textareaRows', 'label' => Yii::t('forms', 'Multiline Rows'), 'type' => self::TYPE_NUMBER],
                ['var' => 'hiddenInputValue', 'label' => Yii::t('forms', 'As Hidden Input Value'), 'type' => self::TYPE_TEXT],
            ]
        ]);
    }

    public function getFieldHelp()
    {
        return [
            'hiddenInputValue' => Yii::t('forms', 'block_hiddenInputValue_help'),
        ];
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">'.Yii::t('forms', 'Text').'</span></div>';
    }

    /**
     * {@inheritDoc}
     */
    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            $this->getVarValue($this->varRule, $this->defaultRule),
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint),
            $this->getVarValue($this->varFormatAs)
        );

        $varName = $this->getVarValue($this->varAttribute);
        if (!$varName) {
            return;
        }

        $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName);

        $hiddenInputValue = $this->getCfgValue('hiddenInputValue');

        if (!empty($hiddenInputValue)) {
            return $activeField->hiddenInput(['value' => $hiddenInputValue])->label(false);
        }

        return $this->getVarValue('isTextarea') ? $activeField->textArea(['rows' => $this->getCfgValue('textareaRows', null)]) : $activeField->textInput();
    }
}
