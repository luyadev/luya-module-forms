<?php
/**
 * View file for block: FormBlock 
 *
 * File has been created with `block/create` command. 
 *
 * @param $this->placeholderValue('content');
 * @param $this->varValue('formId');
 *
 * @var \luya\cms\base\PhpBlockView $this
 */

use luya\helpers\Html;
use luya\helpers\StringHelper;

?>

<?php if (Yii::$app->session->getFlash('formDataSuccess')): ?>
    <?= $this->placeholderValue('success'); ?>
<?php else: ?>
    <div style="border:1px solid red; padding:10px; margin:10px">
        <?php if ($this->extraValue('review')): ?>
            <?= $this->placeholderValue('preview'); ?>
            <?= Html::a($this->cfgValue('previewBackButtonLabel', 'Zurück'), '?reload', Yii::$app->forms->backButtonOptions); ?> | <?= Html::a($this->cfgValue('previewSubmitButtonLabel', 'Senden'), '?submit=1', Yii::$app->forms->submitButtonsOptions); ?>
        <?php else: ?>
            <?= $this->placeholderValue('content'); ?>
            <?= Html::submitButton('Senden', Yii::$app->forms->submitButtonsOptions); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php Yii::$app->forms->form->end(); ?>