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
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'subject' => 'Subject',
            'copy_to_attribute' => 'Copy an Attribute',
            'email_intro' => 'E-Mail intro',
            'email_outro' => 'E-Mail Outro',
            'recipients' => Yii::t('app', 'Recipients'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
            'subject' => 'Betreffe des E-Mails. Wenn nichts angegeben wird, wird der Titel des Forms verwendet',
            'copy_to_attribute' => 'Wenn definiert und das Attribute im Formular vorhanden ist, wird der Wert des angegeben Felds auch zu der Empfänger Liste hinzugefügt.',
            'email_intro' => 'Wenn definiert wird diese verwendet für Email das intro, alle verfügbaren variabeln können mittels {{attribute}} verwendet werden.',
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
