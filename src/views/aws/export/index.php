<?php
use luya\admin\ngrest\aw\CallbackButtonFileDownloadWidget;
use WhichBrowser\Parser;

?>
<?php if (empty($submissions)): ?>
    <p><i class="material-icons text-info">info</i> <?= Yii::t('forms', 'There are no submissions to export.'); ?></p>
<?php else: ?>
    <?= CallbackButtonFileDownloadWidget::widget([
        'callback' => 'export',
        'label' => Yii::t('forms', 'Generate Export'),
    ]); ?>
    <collapse-container class="mt-3" title="<?= Yii::t('forms', 'Preview data'); ?>">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?= Yii::t('forms', 'Created at'); ?></th>
                        <th><?= Yii::t('forms', 'Language'); ?></th>
                        <th><?= Yii::t('forms', 'Is done'); ?></th>
                        <th><?= Yii::t('forms', 'Browser'); ?></th>
                    </tr>
                </thead>
                <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDatetime($sub->created_at); ?></td>
                        <td><?= $sub->language; ?></td>
                        <td><?= Yii::$app->formatter->asBoolean($sub->is_done); ?></td>
                        <td><?= (new Parser($sub->useragent))->toString(); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    
    </collapse-container>
<?php endif; ?>