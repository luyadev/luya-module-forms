<?php

namespace luya\forms\blocks;

use luya\cms\base\PhpBlock;
use luya\forms\blockgroups\FormGroup;
use luya\helpers\StringHelper;
use Yii;

/**
 * Summary
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class SummaryBlock extends PhpBlock
{
    public $template = '<p>{{label}}: {{value}}</p>';

    public $isContainer = false;

    public function blockGroup()
    {
        return FormGroup::class;
    }

    public function config()
    {
        return [
            'vars' => [
                ['var' => 'template', 'label' => Yii::t('forms', 'Row Template'), 'type' => self::TYPE_TEXTAREA, 'placeholder' => $this->template],
            ]
        ];
    }

    public function getFieldHelp()
    {
        return [
            'template' => Yii::t('forms', 'The variables {{label}} and {{value}} are available.'),
        ];
    }

    public function admin()
    {
        return '<div class="alert alert-info border-0 text-center">Summary / Preview</div>';
    }

    public function name()
    {
        return Yii::t('forms', 'Summary');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'description';
    }

    public function frontend()
    {
        Yii::$app->forms->loadModel();
        $html = null;
        $model = Yii::$app->forms->model;
        foreach ($model->attributes as $k => $v) {
            if ($model->isAttributeInvisible($k)) {
                continue;
            }

            $html .= StringHelper::template($this->getVarValue('template', $this->template), [
                'label' => $model->getAttributeLabel($k),
                'value' => $model->formatAttributeValue($k, $v),
            ]);
        }

        return $html;
    }
}
