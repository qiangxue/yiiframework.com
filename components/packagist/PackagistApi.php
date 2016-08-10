<?php

namespace app\components\packagist;

use yii\base\Exception;
use yii\helpers\Json;

/**
 * Package manager for yii2-extension packagist.org packages
 *
 * @see https://packagist.org
 */
class PackagistApi
{
    const ENDPOINT_SEARCH = 'https://packagist.org/search.json?%s';
    const ENDPOINT_PACKAGE = 'https://packagist.org/packages/%s/%s.json';
    const ENDPOINT_GITHUB_FILE = 'https://raw.githubusercontent.com/%s/master/%s';

    /**
     * Get a list of packages
     *
     * @param string $query query
     * @param null|integer $page page
     * @param null|array $sort sorting
     *
     * @return array
     */
    public function search($query, $page = null, $sort = null)
    {
        $packages = [];
        $totalCount = 0;
        $currentPageCount = 0;
        $errorMessage = null;

        $orderBys = [
            [
                'sort' => 'downloads',
                'order' => 'desc',
            ]
        ];
        if ($sort !== null) {
            $orderBys = [];
            foreach ($sort as $kSort => $vSort) {
                $orderBys[] = [
                    'sort' => $kSort,
                    'order' => SORT_ASC === $vSort ? 'asc' : 'desc'
                ];
            }
        }
        $queryParam = [
            'type' => 'yii2-extension',
            'q' => $query,
            'orderBys' => $orderBys
        ];

        if ($page !== null) {
            $queryParam['page'] = $page;
        }

        $url = sprintf(self::ENDPOINT_SEARCH, http_build_query($queryParam));
        $data = null;
        try {
            $data = Json::decode(file_get_contents($url), true);
        } catch (\Exception $e) {
            $errorMessage = 'Error getting data from packagist.org:' . $e->getMessage();
        }

        if (!is_array($data) || array_diff(['results', 'total'], array_keys($data))) {
            $errorMessage = 'Error getting data from packagist.org';
        } else {
            $currentPageCount = count($data['results']);
            foreach ($data['results'] as $result) {
                $package = Package::createFromAPIData($result);
                if ($package instanceof Package) {
                    $packages[] = Package::createFromAPIData($result);
                }
            }
            $totalCount = $data['total'];
        }

        return [
            'packages' => $packages,
            'totalCount' => $totalCount,
            'currentPageCount' => $currentPageCount,
            'errorMessage' => $errorMessage
        ];
    }

    /**
     * Get a package
     *
     * @param string $vendorName
     * @param string $packageName
     *
     * @return Package|bool
     */
    public function getPackage($vendorName, $packageName)
    {
        $url = sprintf(self::ENDPOINT_PACKAGE, $vendorName, $packageName);

        try {
            $data = Json::decode(file_get_contents($url), true);
        } catch (\Exception $e) {
            return false;
        }

        if (!is_array($data) || !isset($data['package'])) {
            return false;
        }

        $package = Package::createFromAPIData($data['package']);
        return $package instanceof Package ? $package : false;
    }

    /**
     * Get README.md from repository
     *
     * @param string $repositoryUrl
     * @return null|string
     */
    public function getReadmeFromRepository($repositoryUrl)
    {
        if (!preg_match('/^https?:\/\/github\.com\/([^\/]+\/[^\/]+)\.git$/i', $repositoryUrl, $matches)) {
            return null;
        }

        try {
            return file_get_contents(sprintf(self::ENDPOINT_GITHUB_FILE, $matches[1], 'README.md'));
        } catch (\Exception $e) {
            return null;
        }
    }
}