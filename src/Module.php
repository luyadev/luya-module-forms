<?php

namespace luya\forms;

class Module extends \luya\admin\base\Module
{
    public $apis = [
        'api-forms-form' => 'luya\forms\apis\FormController',
        'api-forms-submission' => 'luya\forms\apis\SubmissionController',
        'api-forms-submissionvalue' => 'luya\forms\apis\SubmissionValueController',
    ];

    public function getMenu()
    {
        return (new \luya\admin\components\AdminMenuBuilder($this))
            ->node('Forms', 'dynamic_form')
                ->group('Setup')
                    ->itemApi('Form', 'forms/form/index', 'dynamic_form', 'api-forms-form')
                ->group('Data')
                    ->itemApi('Submission', 'forms/submission/index', 'send', 'api-forms-submission')
                    ->itemApi('Values', 'forms/submission-value/index', 'label', 'api-forms-submissionvalue', ['hiddenInMenu' => true]);
    }

    public function registerComponents()
    {
        return [
            'forms' => [
                'class' => Forms::class,
            ]
        ];
    }

    public static function onLoad()
    {
        self::registerTranslation('forms', static::staticBasePath() . '/messages', [
            'forms' => 'forms.php',
        ]);
    }
}
