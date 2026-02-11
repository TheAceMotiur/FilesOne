<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Affiliate;
use App\Models\Analytics;
use Illuminate\Support\Carbon;

class AnalyticsHelper
{
    /**
     * Save new analytics data
     * @param int $fileId
     * @param string $userIp
     * @return mixed
     */
    public static function makeAnalytics(
        int $fileId,
        string $userIp
    ): bool {
        $fileData = DB::table('files')
            ->where('id', $fileId)
            ->first();

        if ($fileData) {

            $sessionKey = "{$userIp}_{$fileData->id}";

            $check = Analytics::where('value', $sessionKey)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->first();

            if ($check) {
                DB::table('files')
                    ->where('id', $fileId)
                    ->update([
                        'pageview' => $fileData->pageview + 1,
                    ]);
            } else {
                DB::table('files')
                    ->where('id', $fileId)
                    ->update([
                        'pageview' => $fileData->pageview + 1,
                        'unique_pageview' => $fileData->unique_pageview + 1,
                    ]);

                Analytics::create([
                    'file_id' => $fileId,
                    'file_name' => $fileData->filename,
                    'uploader' => $fileData->created_by_id ?? null,
                    'value' => $sessionKey,
                ]);
            }
        }

        return true;
    }

    /**
     * Save new analytics data
     * @param int $fileId
     * @return mixed
     */
    public static function makeDownloadAnalytics(
        int $fileId,
    ): bool {
        $fileData = DB::table('files')
            ->where('id', $fileId)
            ->first();

        if ($fileData) {
            DB::table('files')
                ->where('id', $fileId)
                ->update([
                    'download' => ($fileData->download ?? 0) + 1,
                ]);
        }

        return true;
    }

    /**
     * Save new analytics data
     * @param int $fileId
     * @param string $userIp
     * @return mixed
     */
    public static function makeAffiliate(
        int $fileId,
        string $userIp
    ): bool {
        $fileData = DB::table('files')
            ->where('id', $fileId)
            ->first();

        if ($fileData) {

            $sessionKey = "{$userIp}_{$fileData->id}";

            $check = Affiliate::where('value', $sessionKey)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->first();

            if (!$check) {
                $userAgent = request()->header('User-Agent');
                if (isset($userAgent) && $userAgent) {
                    $locationData = self::getLocation(
                        $userIp, 
                        $userAgent
                    );
                    Affiliate::create([
                        'file_id' => $fileId,
                        'file_name' => $fileData->filename,
                        'uploader' => $fileData->created_by_id ?? null,
                        'value' => $sessionKey,
                        'additional' => $locationData 
                            ? json_encode($locationData) 
                            : NULL,
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * Get analytics data from db
     * @param int $fileId
     * @return mixed
     */
    public static function getAnalytics(
        int $fileId
    ): array {

        $fileData = DB::table('files')
            ->where('id', $fileId)
            ->first();

        if ($fileData) {
            return [
                $fileData->pageview ?: 0,
                $fileData->unique_pageview ?: 0,
                $fileData->download ?: 0,
            ];
        }

        return [0,0];
    }

    /**
     * Get user location data
     * @param string $userIp
     * @param string $userAgent
     * @return mixed
     */
    private static function getLocation(
        string $userIp, 
        string $userAgent
    ): array|bool {
        try {
            if (!self::isBot($userAgent)) {
                $token = DB::table('settings')
                    ->where('name', 'ip2location_token')
                    ->first()->value;

                if ($token == null || !$token) {
                    return false;
                }

                $data = json_decode(
                    @file_get_contents(
                        "https://api.ip2location.io/?key={$token}&ip={$userIp}"
                    ), true
                );
                if (isset($data['country_code']) && $data['country_code']) {
                    return [
                        'city' => $data['city_name'] ?? '-',
                        'countryCode' => $data['country_code'] ?? '-',
                        'countryName' => $data['country_name'] ?? '-',
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                    ];
                }

                return false;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check the given user is a bot
     * @param string $userAgent
     * @return mixed
     */
    private static function isBot(
        string $userAgent
    ): bool|int {
        $botRegexPattern = "(googlebot\/|Googlebot\-Mobile|Googlebot\-Image"
        . "|Google favicon|Mediapartners\-Google|bingbot|slurp|java|wget|curl"
        . "|Commons\-HttpClient|Python\-urllib|libwww|httpunit|nutch|phpcrawl"
        . "|msnbot|jyxobot|FAST\-WebCrawler|FAST Enterprise Crawler|biglotron|"
        . "teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|"
        . "GingerCrawler|webmon |httrack|webcrawler|grub\.org|"
        . "UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum"
        . "\.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips\-agent|"
        . "tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|"
        . "purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|"
        . "citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss"
        . "|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail\.RU_Bot|"
        . "discobot|heritrix|findthatfile|europarchive\.org|NerdByNature\.Bot|"
        . "sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify"
        . "|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|"
        . "intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|"
        . "lb\-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|"
        . "lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider"
        . "|gnam gnam spider|web\-archive\-net\.com\.bot|backlinkcrawler|"
        . "coccoc|integromedb|content crawler spider|toplistbot|seokicks\-robot"
        . "|it2media\-domain\-crawler|ip\-web\-crawler\.com|siteexplorer\.info|"
        . "elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki"
        . "\-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|"
        . "Lipperhey SEO Service|CC Metadata Scaper|g00g1e\.net|"
        . "GrapeshotCrawler|urlappendbot|brainobot|fr\-crawler|binlar|"
        . "SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf\.fr_bot|"
        . "A6\-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|"
        . "MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|"
        . "archive\.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot"
        . "|yoozBot|lipperhey|y!j\-asr|Domain Re\-Animator Bot|AddThis|"
        . "YisouSpider|BLEXBot|YandexBot|SurdotlyBot|AwarioRssBot|FeedlyBot|"
        . "Barkrowler|Gluten Free Crawler|Cliqzbot)";
     
        return preg_match("/{$botRegexPattern}/", $userAgent);
    }

    /**
     * Get all stats for graphs
     * @param int $userId
     * @return mixed
     */
    public static function stats(
        int $userId
    ): array {

        $userData = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$userData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $revenueData = DB::table('file_affiliate')
            ->when($userData->type == 1, function ($query) use ($userId) {
                $query->where('file_affiliate.uploader', $userId);
            })
            ->orderBy('file_affiliate.created_at','desc')
            ->get();

        $ratesData = DB::table('payout_rates')
            ->select('country_code', 'rate')
            ->get();

        $ratesArr = [];
        if ($ratesData->isNotEmpty()) {
            foreach ($ratesData as $rates) {
                $ratesArr[$rates->country_code] = $rates->rate;
            }
        }

        $revenueArr = [];
        $totalRevenue = 0;
        if ($revenueData->isNotEmpty()) {
            foreach ($revenueData as $revenue) {
                $revenueData = json_decode($revenue->additional, true);
                $revenueArr[] = [
                    'date' => $revenue->created_at,
                    'fileName' => $revenue->file_name ?? __('lang.deleted_file'),
                    'revenueData' => $revenueData,
                    'revenue' => isset($revenueData['countryCode']) && isset($ratesArr[$revenueData['countryCode']])
                        ? (($ratesArr[$revenueData['countryCode']] / 1000) ?? 0)
                        : 0,
                ];
                $totalRevenue += isset($revenueData['countryCode']) && isset($ratesArr[$revenueData['countryCode']])
                    ? (($ratesArr[$revenueData['countryCode']] / 1000) ?? 0)
                    : 0;
            }
        }

        $payments = DB::table('withdrawals')
            ->where('created_by_id', $userId)
            ->where('status', 1)
            ->get();
        $paymentsTotal = 0;
        if ($payments->isNotEmpty()) {
            foreach ($payments as $payment) {
                $paymentsTotal += $payment->amount;
            }
        }

        $totalRevenue = isset($paymentsTotal) && $paymentsTotal 
            ? $totalRevenue - $paymentsTotal
            : $totalRevenue;

        return [
            true,
            $revenueArr,
            $totalRevenue > 0 ? round($totalRevenue, 6) : 0,
        ];
    }

    /**
     * Get all stats of all users
     * @return mixed
     */
    public static function statsAll(): array {

        $usersData = DB::table('users')
            ->where('type',1)
            ->get();

        $usersArr = [];

        if ($usersData->isNotEmpty()) {
            foreach ($usersData as $userData) {

                $userId = $userData->id;

                $revenueData = DB::table('file_affiliate')
                    ->when($userData->type == 1, function ($query) use ($userId) {
                        $query->where('file_affiliate.uploader', $userId);
                    })
                    ->orderBy('file_affiliate.created_at','desc')
                    ->get();
        
                $ratesData = DB::table('payout_rates')
                    ->select('country_code', 'rate')
                    ->get();
        
                $ratesArr = [];
                if ($ratesData->isNotEmpty()) {
                    foreach ($ratesData as $rates) {
                        $ratesArr[$rates->country_code] = $rates->rate;
                    }
                }
        
                $totalRevenue = 0;
                if ($revenueData->isNotEmpty()) {
                    foreach ($revenueData as $revenue) {
                        if (isset($revenue->additional)) {
                            $revenueData = json_decode($revenue->additional, true);
                            $cCode = $revenueData['countryCode'];
                            $totalRevenue += isset($cCode) && isset($ratesArr[$cCode])
                                ? (($ratesArr[$cCode] / 1000) ?? 0)
                                : 0;
                        }
                    }
                }
        
                $payments = DB::table('withdrawals')
                    ->where('created_by_id', $userId)
                    ->where('status', 1)
                    ->get();
                $paymentsTotal = 0;
                if ($payments->isNotEmpty()) {
                    foreach ($payments as $payment) {
                        $paymentsTotal += $payment->amount;
                    }
                }
        
                $totalRevenue = isset($paymentsTotal) && $paymentsTotal 
                    ? $totalRevenue - $paymentsTotal
                    : $totalRevenue;

                $usersArr[] = [
                    'id' => $userId,
                    'email' => $userData->email,
                    'revenue' => $totalRevenue > 0 
                        ? round($totalRevenue, 6) 
                        : 0,
                ];
            }
        }

        return [
            true,
            $usersArr
        ];
    }

    /**
     * Get monthly stats for graphs
     * @param int $userId
     * @return mixed
     */
    public static function monthlyStats(
        int $userId
    ): array {

        $userData = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$userData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $months = [];
        for ($m = 0; $m <= 11; $m++) {
            $month = Carbon::today()->startOfMonth()->subMonth($m);
            for ($i = 1; $i <= 3; $i++) {
                $months[$month->month][0] = $month->shortMonthName;
                $months[$month->month][$i] = 0;
            }
        }

        $revenueData = DB::table('file_analytics')
            ->select(
                DB::raw(
                    "month(`file_analytics`.`created_at`) AS monthNumber"
                ),
                DB::raw(
                    "monthname(`file_analytics`.`created_at`) AS monthName"
                ),
                DB::raw(
                    'COUNT(`file_analytics`.`id`) AS count'
                )
            )
            ->where('file_analytics.uploader', $userId)
            ->get()
            ->groupBy(function ($item)
            {
                return $item->monthNumber;
            })->toArray();

        $data = [];
        foreach ($months as $monthNumber => $dataBlock) {
            $data[$dataBlock[0]] = $revenueData[$monthNumber][0]->count ?? 0;
        }

        return $data;

    }

}
