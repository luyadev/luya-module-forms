<?php

namespace luya\forms\aws;

use luya\admin\ngrest\aw\CallbackButtonFileDownloadWidget;
use luya\admin\ngrest\base\ActiveWindow;
use luya\forms\models\SubmissionValue;
use luya\helpers\ExportHelper;
use luya\helpers\Inflector;
use Yii;

/**
 * Export data
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class ExportActiveWindow extends ActiveWindow
{
    public $module = 'forms';

    public function defaultIcon()
    {
        return 'system_update_alt';
    }

    public function defaultLabel()
    {
        return Yii::t('forms', 'Export');
    }

    public function getTitle()
    {
        return $this->model->title;
    }

    public function index()
    {
        return $this->render('index', [
            'submissionsCount' => $this->model->getSubmissions()->where(['is_done' => false])->count(),
        ]);
    }

    public function callbackExport()
    {
        $data = [];

        foreach ($this->model->getSubmissions()->where(['is_done' => false])->all() as $submission) {
            $item = [
                Yii::t('forms', 'Date') => Yii::$app->formatter->asDatetime($submission->created_at),
                Yii::t('forms', 'Language') => $submission->language,
            ];

            /** @var SubmissionValue $value */
            foreach ($submission->values as $value) {
                $item[$value->attribute] = $value->formattedValue;
            }

            $data[] = $item;
        }

        return CallbackButtonFileDownloadWidget::sendOutput($this, Inflector::slug('export-' . Yii::$app->formatter->asDatetime(time())).'.xlsx', ExportHelper::xlsx($data, [], true, ['sort' => false]));
    }
}
