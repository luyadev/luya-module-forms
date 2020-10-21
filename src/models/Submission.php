<?php

namespace luya\forms\models;

use luya\admin\aws\DetailViewActiveWindow;
use Yii;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\ngrest\plugins\SelectRelationActiveQuery;
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
 *
 * @property Form $form
 * @property SubmissionValue[] $values
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
            'id' => Yii::t('forms', 'ID'),
            'form_id' => Yii::t('forms', 'Form ID'),
            'useragent' => Yii::t('forms', 'Useragent'),
            'language' => Yii::t('forms', 'Language'),
            'url' => Yii::t('forms', 'Url'),
            'is_done' => Yii::t('forms', 'Is Done'),
            'created_at' => Yii::t('forms', 'Created At'),
            'updated_at' => Yii::t('forms', 'Updated At'),
        ];
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
            'form_id' => [
                'class' => SelectRelationActiveQuery::class,
                'query' => $this->getForm(),
                'relation' => 'form',
                'labelField' => 'title',
            ],
            'useragent' => 'text',
            'language' => 'text',
            'url' => 'text',
            'is_done' => 'toggleStatus',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['form_id', 'created_at', 'is_done', ]],
            [['create', 'update'], ['form_id', 'useragent', 'language', 'url']],
            ['delete', false],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestGroupByField()
    {
        return 'form_id';
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestFilters()
    {
        $filters = [];

        foreach (Submission::find()->with(['form'])->distinct('form_id')->all() as $submission) {
            $filters[$submission->form->title] = self::ngRestFind()->andWhere(['form_id' => $submission->form->id]);
        }

        return $filters;
    }

    /**
     * @return SubmissionValue[]
     */
    public function getValues()
    {
        return $this->hasMany(SubmissionValue::class, ['submission_id' => 'id']);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->hasOne(Form::class, ['id' => 'form_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestActiveWindows()
    {
        return [
            [
                'class' => DetailViewActiveWindow::class,
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ngRestRelations()
    {
        return [
            [
                'label' => 'Values',
                'targetModel' => SubmissionValue::class,
                'dataProvider' => $this->getValues(),
                'tabLabelAttribute' => 'created_at',
            ],
        ];
    }
}
