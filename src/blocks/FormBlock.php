<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\Exception;
use luya\forms\blockgroups\FormGroup;
use luya\forms\Model;
use luya\forms\models\Form;

/**
 * Form Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
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

    public $previewButtonsTemplate = '<div class="forms-preview-buttons-container">{{back}}<span class="forms-divider"> | </span>{{submit}}</div>';

    /**
     * {@inheritDoc}
     */
    public function setup()
    {
        Yii::debug('from block setup invocation', __METHOD__);
        $object = Yii::$app->forms->activeFormClass;
        Yii::$app->forms->startForm($object::begin(Yii::$app->forms->activeFormClassOptions));
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
 
    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'formId',
                    'label' => Yii::t('forms', 'Form'),
                    'type' => self::TYPE_SELECT_CRUD,
                    'required' => true,
                    'options' => ['route' => 'forms/form/index', 'api' => 'admin/api-forms-form', 'fields' => ['title']]
                ],
                [
                    'var' => 'confirmStep',
                    'label' => Yii::t('forms', 'Confirmation Step'),
                    'type' => self::TYPE_CHECKBOX,
                ],
            ],
            'cfgs' => [
                [
                    'var' => 'doNotSaveData',
                    'label' => Yii::t('forms', 'Do not save data'),
                    'type' => self::TYPE_CHECKBOX,
                ],
                ['var' => 'submitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_submitButtonLabel'), 'placeholder' => Yii::t('forms', 'Submit')],
                ['var' => 'previewSubmitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewSubmitButtonLabel'), 'placeholder' => Yii::t('forms', 'Submit')],
                ['var' => 'previewBackButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewBackButtonLabel'), 'placeholder' => Yii::t('forms', 'Back')],
                ['var' => 'previewButtonsTemplate', 'type' => self::TYPE_TEXTAREA, 'label' => Yii::t('forms', 'form_label_previewButtonsTemplate'), 'placeholder' => $this->previewButtonsTemplate],
            ],
            'placeholders' => [
                ['var' => 'content', 'label' => Yii::t('forms', 'Form')],
                ['var' => 'preview', 'label' => Yii::t('forms', 'Preview')],
                ['var' => 'success', 'label' => Yii::t('forms', 'Success')],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function extraVars()
    {
        return [
            'isSubmit' => $this->isSubmit(),
            'invokeSubmitAndStore' => $this->submitAndStore(),
            'isPreview' => $this->getVarValue('confirmStep') && $this->isLoadedValidModel(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFieldHelp()
    {
        return [
            'formId' => Yii::t('forms', 'Select the formular configuraton (defining mail and other settings) from the database or create a new one.'),
            'confirmStep' => Yii::t('forms', 'When enabled, the user will be presented with a summary before submitting the form data.'),
            'doNotSaveData' => Yii::t('forms', 'If enabled, the form data will not be stored in the database, but only sent by e-mail to the specified recipients.'),
        ];
    }

    /**
     * Check submit state based on different scenarios
     *
     * @return boolean Whether the form is in submited state or not
     */
    public function isSubmit()
    {
        // when confirmm step is disabled, but review is loaded, this is equals to a submit:
        if (!$this->getVarValue('confirmStep') && $this->isLoadedValidModel()) {
            return true;
        }

        $isSubmit = Yii::$app->request->get('submit', false);

        return $isSubmit && $isSubmit == $this->getVarValue('formId');
    }

    /**
     * Load model data and validate
     *
     * @return boolean Whether the model data is loaded and validated
     */
    public function isLoadedValidModel()
    {
        return Yii::$app->forms->loadModel();
    }

    /**
     * Invokes the model submiting process and redirects the browsers if needed
     *
     * @return void
     */
    public function submitAndStore()
    {
        if ($this->isSubmit()) {
            // the data is only available if the isSubmit call was running, therefore for
            // first check for is submit
            // second get data from session
            $data = Yii::$app->forms->getFormData();
            if (!empty($data)) {
                /** @var Model $model */
                $model = Yii::$app->forms->model;
                $model->attributes = $data;
                // invisible attributes should not be validate in the second validation step.
                if (Yii::$app->forms->isModelValidated || $model->validate($model->getAttributesWithoutInvisible())) {
                    if (!Yii::$app->forms->save(Form::findOne($this->getVarValue('formId')), $this->getCfgValue('doNotSaveData', false))) {
                        throw new Exception("Error while saving the form data, please try again later.");
                    }

                    Yii::$app->forms->cleanup();
                    // set flash, redirect and end app
                    Yii::$app->session->setFlash('formDataSuccess');
                    Yii::$app->response->redirect(Yii::$app->menu->current->link);
                    
                    return Yii::$app->end();
                }
            }
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
