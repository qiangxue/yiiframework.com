<?php
/* @var int $headerHeight */
/* @var int $branchHeight */
/* @var int $marginLeft */
/* @var int $marginRight */
/* @var int $footerHeight */
/* @var int $yearWidth */
/* @var array $versions */
/* @var \yii\web\View $this */

/* @var \app\widgets\VersionCalendar $widget */
$widget = $this->context;

$i = 0;
foreach ($versions as $branch => $version) {
    $versions[$branch]['top'] = $headerHeight + ($branchHeight * $i++);
}

$years = iterator_to_array(new DatePeriod($widget->minDate(), new DateInterval('P1Y'), $widget->maxDate()));
$width = $marginLeft + $marginRight + ((count($years) - 1) * $yearWidth);
$height = $headerHeight + $footerHeight + (count($versions) * $branchHeight);

use yii\helpers\Html; ?>

<svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 <?= $width ?> <?= $height ?>" width="<?= $width ?>" height="<?= $height ?>" style="max-width: 100%">
	<style type="text/css">
		<![CDATA[
			text {
				fill: #333;
				font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
				font-size: <?= (2 / 3) * $headerHeight; ?>px;
			}
			g.eol rect,
			.branches rect.eol {
				fill: #f33;
			}
			g.eol text {
				fill: white;
			}
			g.security rect,
			.branches rect.security {
				fill: #ffb95e;
			}

            .branches rect.predicted {
                fill-opacity: 0.3;
            }

			g.active rect,
			.branches rect.active {
				fill: #9c9;
			}
            g.feature-freeze rect,
            .branches rect.feature-freeze {
                fill: #71bdff;
            }

            g.future rect {
                fill: #eee;
            }

			.branch-labels text {
				dominant-baseline: central;
				text-anchor: middle;
			}
			.today line {
				stroke: #f33;
				stroke-dasharray: 7,7;
				stroke-width: 3px;
			}
			.today text {
				fill: #f33;
				text-anchor: middle;
			}
			.years line {
				stroke: black;
			}
			.years text {
				text-anchor: middle;
			}
		]]>
	</style>
	<!-- Branch labels -->
	<g class="branch-labels">
		<?php foreach ($versions as $branch => $version): ?>
			<g class="<?= $widget->getBranchSupportState($version) ?>">
				<rect x="0" y="<?= $version['top'] ?>" width="<?= 0.85 * $marginLeft ?>" height="<?= $branchHeight ?>" />
				<text x="<?= 0.42 * $marginLeft ?>" y="<?= $version['top'] + (0.5 * $branchHeight) ?>">
					<?= Html::encode($branch) ?>
				</text>
			</g>
		<?php endforeach ?>
	</g>
	<!-- Branch blocks -->
	<g class="branches">
		<?php foreach ($versions as $branch => $version): ?>
			<?php
            if (!isset($version['release'])) {
                continue;
            }

			$xRelease = $widget->dateHorizontalCoordinate($version['release'] ?? null);
			$releaseClass = isset($version['enhancements']) ? 'active' : 'active predicted';

            $xFeatureFreeze = $widget->dateHorizontalCoordinate($version['enhancements'] ?? null);
            $featureFreezeClass = isset($version['bugfixes']) ? 'feature-freeze' : 'feature-freeze predicted';

			$xBugFreeze = $widget->dateHorizontalCoordinate($version['bugfixes'] ?? null, new DateInterval('P2Y'));
            $bugFreezeClass = isset($version['eol']) ? 'security' : 'security predicted';

			$xEol = $widget->dateHorizontalCoordinate($version['eol'] ?? null, new DateInterval('P5Y'));
			?>
			<rect class="<?= $releaseClass ?>" x="<?= $xRelease ?>" y="<?= $version['top'] ?>" width="<?= $xFeatureFreeze - $xRelease ?>" height="<?= $branchHeight ?>" />
            <rect class="<?= $featureFreezeClass ?>" x="<?= $xFeatureFreeze ?>" y="<?= $version['top'] ?>" width="<?= $xBugFreeze - $xFeatureFreeze ?>" height="<?= $branchHeight ?>" />
			<rect class="<?= $bugFreezeClass ?>" x="<?= $xBugFreeze ?>" y="<?= $version['top'] ?>" width="<?= $xEol - $xBugFreeze ?>" height="<?= $branchHeight ?>" />
		<?php endforeach ?>
	</g>
	<!-- Year lines -->
	<g class="years">
		<?php foreach ($years as $date): ?>
			<line x1="<?= $widget->dateHorizontalCoordinate($date) ?>" y1="<?= $headerHeight ?>" x2="<?= $widget->dateHorizontalCoordinate($date) ?>" y2="<?= $headerHeight + (count($versions) * $branchHeight) ?>" />
			<text x="<?= $widget->dateHorizontalCoordinate($date) ?>" y="<?= 0.8 * $headerHeight; ?>">
				<?= $date->format('Y') ?>
			</text>
		<?php endforeach ?>
	</g>
	<!-- Today -->
	<g class="today">
		<?php
		$now = new DateTime();
		$x = $widget->dateHorizontalCoordinate($now);
		?>
		<line x1="<?= $x ?>" y1="<?= $headerHeight ?>" x2="<?= $x ?>" y2="<?= $headerHeight + (count($versions) * $branchHeight) ?>" />
		<text x="<?= $x ?>" y="<?= $headerHeight + (count($versions) * $branchHeight) + (0.8 * $footerHeight) ?>">
			<?= 'Today: '.$now->format('j M Y') ?>
		</text>
	</g>
</svg>
