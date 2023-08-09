<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\cms\helpers\BlockHelper;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;
use luya\helpers\ArrayHelper;

/**
 * Text Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class SelectBlock extends PhpBlock
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
        return Yii::t('forms', 'Dropdown/Radio Buttons');
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'arrow_drop_down_circle';
    }

    public function config()
    {
        return ArrayHelper::merge([
            'vars' => [
                [
                    'var' => 'type',
                    'label' => Yii::t('forms', 'Type'),
                    'type' => self::TYPE_RADIO,
                    'options' => BlockHelper::radioArrayOption([
                        1 => Yii::t('forms', 'Dropdown'),
                        2 => Yii::t('forms', 'Radio Buttons'),
                    ])
                ],
                [
                    'var' => 'values',
                    'label' => Yii::t('forms', 'Values'),
                    'type' => self::TYPE_LIST_ARRAY
                ]
            ],
            'cfgs' => [
                [
                    'var' => 'separator',
                    'label' => Yii::t('forms', 'Separator'),
                    'type' => self::TYPE_TEXT,
                    'placeholder' => '\n',

                ]
            ]
        ], $this->parentConfig());
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldHelp()
    {
        return [
            'separator' => Yii::t('forms', 'The HTML code that separates items. By default, it is a newline, use &nbsp; to separate items with spaces.')
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">'.Yii::t('forms', 'Dropdown/Radio Buttons').'</span></div>';
    }

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

        $values = ArrayHelper::combine(ArrayHelper::getColumn($this->getVarValue('values', []), 'value'));

        return $this->getVarValue('type') == 1 ? $activeField->dropDownList($values, ['prompt' => '-']) : $activeField->radioList($values, [
            'separator' => $this->getCfgValue('separator', "\n")
        ]);
    }
}
