<?php

namespace luya\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use luya\Exception;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FormsModel;
use luya\forms\models\Form;
use yii\base\InvalidConfigException;

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

    public $previewButtonsTemplate = '<div>{{back}}<span> | </span>{{submit}}</div>';

    /**
     * {@inheritDoc}
     */
    public function setup()
    {
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
                    'label' => 'Form',
                    'type' => self::TYPE_SELECT_CRUD,
                    'required' => true,
                    'options' => ['route' => 'forms/form/index', 'api' => 'admin/api-forms-form', 'fields' => ['title']]
                ]
            ],
            'cfgs' => [
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

    public function extraVars()
    {
        return [
            'getModels' => $this->getModels(),
            'review' => $this->isReview(),
            'isSubmit' => $this->isSubmit(),
        ];
    }

    public function isSubmit()
    {
        $isSubmit = Yii::$app->request->get('submit', false);

        return $isSubmit && $isSubmit == $this->getVarValue('formId');
    }

    public function isReview()
    {
        return Yii::$app->forms->loadModel();
    }

    public function getModels()
    {
        $data = Yii::$app->forms->getFormData();
        if ($this->isSubmit() && !empty($data)) {
            /** @var FormsModel $model */
            $model = Yii::$app->forms->model;
            $model->attributes = $data;
            // invisible attributes should not be validate in the second validation step.
            if ($model->validate($model->getAttributesWithoutInvisible())) {
                
                if (!Yii::$app->forms->submit(Form::findOne($this->getVarValue('formId')))) {
                    throw new Exception("Error while saving the form data, please try again later.");
                }

                Yii::$app->forms->removeFormData();
                // set flash, redirect and end app
                Yii::$app->session->setFlash('formDataSuccess');
                Yii::$app->response->redirect(Yii::$app->menu->current->link);
                
                return Yii::$app->end();
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
