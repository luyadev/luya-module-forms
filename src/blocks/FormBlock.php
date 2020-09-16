<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\cms\frontend\blockgroups\ProjectGroup;
use luya\cms\helpers\BlockHelper;
use luya\forms\blockgroups\FormGroup;
use luya\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Form Block.
 *
 * File has been created with `block/create` command. 
 */
class FormBlock extends PhpBlock
{
    /**
     * @var string The module where this block belongs to in order to find the view files.
     */
    public $module = 'forms';

    /**
     * @var boolean Choose whether block is a layout/container/segmnet/section block or not, Container elements will be optically displayed
     * in a different way for a better user experience. Container block will not display isDirty colorizing.
     */
    public $isContainer = true;

    public $review = false;

    public function setup()
    {
        Yii::$app->forms->startForm(ActiveForm::begin());


        $formData = Yii::$app->session->get('formData', []);
        
        if (!empty($formData)) {
            Yii::$app->forms->model->attributes = ArrayHelper::typeCast($formData);
            Yii::$app->forms->model->validate();
        }
    }

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
        return 'Form';
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'extension'; // see the list of icons on: https://design.google.com/icons/
    }
 
    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                 ['var' => 'formId', 'label' => 'Form', 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([1 => 'Label for 1'])],
            ],
            'placeholders' => [
                 ['var' => 'content', 'label' => 'Inhalt'],
            ],
        ];
    }

    public function extraVars()
    {
        return [
            'getModels' => $this->getModels(),
            'review' => $this->review,
        ];
    }

    public function getModels()
    {
        $isSubmit = Yii::$app->request->get('submit', false);
        $data = Yii::$app->session->get('formData');
        if ($isSubmit && !empty($data)) {
            Yii::$app->forms->model->attributes = $data;
            if (Yii::$app->forms->model->validate()) {
                // store values as submission

                Yii::$app->session->remove('formData');

                Yii::$app->session->setFlash('formDataSuccess');
                Yii::$app->response->refresh();
                
                return Yii::$app->end();
            }
        }
        if (Yii::$app->forms->model->load(Yii::$app->request->post()) && Yii::$app->forms->model->validate()) {
            Yii::$app->session->set('formData', Yii::$app->forms->model->attributes);
            $this->review = true;
        }
    }

    /**
     * {@inheritDoc} 
     *
     * @param {{placeholders.content}}
     * @param {{vars.formId}}
    */
    public function admin()
    {
        return '<h1>FORM</h1><p>{{vars.formId}}</p>';
    }
}