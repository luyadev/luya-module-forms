<?php

namespace luya\forms;

use luya\base\DynamicModel;
use luya\Exception;
use luya\forms\models\Form;
use luya\forms\models\Submission;
use luya\forms\models\SubmissionValue;
use luya\helpers\ArrayHelper;
use Yii;
use yii\base\Component;
use yii\widgets\ActiveForm;

class Forms extends Component
{
    public $form;

    public $model;

    public $sessionDataName = '__formData';

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
     *        ->setVariables(['html' => $email->getSummaryHtml()])
     *        ->setTo($email->getRecipients())
     *        ->send();
     * }
     * ``` 
     * 
     * If not defined, the Forms component will use the {{luya\components\Mail}} to compose and send a message with a standard template.
     */
    public $emailMessage;

    public function setFormData(array $data)
    {
        Yii::$app->session->set($this->sessionDataName, $data);
    }

    public function getFormData()
    {
        return ArrayHelper::typeCast(Yii::$app->session->get($this->sessionDataName, []));
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
            foreach ($this->getFormData() as $label => $value) {
                $submissionValue = new SubmissionValue();
                $submissionValue->submission_id = $model->id;
                $submissionValue->attribute = $label;
                $submissionValue->label = $label;
                $submissionValue->value = $value;
                $submissionValue->save();
            }
        }

        $submissionEmail = new SubmissionEmail($model);

        if ($this->emailMessage) {
            call_user_func($this->emailMessage, $submissionEmail, $this);
        } else {
            $mail = Yii::$app->mail
                ->compose($submissionEmail->getSubject(), $submissionEmail->getSummaryHtml())
                ->addresses($submissionEmail->getRecipients())
                ->send();

            if (!$mail) {
                throw new Exception(Yii::$app->mail->error);
            }
        }
    }

    public function removeFormData()
    {
        Yii::$app->session->remove($this->sessionDataName);
    }

    public function startForm(ActiveForm $form)
    {
        $this->form = $form;
        $this->model = new DynamicModel();
    }

    public function autoConfigureAttribute($attributeName, $rule, $isRequired)
    {
        $this->addAttribute($attributeName, $rule);

        if ($isRequired) {
            $this->addRule($attributeName, 'required');
        }

        $value = $this->formDataAttribute($attributeName);

        if (!empty($value)) {
            $this->attributeValue($attributeName, $value);
        }
    }

    public function addAttribute($attributeName, $rule = 'safe')
    {
        $this->model->defineAttribute($attributeName);
        $this->addRule($attributeName, $rule);
    }

    public function addRule($attributeName, $rule)
    {
        $this->model->addRule([$attributeName], $rule);
    }

    public function attributeValue($attribute, $value)
    {
        $this->model->{$attribute} = $value;
    }
}