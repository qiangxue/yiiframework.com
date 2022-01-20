<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $badges app\models\Badge[] */
/* @var $counts array */
/* @var $forumBadges array */

$this->title = 'Badges';

$this->registerMetaTag(['name' => 'keywords', 'value' => 'yii framework, community, badges']);

$total = array_sum($counts);
if ($total === 0) {
    $total = 1;
}
$max = empty($counts) ? 0 : max($counts) / $total;
$expand = $max < 0.45 ? 2 : 1;

?>
<div class="container view-user-badges">
    <div class="content">
        <h1>Badges</h1>

        <p>It's easy to play an active role in the Yii community: add comments and cast votes throughout the site, ask and respond to questions posted in forum topics, write and help to improve on the wiki articles, contribute framework extensions, and more. As you participate you will earn badges which appear on your user page. Here are all available badges and the criteria for earning them:</p>

        <h2>Website Badges</h2>

        <?php foreach($badges as $badge): ?>
            <?php if(!isset($counts[$badge->id])) $counts[$badge->id]=0; ?>
            <?php $percent = $counts[$badge->id]/$total*100.0; ?>
            <div class="userbadge">
                <div class="userbadge-icon userbadge-<?= $badge->urlname ?>"></div>
                <div class="userbadge-progress-bar" style="width: <?= round($percent*$expand) ?>%"></div>
                <div class="userbadge-info">
                    <h3>
                        <?= Html::a(Html::encode($badge->name), ['user/view-badge', 'name' => $badge->urlname]) ?>
                        <?php if(isset($counts[$badge->id])): ?>
                            <span class="x">x</span><span class="count"><?= $counts[$badge->id] ?></span>
                        <?php endif ?>
                    </h3>
                    <p><?= Html::encode($badge->description) ?></p>
                    <span class="percent"><?php printf('%0.1f%%', $percent) ?></span>
                </div>
            </div>
        <?php endforeach ?>

        <h2>Forum Badges</h2>

        <?php foreach($forumBadges as $badge): ?>
        <div class="userbadge userbadge-forum">
            <div class="userbadge-progress-bar" style="width: <?= round(0) ?>%"></div>
            <div class="userbadge-info">
                <h3>
                    <?= Html::a(Html::encode($badge['name']), $badge['url']) ?>
                    <?php if(isset($badge['grant_count'])): ?>
                        <span class="x">x</span><span class="count"><?= $badge['grant_count'] ?></span>
                    <?php endif ?>
                </h3>
                <p><?= $badge['description'] ?></p>
            </div>
        </div>
        <?php endforeach ?>

    </div>
</div>

