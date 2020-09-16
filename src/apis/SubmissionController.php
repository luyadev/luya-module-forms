<?php

namespace luya\forms\apis;

/**
 * Submission Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class SubmissionController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\forms\models\Submission';
}