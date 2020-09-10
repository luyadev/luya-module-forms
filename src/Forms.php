<?php

namespace luya\forms;

use luya\base\DynamicModel;
use yii\base\Component;
use yii\widgets\ActiveForm;

class Forms extends Component
{
    public $form;

    public $model;

    public function startForm(ActiveForm $form)
    {
        $this->form = $form;
        $this->model = new DynamicModel();
    }


}