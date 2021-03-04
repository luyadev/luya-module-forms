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

        $keys = false;

        foreach ($this->model->getSubmissions()
            ->where(['is_done' => false])
            ->with(['values'])
            ->orderBy(['id' => SORT_DESC])
            ->all() as $submission) {
            $item = [
                Yii::t('forms', 'Date') => Yii::$app->formatter->asDatetime($submission->created_at),
                Yii::t('forms', 'Language') => $submission->language,
            ];

            /** @var SubmissionValue $value */
            foreach ($submission->values as $value) {
                $item[$value->attribute] = $value->formattedValue;
            }

            // the latest submission value is the "master" for the export, thereore assign the keys
            if (!$keys) {
                $keys = array_keys($item);
            }

            $cleanedUpTime = array_intersect_key($item, array_flip($keys));

            // ensure that new attributes exists in old values
            foreach ($keys as $requiredKeyName) {
                if (!array_key_exists($requiredKeyName, $cleanedUpTime)) {
                    $cleanedUpTime[$requiredKeyName] = '';
                }
            }
            $data[] = $this->sortArrayByArray($cleanedUpTime, $keys);
        }

        return CallbackButtonFileDownloadWidget::sendOutput($this, Inflector::slug('export-' . Yii::$app->formatter->asDatetime(time())).'.xlsx', ExportHelper::xlsx($data, [], true, ['sort' => false]));
    }

    /**
     * Sort the given array by the they values of another array
     *
     * @param array $array
     * @param array $orderArray
     * @return array
     */
    private function sortArrayByArray(array $array, array $orderArray)
    {
        $ordered = [];
        foreach ($orderArray as $key) {
            if (array_key_exists($key, $array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }

        return $ordered + $array;
    }
}
