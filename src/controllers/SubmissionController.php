<?php

namespace luya\forms\controllers;

/**
 * Submission Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class SubmissionController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\forms\models\Submission';
}