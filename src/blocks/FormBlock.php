<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\cms\injectors\ActiveQuerySelectInjector;
use luya\forms\blockgroups\FormGroup;
use luya\forms\models\Form;

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

    public $previewButtonsTemplate = '{{back}} | {{submit}}';

    public function setup()
    {
        Yii::$app->forms->startForm(Yii::$app->forms->activeFormClass::begin());
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
        return Yii::t('forms', 'Form');
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
                ['var' => 'submitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_submitButtonLabel')],
                ['var' => 'previewSubmitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewSubmitButtonLabel')],
                ['var' => 'previewBackButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewBackButtonLabel')],
                ['var' => 'previewButtonsTemplate', 'type' => self::TYPE_TEXTAREA, 'label' => Yii::t('forms', 'form_label_previewButtonsTemplate'), 'placeholder' => $this->previewButtonsTemplate],
            ],
            'placeholders' => [
                ['var' => 'content', 'label' => Yii::t('forms', 'Form')],
                ['var' => 'preview', 'label' => Yii::t('forms', 'Preview')],
                ['var' => 'success', 'label' => Yii::t('forms', 'Success')],
            ],
        ];
    }

    public function extraVars()
    {
        return [
            'getModels' => $this->getModels(),
            'review' => $this->review,
            'isSubmit' => $this->isSubmit(),
        ];
    }

    public function isSubmit()
    {
        $isSubmit = Yii::$app->request->get('submit', false);

        return $isSubmit && $isSubmit == $this->getVarValue('formId');
    }

    public function getModels()
    {
        $isSubmit = $this->isSubmit();
        $data = Yii::$app->forms->getFormData();
        if ($isSubmit && !empty($data)) {
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

        if (Yii::$app->forms->loadModel()) {
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
