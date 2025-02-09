<?php


namespace app\widgets;


use DateInterval;
use DateTime;
use yii\base\Widget;

/**
 * VersionCalendar displays versions timeline in cool visual way.
 * Ported from php.net website
 * @see https://github.com/php/web-php/blob/master/images/supported-versions.php
 */
class VersionCalendar extends Widget
{
    public $versions = [];

    private $marginLeft = 100;
    private $marginRight = 50;
    private $headerHeight = 24;
    private $yearWidth = 120;
    private $branchHeight = 30;
    private $footerHeight = 24;

    public function run()
    {
        return $this->render('versionCalendar', [
            'headerHeight' => $this->headerHeight,
            'branchHeight' => $this->branchHeight,
            'marginLeft' => $this->marginLeft,
            'marginRight' => $this->marginRight,
            'footerHeight' => $this->footerHeight,
            'yearWidth' => $this->yearWidth,
            'versions' => $this->versions,
        ]);
    }

    public function minDate(): DateTime
    {
        return (new DateTime('January 1'))->sub(new DateInterval('P3Y'));
    }

    public function maxDate(): DateTime
    {
        return (new DateTime('January 1'))->add(new DateInterval('P6Y'));
    }

    /**
     * @param DateTime|null $date
     * @param DateInterval|null $extra
     * @return float|int
     */
    public function dateHorizontalCoordinate($date, $extra = null)
    {
        if ($date === null) {
            $date = new DateTime();
            if ($extra !== null) {
                $date->add($extra);
            }
        } elseif (\is_string($date)) {
            $date = new DateTime($date);
        }

        $diff = $date->diff($this->minDate());
        if (!$diff->invert) {
            return $this->marginLeft;
        }
        return $this->marginLeft + ($diff->days / (365.24 / $this->yearWidth));
    }

    public function getBranchSupportState($version)
    {
        $release = $version['release'] ?? null;
        $enhancement = $version['enhancements'] ?? null;
        $bug = $version['bugfixes'] ?? null;
        $eol = $version['eol'] ?? null;

        $now = new DateTime();
        if ($eol !== null && $now >= new DateTime($eol)) {
            return 'eol';
        }

        if ($bug !== null && $now >= new DateTime($bug)) {
            return 'security';
        }

        if ($enhancement !== null && $now >= new DateTime($enhancement)) {
            return 'feature-freeze';
        }

        if ($release !== null && $now >= new DateTime($release)) {
            return 'active';
        }

        return 'future';
    }
}
