<?php

namespace luya\forms\blocks;

use luya\cms\base\PhpBlock;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;
use Yii;

/**
 * DatePicker using HTML type "date"
 * 
 * The date (value) is always formatted according to ISO8601
 * 
 * @since 1.3.0
 * @author Basil Suter <git@nadar.io>
 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/date
 */
class DatepickerBlock extends PhpBlock
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
        return Yii::t('forms', 'Datepicker');
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'date_range';
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">'.Yii::t('forms', 'Datepicker').'</span></div>';
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

        return $activeField->textInput(['type' => 'date']);
    }
}