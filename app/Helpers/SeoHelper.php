<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SeoHelper
{
    /**
     * Create page seo data as array
     * @param string $pageKey
     * @param mixed $additionalData
     * @return mixed
     */
    public static function pageSeo(
        string $pageKey,
        mixed $additionalData = false,
    ) {
        if (
            $pageKey == 'blog_inner'
            || $pageKey == 'file'
        ) {
            if ($pageKey == 'blog_inner') {
                $pageSeo = self::getBlogInner($additionalData);
            }
            if ($pageKey == 'file') {
                $pageSeo = self::getFileInner($additionalData);
            }
        } else {

            if (
                env('INSTALLED') == '%installed%'
                && !request()->routeIs('install')
            ) {
                $pageData = Cache::get('pages')
                    ->where('page_key', $pageKey)
                    ->first()->seo;

                $pageSeoArr = json_decode($pageData, true);
                $pageSeo = self::getDefaultPage($pageSeoArr);
            }
        }

        return [
            "title" => $pageSeo['title'] ?? false,
            "keywords" => $pageSeo['keywords'] ?? false,
            "description" => $pageSeo['description'] ?? false,
            "no_index" => $pageSeo['no_index'] ?? false,
            "og_title" => $pageSeo['og_title'] ?? false,
            "og_description" => $pageSeo['og_description'] ?? false,
            "og_image" => $pageSeo['og_image'] ?? false,
        ];
    }

    /**
     * Create website seo data as array
     * @return mixed
     */
    private static function getWebsiteSeo()
    {
        $websiteData = DB::table('settings')
            ->whereIn(
                'name',
                [
                    'name',
                    'description',
                    'keywords',
                    'og_title',
                    'og_image',
                    'og_description'
                ]
            )
            ->get();
        $websiteDataArray = [];
        foreach ( $websiteData as $data ) {
            $websiteDataArray[$data->name] = $data->value;
        }

        return $websiteDataArray;
    }

    /**
     * Get page default seo data
     * @param array $pageSeoArr
     * @return mixed
     */
    private static function getDefaultPage(
        array $pageSeoArr
    ): array {
        $websiteSeo = self::getWebsiteSeo();
        $pageSeo = [];

        $pageSeo['title'] = isset($pageSeoArr['title']) && $pageSeoArr['title']
            ? $pageSeoArr['title'] . ' | ' . $websiteSeo['name']
            : $websiteSeo['name'];

        $pageSeo['description'] = (
            isset($pageSeoArr['description'])
            && $pageSeoArr['description']
        )
            ? $pageSeoArr['description']
            : (
                isset($websiteSeo['description']) && $websiteSeo['description']
                ? $websiteSeo['description']
                : false
            );

        $pageSeo['og_title'] = (
            isset($pageSeoArr['og_title'])
            && $pageSeoArr['og_title']
        )
            ? $pageSeoArr['og_title'] . ' | ' . $websiteSeo['name']
            : (
                isset($websiteSeo['og_title']) && $websiteSeo['og_title']
                ? $websiteSeo['og_title'] . ' | ' . $websiteSeo['name']
                : false
            );

        $pageSeo['og_description'] = (
            isset($pageSeoArr['og_description'])
            && $pageSeoArr['og_description']
        )
            ? $pageSeoArr['og_description']
            : (
                (
                    isset($websiteSeo['og_description'])
                    && $websiteSeo['og_description']
                )
                ? $websiteSeo['og_description']
                : false
            );

        $pageSeo['og_image'] = (
            isset($pageSeoArr['og_image'])
            && $pageSeoArr['og_image']
        )
            ? img('page', $pageSeoArr['og_image'])
            : (
                isset($websiteSeo['og_image']) && $websiteSeo['og_image']
                ? img('other', $websiteSeo['og_image'])
                : false
            );

        $pageSeo['no_index'] = (
            isset($pageSeoArr['no_index'])
            && $pageSeoArr['no_index'] == 1
        )
            ? 'index follow'
            : 'noindex nofollow';

        $pageSeo['keywords'] = (
            isset($pageSeoArr['keywords'])
            && $pageSeoArr['keywords']
        )
            ? $pageSeoArr['keywords']
            : (
                isset($websiteSeo['keywords']) && $websiteSeo['keywords']
                ? $websiteSeo['keywords']
                : false
            );

        return $pageSeo;
    }

    /**
     * Get blog page seo data (from post data)
     * @param object $postData
     * @return mixed
     */
    private static function getBlogInner(
        object $postData
    ): array {

        $postSeo = json_decode($postData->seo, true);
        $websiteSeo = self::getWebsiteSeo();
        $pageSeo = [];

        $pageSeo['title'] = $postData->title . ' | ' . $websiteSeo['name'];

        $pageSeo['description'] = (
            isset($postSeo['description'])
            && $postSeo['description']
        )
            ? substr($postSeo['description'], 0, 250)
            : substr(strip_tags($postData->postContent), 0, 250);

        $pageSeo['og_title'] = (
            isset($postSeo['og_title'])
            && $postSeo['og_title']
        )
            ? $postSeo['og_title'] . ' | ' . $websiteSeo['name']
            : $postData->title . ' | ' . $websiteSeo['name'];

        $pageSeo['og_description'] = (
            isset($postSeo['og_description'])
            && $postSeo['og_description']
        )
            ? substr($postSeo['og_description'], 0, 250)
            : substr(strip_tags($postData->postContent), 0, 250);

        $pageSeo['og_image'] = (
            isset($postSeo['og_image'])
            && $postSeo['og_image']
        )
            ? img('blog', $postSeo['og_image'])
            : (
                isset($postData->featured_photo) && $postData->featured_photo
                ? img('blog', $postData->featured_photo)
                : (
                    (
                        isset($websiteSeo['og_image'])
                        && $websiteSeo['og_image']
                    )
                    ? img('other', $websiteSeo['og_image'])
                    : false
                )
            );

        $pageSeo['no_index'] = (
            isset($postSeo['no_index'])
            && $postSeo['no_index'] == 1
        )
            ? 'index follow'
            : 'noindex nofollow';

        $pageSeo['keywords'] = (
            isset($postSeo['keywords'])
            && $postSeo['keywords']
        )
            ? $postSeo['keywords']
            : ($websiteSeo['keywords'] ?? false);

        return $pageSeo;
    }

    /**
     * Get file page seo data (from file data)
     * @param object $fileData
     * @return mixed
     */
    private static function getFileInner(
        object $fileData
    ): mixed {
        $pageData = Cache::get('pages')
            ->where('page_key', 'file')
            ->first()->seo;
        $pageSeoArr = json_decode($pageData, true);
        $websiteSeo = self::getWebsiteSeo();
        $pageSeo = [];
        $filename = $fileData->short_key;

        $pageSeo['title'] = isset($pageSeoArr['title']) && $pageSeoArr['title']
            ? $filename . ' | ' . $pageSeoArr['title'] . ' | ' . $websiteSeo['name']
            : $filename . ' | ' . $websiteSeo['name'];

        $pageSeo['og_title'] = isset($pageSeoArr['og_title']) && $pageSeoArr['og_title']
            ? $filename . ' | ' . $pageSeoArr['og_title'] . ' | ' . $websiteSeo['name']
            : $filename . ' | ' . $websiteSeo['name'];

        $pageSeo['description'] = (
            isset($pageSeoArr['description'])
            && $pageSeoArr['description']
        )
            ? $pageSeoArr['description']
            : (
                isset($websiteSeo['description']) && $websiteSeo['description']
                ? $websiteSeo['description']
                : false
            );

        $pageSeo['og_description'] = (
            isset($pageSeoArr['og_description'])
            && $pageSeoArr['og_description']
        )
            ? $pageSeoArr['og_description']
            : (
                (
                    isset($websiteSeo['og_description'])
                    && $websiteSeo['og_description']
                )
                ? $websiteSeo['og_description']
                : false
            );

        $pageSeo['keywords'] = (
            isset($pageSeoArr['keywords'])
            && $pageSeoArr['keywords']
        )
            ? $pageSeoArr['keywords']
            : (
                isset($websiteSeo['keywords']) && $websiteSeo['keywords']
                ? $websiteSeo['keywords']
                : false
            );

        $pageSeo['og_image'] = isset($pageSeoArr['og_image']) && $pageSeoArr['og_image']
            ? img('page', $pageSeoArr['og_image'])
            : (
                isset($websiteSeo['og_image']) && $websiteSeo['og_image']
                ? img('other', $websiteSeo['og_image'])
                : false
            );

        return $pageSeo;
    }

}
