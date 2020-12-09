<?php
use luya\admin\ngrest\aw\CallbackButtonFileDownloadWidget;
use WhichBrowser\Parser;

?>
<?php if (!$submissionsCount): ?>
    <p><i class="material-icons text-info">info</i> <?= Yii::t('forms', 'There are no submissions to export.'); ?></p>
<?php else: ?>
    <?= CallbackButtonFileDownloadWidget::widget([
        'callback' => 'export',
        'label' => Yii::t('forms', 'Generate Export') . " ({$submissionsCount})",
    ]); ?>
<?php endif; ?>