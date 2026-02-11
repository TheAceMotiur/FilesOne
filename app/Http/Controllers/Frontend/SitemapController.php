<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function sitemap(): mixed
    {
        $sitemap = $this->urls();
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function urls(): mixed
    {
        // Array to store URLs
        $urls = [];
        $now = Carbon::now()->toAtomString();

        // Add static pages
        $urls[] = [
            'loc' => URL::to('/'),
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        $urls[] = [
            'loc' => URL::to(pageSlug('pricing', true)),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => URL::to(pageSlug('blog', true)),
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '0.8'
        ];

        // Add dynamic pages
        $postSlug = pageSlug('blog_inner', true);
        foreach (BlogPost::all() as $post) {
            $urls[] = [
                'loc' => URL::to("{$postSlug}/{$post->slug}"),
                'lastmod' => $post->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ];
        }

        $urls[] = [
            'loc' => URL::to(pageSlug('affiliate', true)),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => URL::to(pageSlug('contact', true)),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => URL::to(pageSlug('terms_of_use', true)),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => URL::to(pageSlug('privacy_policy', true)),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        // Generate XML
        return $this->generateSitemap($urls);
    }

    private function generateSitemap($urls): mixed
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $urlTag = $xml->addChild('url');
            $urlTag->addChild('loc', $url['loc']);
            $urlTag->addChild('lastmod', $url['lastmod']);
            $urlTag->addChild('changefreq', $url['changefreq']);
            $urlTag->addChild('priority', $url['priority']);
        }

        return $xml->asXML();
    }

}
