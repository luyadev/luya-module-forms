<?php

namespace luya\forms;

class Module extends \luya\admin\base\Module
{
    public function registerComponents()
    {
        return [
            'forms' => [
                'class' => Forms::class,
            ]
        ];
    }
}