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
            ->node('Form', 'extension')
                ->group('Group')
                    ->itemApi('Form', 'forms/form/index', 'label', 'api-forms-form')
                    ->itemApi('Submission', 'forms/submission/index', 'label', 'api-forms-submission')
                    ->itemApi('SubmissionValue', 'forms/submission-value/index', 'label', 'api-forms-submissionvalue');
    }

    public function registerComponents()
    {
        return [
            'forms' => [
                'class' => Forms::class,
            ]
        ];
    }
}