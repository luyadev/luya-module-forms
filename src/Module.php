<?php

namespace luya\forms;

/**
 * Module
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
    /**
     * {@inheritDoc}
     */
    public $apis = [
        'api-forms-form' => 'luya\forms\apis\FormController',
        'api-forms-submission' => 'luya\forms\apis\SubmissionController',
        'api-forms-submissionvalue' => 'luya\forms\apis\SubmissionValueController',
    ];

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function registerComponents()
    {
        return [
            'forms' => [
                'class' => Forms::class,
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function onLoad()
    {
        self::registerTranslation('forms', static::staticBasePath() . '/messages', [
            'forms' => 'forms.php',
        ]);
    }
}
