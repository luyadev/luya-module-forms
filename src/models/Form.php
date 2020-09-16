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
 * @property text $recipients
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
    public $i18n = ['title'];

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
            [['title', 'recipients'], 'string'],
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
            'recipients' => 'textArray',
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
            [['create', 'update'], ['title', 'recipients']],
            ['delete', false],
        ];
    }
}
