<?php

namespace luya\forms;

use luya\base\DynamicModel;
use luya\Exception;
use luya\forms\models\Form;
use luya\forms\models\Submission;
use luya\forms\models\SubmissionValue;
use luya\helpers\ArrayHelper;
use luya\helpers\StringHelper;
use Yii;
use yii\base\Component;
use yii\widgets\ActiveForm;

class Forms extends Component
{
    public $form;

    /**
     * @var FormsModel
     */
    public $model;

    public $sessionFormDataName = '__formData';

    public $activeFormClass = 'yii\widgets\ActiveForm';

    public $submitButtonsOptions = [];

    public $backButtonOptions = [];

    /**
     * A callable which can be used to make customized emails.
     *
     * @var callable The callable recieves the SubmissionEmail class as first and the Forms component as second argument:
     * ```php
     * 'emailMessage' => function(SubmissionEmail $email, Forms $forms) {
     *    // an example to use mailjet instead
     *    Yii::$app->mailer->compose()
     *        ->setTemplate(484590)
     *        ->setVariables([
     *          'html' => $email->getSummaryHtml(),
     *          'intro' => $email->getIntro(),
     *          'outro' => $email->getOutro(),
     *        ])
     *        ->setTo($email->getRecipients())
     *        ->send();
     * }
     * ```
     *
     * If not defined, the Forms component will use the {{luya\components\Mail}} to compose and send a message with a standard template.
     */
    public $emailMessage;

    public $defaultEmailTemplate = '<p>{{intro}}</p>{{summary}}<p>{{outro}}</p>';

    protected function setFormData(array $data)
    {
        Yii::$app->session->set($this->sessionFormDataName, $data);
    }

    public function getFormData()
    {
        return ArrayHelper::typeCast(Yii::$app->session->get($this->sessionFormDataName, []));
    }

    public function setFormAttributeInfo($attribute, $label, $hint, $formatAs)
    {
        $this->model->attributeLabels[$attribute] = $label;
        $this->model->attributeHints[$attribute] = $hint;
        if ($formatAs && !empty($formatAs)) {
            $this->model->formatters[$attribute] = $formatAs;
        }
    }

    public function formDataAttribute($attributeName)
    {
        $data = $this->getFormData();

        return isset($data[$attributeName]) ? $data[$attributeName] : null;
    }

    public function submit(Form $form)
    {
        $model = new Submission();
        $model->form_id = $form->id;
        $model->useragent = Yii::$app->request->userAgent;
        $model->language = Yii::$app->composition->getLangShortCode();
        $model->url = Yii::$app->request->url;
        
        if ($model->save()) {
            foreach ($this->getFormData() as $attribute => $value) {
                if ($this->model->isAttributeInvisible($attribute)) {
                    continue;
                }

                $submissionValue = new SubmissionValue();
                $submissionValue->submission_id = $model->id;
                $submissionValue->attribute = $attribute;
                $submissionValue->label = $this->model->getAttributeLabel($attribute);
                $submissionValue->hint = isset($this->model->attributeHints[$attribute]) ? $this->model->attributeHints[$attribute] : null;
                $submissionValue->value = $this->model->formatAttributeValue($attribute, $value);
                $submissionValue->save();
            }
        }

        $submissionEmail = new SubmissionEmail($model);

        if ($this->emailMessage) {
            call_user_func($this->emailMessage, $submissionEmail, $this);
        } else {
            $mail = Yii::$app->mail
                ->compose(
                    $submissionEmail->getSubject(),
                    StringHelper::template($this->defaultEmailTemplate, [
                        'intro' => $submissionEmail->getIntro(),
                        'outro' => $submissionEmail->getOutro(),
                        'summary' => $submissionEmail->getSummaryHtml()
                    ])
                )
                ->addresses($submissionEmail->getRecipients())
                ->send();

            if (!$mail) {
                throw new Exception(Yii::$app->mail->error);
            }
        }
    }

    private $_isLoaded = false;

    public function loadModel()
    {
        if ($this->_isLoaded) {
            return true;
        }
        
        if (!Yii::$app->request->isPost) {
            return false;
        }

        $this->model->load(Yii::$app->request->post());
        if ($this->model->validate()) {
            $this->setFormData($this->model->attributes);
            $this->_isLoaded = true;
            return true;
        }

        return false;
    }

    public function removeFormData()
    {
        Yii::$app->session->remove($this->sessionFormDataName);
    }

    public function startForm(ActiveForm $form)
    {
        $this->form = $form;
        $this->model = new FormsModel();
    }

    /**
     * Auto configures a gien attribute into the model.
     * 
     * The following steps will be done when using auto configure attribute:
     * 
     * + The $attributeName will be added to the form model with the given $role
     * + When required is enabled, the required rule will be set or not.
     * + If there is already a value from session data, the value will be inject into the model, this is mainly used for preview.
     * + label and hint informations will be assigned
     * + if a specific format type is provided, the formatter will be taken to format the value when previewing or storing the value.
     */
    public function autoConfigureAttribute($attributeName, $rule, $isRequired, $label = null, $hint = null, $formatAs = null)
    {
        $this->addAttribute($attributeName, $rule);

        if ($isRequired) {
            $this->addRule($attributeName, 'required');
        }

        $value = $this->formDataAttribute($attributeName);

        if (!empty($value)) {
            $this->setAttributeValue($attributeName, $value);
        }

        $this->setFormAttributeInfo($attributeName, $label, $hint, $formatAs);
    }

    public function addAttribute($attributeName, $rule = 'safe')
    {
        $this->model->defineAttribute($attributeName);
        $this->addRule($attributeName, $rule);
    }

    private function addRule($attributeName, $rule)
    {
        $this->model->addRule([$attributeName], $rule);
    }

    private function setAttributeValue($attribute, $value)
    {
        $this->model->{$attribute} = $value;
    }
}
