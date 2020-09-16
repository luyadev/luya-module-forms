<?php

namespace luya\forms;

use luya\forms\models\Form;
use luya\forms\models\Submission;

class SubmissionEmail
{
    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;  
    }

    public function getSubject()
    {
        return 'subject';
    }

    public function getRecipients()
    {
        return $this->submission->form->recipients;
    }

    public function getSummaryHtml()
    {
        $html = null;

        foreach ($this->submission->values as $value) {
            $html .= "<p>{$value->label}: {$value->value}</p>";
        }

        return $html;
    }
}