<?php

namespace luya\forms;

use luya\Exception;
use luya\forms\models\Form;
use luya\forms\models\Submission;
use luya\forms\models\SubmissionValue;
use luya\helpers\ArrayHelper;
use luya\helpers\StringHelper;
use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\widgets\ActiveForm;

/**
 * Forms Component
 *
 * @property ActiveForm $form
 * @property Model $model
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class Forms extends Component
{
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var string The session variable name
     */
    public $sessionFormDataName = '__formData';

    /**
     * @var string The Active Form class, for configurations options see {{$activeFormClassOptions}}.
     */
    public $activeFormClass = 'yii\widgets\ActiveForm';

    /**
     * @var array A configuration array which will be passed to ActiveForm::begin($options). Example usage `['enableClientValidation' => false]`
     */
    public $activeFormClassOptions = [];

    /**
     * @var array An array of options which will be passed to {{ Html::submitButton(..., $options)}} submit buttons.
     */
    public $submitButtonsOptions = [];

    /**
     * @var array An array of options which will be passed to {{ Html::submitButton(..., $options)}} back buttons.
     */
    public $backButtonOptions = [];

    /**
     * A callable which can be used to make customized emails.
     *
     * @var callable The callable recieves the SubmissionEmail class as first and the Forms component as second argument:
     * ```php
     * 'emailMessage' => function(SubmissionEmail $email, Forms $forms) {
     *    // an example to use mailjet instead
     *    return Yii::$app->mailer->compose()
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
     *
     * The return value must indicate whether sending was successfull or not
     */
    public $emailMessage;

    /**
     * @var boolean Indicates whether the current model has been loaded or not. This does not say anything about whether loading was successfull
     * or not.
     * @since 1.3.0
     */
    public $isModelLoaded = false;

    /**
     * @var boolean Indicates whether the curent model is loaded AND sucessfull validated.
     * @since 1.4.2
     */
    public $isModelValidated = false;

    /**
     * @var ActiveForm
     */
    private $_form;

    /**
     * @var Model
     */
    private $_model;

    /**
     * @var string The email email which will be taken to generate the default message.
     */
    public $defaultEmailTemplate = '<p>{{intro}}</p>{{summary}}<p>{{outro}}</p>';

    /**
     * Initialize the form and the model
     *
     * @param ActiveForm $form
     */
    public function startForm(ActiveForm $form)
    {
        $this->_form = $form;
        $this->_model = new Model();
    }

    /**
     * Active Form Getter
     *
     * @return ActiveForm
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * Model Getter
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * Clean up the session and destroy model and form
     */
    public function cleanup()
    {
        Yii::$app->session->remove($this->sessionFormDataName);
        $this->_model = null;
        $this->_form = null;
        $this->isModelValidated = false;
        $this->isModelLoaded = false;
    }

    /**
     * Loads the data from the post request into the model, validates it and stores the data in the session.
     *
     * @return boolean Whether loading the model with data was successfull or not (if not a validation error may persists in the $model).
     */
    public function loadModel()
    {
        Yii::debug('load and validate model', __METHOD__);
        if ($this->isModelValidated) {
            return true;
        }
        
        if (!Yii::$app->request->isPost || !$this->model) {
            return false;
        }

        $this->isModelLoaded = $this->model->load(Yii::$app->request->post());

        if ($this->isModelLoaded && $this->model->validate()) {
            Yii::$app->session->set($this->sessionFormDataName, $this->model->attributes);
            Yii::debug('successfull loaded and validated model', __METHOD__);
            $this->isModelValidated = true;
            return true;
        }

        return false;
    }

    /**
     * Get all form values which are stored trough {{loadModel()}}.
     *
     * @return array An array with attribute name and value
     */
    public function getFormData()
    {
        return ArrayHelper::typeCast(Yii::$app->session->get($this->sessionFormDataName, []));
    }

    /**
     * Get the attribute value for a given value from the post data
     *
     * @param string $attribute
     * @return mixed
     * @since 1.3.0
     */
    public function postAttributeValue($attribute)
    {
        if (!Yii::$app->request->isPost) {
            return [];
        }

        $data = Yii::$app->request->post($this->model->formName(), []);
        
        if (!is_array($data)) {
            return null;
        }

        return array_key_exists($attribute, $data) ? $data[$attribute] : null;
    }

    /**
     * Return the value for a given attribute form the form data
     *
     * @param string $attributeName
     * @return mixed
     */
    public function getFormDataAttributeValue($attributeName)
    {
        $data = $this->getFormData();

        $value = isset($data[$attributeName]) ? $data[$attributeName] : null;

        // the value is empty and the form is not yet loaded
        // lets try to extract the values from the post data for now
        // because the model loading can only work when all attributes are stored
        // which is after the form attributes are defined!
        if (empty($value) && !$this->isModelLoaded) {
            return $this->postAttributeValue($attributeName);
        }

        return $value;
    }

    /**
     * Submit and Save the form and store the data
     *
     * @param Form $form
     * @param boolean $doNotSave Whether the form should really be saved, this can be enabled when sensitiv informations should not be stored
     * and just sent by email instead.
     * @return boolean Whether sending was succesful or not
     */
    public function save(Form $form, $doNotSave = false)
    {
        $model = new Submission();
        $model->form_id = $form->id;
        $model->useragent = Yii::$app->request->userAgent;
        $model->language = Yii::$app->composition->getLangShortCode();
        $model->url = Yii::$app->request->url;
        
        // error while saving
        if (!$model->save()) {
            return false;
        }

        foreach ($this->getFormData() as $attribute => $value) {
            if ($this->model->isAttributeInvisible($attribute)) {
                continue;
            }

            $submissionValue = new SubmissionValue();
            $submissionValue->submission_id = $model->id;
            $submissionValue->attribute = $attribute;
            $submissionValue->label = $this->model->getAttributeLabel($attribute);
            $submissionValue->hint = isset($this->model->attributeHints[$attribute]) ? $this->model->attributeHints[$attribute] : null;
            $submissionValue->value = $value;
            $submissionValue->format = isset($this->model->formatters[$attribute]) ? $this->model->formatters[$attribute] : null;
            $submissionValue->save();
        }

        $submissionEmail = new SubmissionEmail($model);

        $event = new AfterSaveEvent();
        $event->submission = $submissionEmail;
        $event->form = $form;
        $this->trigger(self::EVENT_AFTER_SAVE, $event);

        // if no recipients are defined, the form wont trigger an email
        // and therefore succeed.
        if (empty($submissionEmail->getRecipients())) {
            return true;
        }
        try {
            if ($this->emailMessage) {
                return call_user_func($this->emailMessage, $submissionEmail, $this);
            } else {
                $mail = Yii::$app->mail
                    ->compose(
                        $submissionEmail->getSubject(),
                        StringHelper::template($this->defaultEmailTemplate, [
                            'intro' => nl2br($submissionEmail->getIntro()),
                            'outro' => nl2br($submissionEmail->getOutro()),
                            'summary' => $submissionEmail->getSummaryHtml()
                        ])
                    )
                    ->addresses($submissionEmail->getRecipients())
                    ->send();

                if (!$mail) {
                    throw new Exception(Yii::$app->mail->error);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            if ($doNotSave) {
                $model->delete();
            }
        }

        return true;
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
     *
     * @param string $attributeName
     * @param string $rule
     * @param boolean $isRequired
     * @param string $label
     * @param string $hint
     * @param string $formatAs
     */
    public function autoConfigureAttribute($attributeName, $rule, $isRequired, $label = null, $hint = null, $formatAs = null)
    {
        Yii::debug('configure form attribute: ' . $attributeName, __METHOD__);

        $this->createAttribute($attributeName, $rule);

        if ($isRequired) {
            $this->setAttributeRule($attributeName, 'required');
        }

        $value = $this->getFormDataAttributeValue($attributeName);

        if (!empty($value)) {
            $this->setAttributeValue($attributeName, $value);
        }

        $this->setAttributeLabel($attributeName, $label);
        $this->setAttributeHint($attributeName, $hint);
        $this->setAttributeFormat($attributeName, $formatAs);
    }

    /**
     * Create a new attribute with a required default rule
     *
     * @param string $attributeName
     * @param string|array $rule Providing a rule by array means the first element of the array must be the rule, while the second
     * an array with the options. `[RequireValidator::class, ['skipOnEmpty' => true]]`
     */
    public function createAttribute($attributeName, $rule = 'safe')
    {
        $this->model->defineAttribute($attributeName);

        // [RequireValidator::class, ['foo' => 'bar']]
        $options = [];
        if (is_array($rule)) {
            list($rule, $options) = $rule;
        }

        $this->setAttributeRule($attributeName, $rule, $options);
    }

    /**
     * Attribute Rule
     *
     * @param string $attributeName
     * @param string $rule
     * @param array $options
     *
     */
    public function setAttributeRule($attributeName, $rule, $options = [])
    {
        $this->model->addRule([$attributeName], $rule, $options);
    }

    /**
     * Set attribute value
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function setAttributeValue($attribute, $value)
    {
        $this->model->{$attribute} = $value;
    }
    
    /**
     * Attribute Label
     *
     * @param string $attribute
     * @param string $label
     */
    public function setAttributeLabel($attribute, $label)
    {
        $this->model->setAttributeLabel($attribute, $label);
    }

    /**
     * Attribute Hint
     *
     * @param string $attribute
     * @param string $hint
     */
    public function setAttributeHint($attribute, $hint)
    {
        $this->model->_attributeHints[$attribute] = $hint;
    }

    /**
     * Attribute Format
     *
     * @param string $attribute
     * @param string $formatAs
     */
    public function setAttributeFormat($attribute, $formatAs)
    {
        if ($formatAs && !empty($formatAs)) {
            $this->model->formatters[$attribute] = $formatAs;
        }
    }
}
