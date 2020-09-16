<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\cms\frontend\blockgroups\ProjectGroup;
use luya\cms\helpers\BlockHelper;
use luya\cms\injectors\ActiveQuerySelectInjector;
use luya\forms\blockgroups\FormGroup;
use luya\forms\models\Form;
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
        Yii::$app->forms->startForm(Yii::$app->forms->activeFormClass::begin());


        $formData = Yii::$app->forms->getFormData();
        
        if (!empty($formData)) {
            Yii::$app->forms->model->attributes = $formData;
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
        return 'dynamic_form'; // see the list of icons on: https://design.google.com/icons/
    }

    public function injectors()
    {
        return [
            'formId' => new ActiveQuerySelectInjector([
                'query' => Form::find(),
                'label' => 'title',
                'type' => self::INJECTOR_VAR,
                'varLabel' => 'Form',
            ])
        ];
    }
 
    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'cfgs' => [
                ['var' => 'submitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => 'Weiter Button Vorschau Label'],
                ['var' => 'previewSubmitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => 'Weiter Button Formular'],
                ['var' => 'previewBackButtonLabel', 'type' => self::TYPE_TEXT, 'label' => 'Zurück Button Vorschau Label'],
            ],
            'placeholders' => [
                 ['var' => 'content', 'label' => 'Form'],
                 ['var' => 'success', 'label' => 'Success'],
                 ['var' => 'preview', 'label' => 'Preview'],
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
        $data = Yii::$app->forms->getFormData();
        if ($isSubmit && $isSubmit == $this->getVarValue('formId') && !empty($data)) {
            Yii::$app->forms->model->attributes = $data;
            if (Yii::$app->forms->model->validate()) {

                Yii::$app->forms->submit(Form::findOne($this->getVarValue('formId')));
                Yii::$app->forms->removeFormData();
                // set flash, redirect and end app
                Yii::$app->session->setFlash('formDataSuccess');
                Yii::$app->response->refresh();
                
                return Yii::$app->end();
            }
        }
        if (Yii::$app->forms->model->load(Yii::$app->request->post()) && Yii::$app->forms->model->validate()) {
            Yii::$app->forms->setFormData(Yii::$app->forms->model->attributes);
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
        return;
    }
}