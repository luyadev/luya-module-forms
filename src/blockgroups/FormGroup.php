<?php

namespace luya\forms\blockgroups;

use luya\cms\base\BlockGroup;

class FormGroup extends BlockGroup
{
    public function identifier()
    {
        return 'forms-group';
    }

    public function label()
    {
        return 'Forms';
    }
}
