<?php

namespace luya\forms\blocks;

use luya\admin\validators\StorageUploadValidator;
use Yii;
use luya\cms\base\PhpBlock;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;

/**
 * Text Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class FileBlock extends PhpBlock
{
    use FieldBlockTrait { config as parentConfig; }

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return FormGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return Yii::t('forms', 'File');
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'attach_file';
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        $config = $this->parentConfig();
        // remove validator
        unset($config['vars'][4]);
        return $config;
    }
    
    /**
     * {@inheritDoc}
     *
     * @param {{vars.field}}
     * @param {{vars.hint}}
     * @param {{vars.label}}
    */
    public function admin()
    {
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">'.Yii::t('forms', 'File').'</span></div>';
    }

    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            StorageUploadValidator::class,
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint),
            $this->getVarValue($this->varFormatAs)
        );

        $varName = $this->getVarValue($this->varAttribute);
        if (!$varName) {
            return;
        }

        $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName);

        return $activeField->fileInput(['accept' => 'file/*']);
    }
}
