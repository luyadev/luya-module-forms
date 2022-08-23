<?php

namespace luya\forms;

use luya\forms\models\Form;
use yii\base\Event;

/**
 * A form after save event to attach in the config.
 * 
 * ```php
 * 'forms' => [
 *     'class' => 'luya\forms\Forms',
 *     'on afterSave' => function(\luya\forms\AfterSaveEvent $event) {
 *         // do something with event model 
 *     }
 * ]
 * ```
 * 
 * @since 1.6.0
 */
class AfterSaveEvent extends Event
{
    /**
     * @var SubmissionEmail
     */
    public $submission;

    /**
     * @var Form
     */
    public $form;
}