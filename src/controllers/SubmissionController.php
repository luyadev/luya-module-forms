<?php

namespace luya\forms\controllers;

/**
 * Submission Controller.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class SubmissionController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\forms\models\Submission';
}
