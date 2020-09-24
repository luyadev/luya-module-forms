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
 * File has been created with `block/create` command. 
 */
class CheckboxesBlock extends PhpBlock
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
        return 'Checkboxes';
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'radio_button_checked';
    }

    public function config()
    {
        return ArrayHelper::merge([
            'vars' => [
                [
                    'var' => 'values',
                    'label' => 'Values',
                    'type' => self::TYPE_LIST_ARRAY
                ]
            ],
        ], $this->parentConfig());
    }

    public function getFieldHelp()
    {
        return [
            'values' => 'Wenn nur ein Wert gesetzt wird, wird eine "einfache" checkbox erstellt.',
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">Checkboxes</span></div>';
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

        $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $this->getVarValue($this->varAttribute));

        $values = ArrayHelper::combine(ArrayHelper::getColumn($this->getVarValue('values'), 'value'));

        $options = [];

        // if required, when not checked the submited value should be empty otherwise the required validator will not
        // catch the value `0` as required.
        if ($this->varIsRequired) {
            $options['uncheck'] = null;
        }

        return count($values) > 1 ? $activeField->checkboxList($values) : $activeField->checkbox($options);
    }
}