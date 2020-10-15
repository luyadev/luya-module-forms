<?php

namespace luya\forms\models;

use luya\admin\behaviors\BlameableBehavior;
use Yii;
use luya\admin\ngrest\base\NgRestModel;
use yii\behaviors\TimestampBehavior;

/**
 * Form.
 *
 * File has been created with `crud/create` command.
 *
 * @property integer $id
 * @property text $title
 * @property text $subject
 * @property text $copy_to_attribute
 * @property text $recipients
 * @property text $email_intro
 * @property text $email_outro
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Form extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public $i18n = ['title', 'subject', 'email_intro', 'email_outro'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%forms_form}}';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-forms-form';
    }

    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class],
            ['class' => BlameableBehavior::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('forms', 'ID'),
            'title' => Yii::t('forms', 'Title'),
            'subject' => Yii::t('forms', 'Subject'),
            'copy_to_attribute' => Yii::t('forms', 'Copy an Attribute'),
            'email_intro' => Yii::t('forms', 'E-Mail intro'),
            'recipients' => Yii::t('forms', 'Recipients'),
            'created_at' => Yii::t('forms', 'Created At'),
            'updated_at' => Yii::t('forms', 'Updated At'),
            'created_by' => Yii::t('forms', 'Created By'),
            'updated_by' => Yii::t('forms', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'recipients', 'subject', 'copy_to_attribute', 'email_intro', 'email_outro'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'title' => 'text',
            'subject' => 'text',
            'recipients' => 'textArray',
            'copy_to_attribute' => 'text',
            'email_intro' => 'textarea',
            'email_outro' => 'textarea',
            'created_at' => 'number',
            'updated_at' => 'number',
            'created_by' => 'number',
            'updated_by' => 'number',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['title', 'recipients']],
            [['create', 'update'], ['title', 'recipients', 'subject', 'copy_to_attribute', 'email_intro', 'email_outro']],
            ['delete', false],
        ];
    }

    public function attributeHints()
    {
        return [
            'subject' => Yii::t('forms', 'form_model_subject_hint'),
            'copy_to_attribute' => Yii::t('forms', 'form_model_copy_to_attribute_hint'),
            'email_intro' => Yii::t('forms', 'form_model_email_intro_hint'),
        ];
    }

    /**
     * Submissions
     *
     * @return Submission[]
     */
    public function getSubmissions()
    {
        return $this->hasMany(Submission::class, ['form_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestRelations()
    {
        return [
            [
                'label' => 'Submissions',
                'targetModel' => Submission::class,
                'dataProvider' => $this->getSubmissions(),
                'tabLabelAttribute' => 'title',
            ],
        ];
    }
}
