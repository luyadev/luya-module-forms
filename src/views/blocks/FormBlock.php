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
    <?php if ($this->extraValue('review')): ?>
        <?= $this->placeholderValue('preview'); ?>
        <?= StringHelper::template($this->varValue('previewButtonsTemplate', $this->context->previewButtonsTemplate), [
            'back' => Html::a($this->cfgValue('previewBackButtonLabel', 'Zurück'), '?reload=' . $this->varValue('formId'), Yii::$app->forms->backButtonOptions),
            'submit' => Html::a($this->cfgValue('previewSubmitButtonLabel', 'Senden'), '?submit='.$this->varValue('formId'), Yii::$app->forms->submitButtonsOptions),
        ]); ?>

    <?php else: ?>
        <?php if ($this->extraValue('isSubmit') && Yii::$app->forms->model->hasErrors()): ?>
            <?= Yii::$app->forms->form->errorSummary(Yii::$app->forms->model); ?>
        <?php endif; ?>
        <?= $this->placeholderValue('content'); ?>
        <?= Html::submitButton('Senden', Yii::$app->forms->submitButtonsOptions); ?>
    <?php endif; ?>
<?php endif; ?>
<?php Yii::$app->forms->form->end(); ?>