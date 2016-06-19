<?php
/**
 * @var yii\web\View $this
 * @var \yii\data\Pagination $pagination
 * @var \yii\data\Sort $sort
 * @var array $listPackage
 * @var integer $totalPackage
 * @var string $queryString
 */

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\LinkPager;

$this->title = 'Extensions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="guide-header-wrap">
    <div class="container guide-header common-heading">
        <div class="row">
            <div class="col-md-12">
                <h1 class="guide-headline"><?= Html::encode($this->title)?></h1>
                <small>via packagist.org</small>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        <?= \app\widgets\Alert::widget();?>

        <?= $this->render('_search', [
            'queryString' => $queryString
        ])?>

        <p>Total: <?= Html::encode($totalPackage);?></p>
        <? if ($listPackage):?>
            <table class="summary-table table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Vendor / Name</th>
                        <th>Description</th>
                        <th><?= $sort->link('downloads');?></th>
                        <th><?= $sort->link('favers');?></th>
                        <th>Repository</th>
                        <th>Package</th>
                    </tr>
                <? foreach ($listPackage as $package):?>
                    <tr>
                        <td>
                            <?= Html::a($package['name'], $package['urlPackage']);?>
                        </td>
                        <td><?= Html::encode(StringHelper::truncateWords($package['description'], 10));?></td>
                        <td><?= Html::encode($package['downloads']);?></td>
                        <td><?= Html::encode($package['favers']);?></td>
                        <td>
                            <?= Html::a(parse_url($package['repository'], PHP_URL_HOST), $package['repository'], ['target' => '_blank']);?>
                        </td>
                        <td>
                            <?= Html::a('Open', $package['urlPackage']);?>
                        </td>
                    </tr>
                <? endforeach;?>
                </tbody>
            </table>

            <? if ($pagination):?>
                <?= LinkPager::widget([
                    'pagination' => $pagination
                ]);?>
            <? endif;?>
        <? endif;?>
    </div>
</div>
<br>
