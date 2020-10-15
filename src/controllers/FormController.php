<?php

namespace luya\forms\controllers;

/**
 * Form Controller.
 *
 * File has been created with `crud/create` command.
 */
class FormController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\forms\models\Form';
}
