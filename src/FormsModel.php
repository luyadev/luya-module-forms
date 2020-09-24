<?php

namespace luya\forms;

use luya\base\DynamicModel;
use Yii;

class FormsModel extends DynamicModel
{
    public $formId;

    public $formatters = [];

    public function formatAttributeValue($attribute, $value)
    {
        $value = is_array($value) ? implode(", ", $value) : Yii::$app->formatter->autoFormat($value);

        if (isset($this->formatters[$attribute]) && !empty($this->formatters[$attribute])) {
            return Yii::$app->formatter->format($value, $this->formatters[$attribute]);
        }

        return Yii::$app->formatter->autoFormat($value);
    }
}