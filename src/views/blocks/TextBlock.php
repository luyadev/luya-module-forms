<?php
/**
 * View file for block: TextBlock 
 *
 * File has been created with `block/create` command. 
 *
 * @param $this->varValue('field');
 * @param $this->varValue('hint');
 * @param $this->varValue('label');
 *
 * @var \luya\cms\base\PhpBlockView $this
 */

$attributeName = $this->varValue('attribute');
Yii::$app->forms->model->defineAttribute($attributeName);
Yii::$app->forms->model->addRule([$attributeName], $this->varValue('rule', 'safe'));

if ($this->varValue('isRequired')) {
    Yii::$app->forms->model->addRule([$attributeName], 'required');
}

$formData = Yii::$app->session->get('formData', []);
if (isset($formData[$attributeName])) {
    Yii::$app->forms->model->{$attributeName} = $formData[$attributeName];
}

?>
<?= Yii::$app->forms->form->field(Yii::$app->forms->model, $attributeName)
    ->textInput()
    ->label($this->varValue('label'))
    ->hint($this->varValue('hint')); ?>
