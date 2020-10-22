<?php

namespace luya\forms\aws;

use luya\admin\ngrest\aw\CallbackButtonFileDownloadWidget;
use luya\admin\ngrest\base\ActiveWindow;
use luya\forms\models\SubmissionValue;
use luya\helpers\ExportHelper;
use luya\helpers\Inflector;
use Yii;

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
            'submissions' => $this->model->submissions,
        ]);
    }

    public function callbackExport()
    {
        $data = [];

        foreach ($this->model->submissions as $submission) {
            $item = [
                'date' => Yii::$app->formatter->asDatetime($submission->created_at),
                'language' => $submission->language,
                'done' => $submission->is_done,
            ];

            /** @var SubmissionValue $value */
            foreach ($submission->values as $value) {
                $item[$value->label] = $value->formattedValue;
            }

            $data[] = $item;
        }

        return CallbackButtonFileDownloadWidget::sendOutput($this, Inflector::slug('export-' . Yii::$app->formatter->asDatetime(time())).'.xlsx', ExportHelper::xlsx($data));
    }
}