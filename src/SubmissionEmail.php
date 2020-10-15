<?php

namespace luya\forms;

use luya\forms\models\Submission;
use luya\helpers\StringHelper;

class SubmissionEmail
{
    /**
     * @var Submission
     */
    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function getSubject()
    {
        return $this->submission->form->subject ? $this->submission->form->subject : $this->submission->form->title;
    }

    public function getRecipients()
    {
        $recipients = $this->submission->form->recipients;

        $copyAttribute = $this->submission->form->copy_to_attribute;

        if ($copyAttribute) {
            foreach ($this->submission->values as $value) {
                if ($value->attribute == $copyAttribute) {
                    $recipients[] = $value->value;
                }
            }
        }

        return $recipients;
    }

    public function getSummaryHtml()
    {
        $html = null;

        foreach ($this->submission->values as $value) {
            $html .= "<p>{$value->label}: {$value->value}</p>";
        }

        return $html;
    }

    protected function variablizeValues()
    {
        $vars = [];
        foreach ($this->submission->values as $value) {
            $vars[$value->attribute] = $value->value;
        }

        return $vars;
    }

    public function getIntro()
    {
        return StringHelper::template($this->submission->form->email_intro, $this->variablizeValues());
    }

    public function getOutro()
    {
        return StringHelper::template($this->submission->form->email_outro, $this->variablizeValues());
    }
}
