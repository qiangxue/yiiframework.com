<?php

use app\widgets\VersionCalendar;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $versions array */
?>
<div class="container style_external_links">
    <div class="content">
        <div>
            <h2>Release interval</h2>

            <ul>
                <li>Extension releases are tagged every week if there are changes since last release.</li>
                <li>Main framework package release is tagged about once a month if there are changes since last release.</li>
            </ul>

            <h2>Maintenance policy</h2>

            <p>Major version is receiving enhancements until it is decided to start working on next major version.</p>

            <p>After current major version release previous major version has:</p>

            <ul>
                <li>Two years of bug fixes.</li>
                <li>Three years of security and PHP compatibility fixes.</li>
            </ul>

            <p>Above policy periods may be extended for individual versions.</p>

            <h2>PHP versions support</h2>

            <p>In order for the framework to work well in a range of PHP versions we are running framework tests using PHPUnit.
               In case <a href="https://phpunit.de/supported-versions.html">newer version of PHPUnit does not work well with unsupported PHP version</a>
               we may remove such PHP version from our test runs. It doesn't mean that older versions would break but
               it significally increases chances for it.
            </p>

            <p>Therefore, it is recommended to use <a href="https://www.php.net/supported-versions.php">supported verison of PHP</a>.</p>

            <h2>Current versions</h2>

            <?= VersionCalendar::widget(['versions' => $versions]) ?>

            <h3>Legend</h3>

            <table class="table">
                <tbody>
                <tr>
                    <td style="background: #9c9; width: 40px;"></td>
                    <td style="background: #9c9; width: 40px; opacity: 0.3"></td>
                    <td>Active support. New features, enhancements, bug fixes, security fixes are accepted.</td>
                </tr>
                <tr>
                    <td style="background: #71bdff; width: 40px;"></td>
                    <td style="background: #71bdff; width: 40px; opacity: 0.3;"></td>
                    <td>Feature freeze. New features are no longer accepted.</td>
                </tr>
                <tr>
                    <td style="background: #ffb95e; width: 40px;"></td>
                    <td style="background: #ffb95e; width: 40px; opacity: 0.3;"></td>
                    <td>Mainly security and PHP compatibility fixes.</td>
                </tr>
                <tr>
                    <td style="background: #eee; width: 40px;"></td>
                    <td></td>
                    <td>Future version.</td>
                </tr>
                </tbody>
            </table>

            <p>Pale color means forecast in case next major version is released today.</p>

            <h2>Details</h2>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Version</th>
                        <th>PHP Version</th>
                        <th>Release (active support)</th>
                        <th>Feature freeze</th>
                        <th>Security and PHP compatibility fixes only</th>
                        <th>End of life</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($versions as $branch => $data): ?>
                        <tr>
                            <td><?= Html::encode($branch) ?></td>
                            <td><?= Html::encode($data['php'] ?? 'To be announced') ?></td>
                            <td><?= Html::encode($data['release'] ?? 'To be announced') ?></td>
                            <td><?= Html::encode($data['enhancements'] ?? 'To be announced') ?></td>
                            <td><?= Html::encode($data['bugfixes'] ?? 'Next release +2 years') ?></td>
                            <td><?= Html::encode($data['eol'] ?? 'Next release +5 years') ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            ¹ Note: Yii <a href="https://github.com/yiisoft/yii2/discussions/19831#discussioncomment-5858046" target="_blank">2.1</a> was skipped.
        </div>
    </div>
</div>
