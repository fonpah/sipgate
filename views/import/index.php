<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 19.02.2015
 * Time: 16:07
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Import Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-md-9">
        <div class="row">
            <?php $form = ActiveForm::begin([
                'id' => 'import-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-md-8\">{input}{error}</div>",
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                ],
            ]); ?>

            <?= $form->field($model, 'file')->fileInput() ?>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-4 text-right">
                    <?= Html::submitButton('Import', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
