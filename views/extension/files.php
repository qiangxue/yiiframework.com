<?php

use app\components\UserPermissions;
use app\models\File;
use yii\helpers\Html;

/** @var $model \app\models\Extension */
/** @var $revision int */
/** @var $file File */

$this->title = "$model->name | Downloads";

?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2 col-lg-2">
            <?= $this->render('_sidebar', [
                'category' => $model->category_id,
            ]) ?>
        </div>

        <div class="col-sm-9 col-md-10 col-lg-10" role="main">

            <?= \app\widgets\Alert::widget() ?>

            <div class="row">
                <div class="col-md-12 col-lg-9">
                    <div class="content extension-row">
                        <h2 class="title"><?= Html::a(Html::encode($model->name), $model->getUrl()) ?> - Downloads</h2>
                        <div class="text">

                            <?php
                            $downloads = $model->getDownloads()->latest()->all();
                            if (!empty($downloads)): ?>
                                <h3>Downloads</h3>

                                <?php foreach($downloads as $download): ?>
                                <div class="file">
                    <b><?= Html::a(Html::encode($download->file_name), $model->getUrl('download', ['filename' => $download->file_name])); ?></b>
                            (<?= Yii::$app->formatter->asShortSize($download->file_size); ?>)
                            <?php if (UserPermissions::canUpdateExtension($model)): ?>
                                [<?= Html::a(
                                    'delete this file',
                                    ['extension/delete-file', 'id' => $model->id, 'file' => $download->id],
                                    ['data' => [
                                        'method' => 'post',
                                        'confirm' => 'Are you sure to delete ' . $download->file_name . '?',
                                    ]]
                                ); ?>]
                            <?php endif; ?>
                            <br/><i><?= Html::encode($download->summary); ?></i>
                            <br/>Released on <?= Yii::$app->formatter->asDate($download->created_at); ?>; downloaded <?= number_format($download->download_count); ?> times.
                        </div>

                                <?php endforeach; ?>

                            <?php else: ?>
                                <p>No downloadable files yet.</p>
                            <?php endif; ?>

                            <?php if ($file !== null): ?>

                                <h3>Upload File</h3>

                                <?php $form = \yii\bootstrap\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

                                    <?= $form->field($file, 'file_name')->fileInput()->label('File')->hint('The file size must not exceed 2MB. Only gif, png, jpg, jpeg, bmp, zip, gz, tgz or bz2 files are allowed.') ?>
                                    <?= $form->field($file, 'summary') ?>

                                    <div class="entry buttons">
                                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>
                                    </div>

                                <?php \yii\bootstrap\ActiveForm::end() ?>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3">

                    <?= $this->render('_info.php', ['model' => $model]) ?>

                </div>
            </div>


        </div>
    </div>
</div>
