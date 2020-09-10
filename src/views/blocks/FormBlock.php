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

?>

<?php if (Yii::$app->session->getFlash('formDataSuccess')): ?>
    <div class="alert alert-success">Super, success!</div>
<?php else: ?>
    <div style="border:1px solid red; padding:10px; margin:10px">
        <?php if ($this->extraValue('review')): ?>
            <p class="lead">Confirm</p>
            <?php foreach(Yii::$app->forms->model->attributes as $k => $v): ?>
                <p><?= $k; ?>: <?= $v; ?></p>
            <?php endforeach; ?>
            <?= Html::a('Edit Data', '?reload'); ?> | <?= Html::a('Submit', '?submit=1'); ?>
        <?php else: ?>
            <?= $this->placeholderValue('content'); ?>
            <?= Html::submitButton('Senden'); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php Yii::$app->forms->form->end(); ?>