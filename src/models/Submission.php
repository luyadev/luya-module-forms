<?php

namespace luya\forms\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;
use yii\behaviors\TimestampBehavior;

/**
 * Submission.
 * 
 * File has been created with `crud/create` command. 
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $useragent
 * @property string $language
 * @property string $url
 * @property int $is_done
 * @property integer $created_at
 * @property integer $updated_at
 */
class Submission extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%forms_submission}}';
    }

    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-forms-submission';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'useragent' => Yii::t('app', 'Useragent'),
            'language' => Yii::t('app', 'Language'),
            'url' => Yii::t('app', 'Url'),
            'is_done' => Yii::t('app', 'Is Done'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getForm()
    {
        return $this->hasOne(Form::class, ['id' => 'form_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'required'],
            [['form_id', 'created_at', 'updated_at', 'is_done'], 'integer'],
            [['useragent', 'language', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'form_id' => 'number',
            'useragent' => 'text',
            'language' => 'text',
            'url' => 'text',
            'is_done' => 'toggleStatus',
            'created_at' => 'number',
            'updated_at' => 'number',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['form_id', 'useragent', 'language', 'url', 'is_done', 'created_at']],
            [['create', 'update'], ['form_id', 'useragent', 'language', 'url']],
            ['delete', false],
        ];
    }

    public function getValues()
    {
        return $this->hasMany(SubmissionValue::class, ['submission_id' => 'id']);
    }
}
