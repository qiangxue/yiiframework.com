<?php
/* @var $report \app\models\Report */
/* @var $object \app\models\Linkable */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create News';
?>

<div class="container">
    <div class="row">
        <div class="content">
            <div class="report-create">
                <p>You are going to report "<?= Html::a(Html::encode($object->getLinkTitle()), $object->getUrl())?>".</p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($report, 'content')->textarea(['name' => 'content']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
