<?php

namespace luya\forms\apis;

/**
 * Submission Value Controller.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class SubmissionValueController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\forms\models\SubmissionValue';
}
