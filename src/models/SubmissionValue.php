<?php

namespace luya\forms\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Submission Value.
 *
 * File has been created with `crud/create` command.
 *
 * @property integer $id
 * @property integer $submission_id
 * @property string $attribute
 * @property string $label
 * @property string $hint
 * @property text $value
 */
class SubmissionValue extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%forms_submission_value}}';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-forms-submissionvalue';
    }

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_VALIDATE, function () {
            if (is_array($this->value)) {
                $this->value = implode(", ", $this->value);
            }

            $this->value = strip_tags($this->value);
        });
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('forms', 'ID'),
            'submission_id' => Yii::t('forms', 'Submission ID'),
            'attribute' => Yii::t('forms', 'Attribute'),
            'label' => Yii::t('forms', 'Label'),
            'hint' => Yii::t('forms', 'Hint'),
            'value' => Yii::t('forms', 'Value'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_id'], 'integer'],
            [['attribute', 'label'], 'required'],
            [['value'], 'string'],
            [['attribute', 'label', 'hint'], 'string', 'max' => 255, 'skipOnEmpty' => true, 'strict' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'submission_id' => 'number',
            'attribute' => 'text',
            'label' => 'text',
            'hint' => 'text',
            'value' => 'textarea',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['label', 'value']],
            [['create', 'update'], ['submission_id', 'attribute', 'label', 'hint', 'value']],
            ['delete', false],
        ];
    }
}
