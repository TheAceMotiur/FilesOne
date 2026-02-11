<?php

namespace App\Helpers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdminHelper
{
    /**
     * Generate user block with user name and user photo
     * @param mixed $name
     * @param mixed $photo
     * @return string
     */
    public static function userBlock(
        mixed $name,
        mixed $photo = false
    ): string {
        $name = isset($name) && trim($name)
            ? $name
            : __('lang.deleted_user');
        if (isset($photo) && $photo) {
            return "<div class='d-flex justify-content-center'>"
                . "<div>"
                . "<div class='avatar covered' "
                . "style='background:url("
                . url($photo)
                . ")'></div>"
                . "</div>"
                . "<div class='my-auto ms-2'>"
                . "<p class='m-0'>{$name}</p>"
                . "</div>"
                . "</div>";
        }
        return "<div class='d-flex justify-content-center'>"
            . "<div>"
            . "<div class='avatar covered' "
            . "style='background:url("
            . url("/assets/image/user-default-sm.webp")
            . ")'></div>"
            . "</div>"
            . "<div class='my-auto ms-2'>"
            . "<p class='m-0'>{$name}</p>"
            . "</div>"
            . "</div>";
    }

    /**
     * Generate user details popover with user id and user email
     * @param string $name
     * @param mixed $id
     * @param mixed $email
     * @return string
     */
    public static function userDetails(
        string $name,
        mixed $id,
        mixed $email,
        bool $visitor
    ): string {
        if (isset($id) && trim($email)) {
            return
                '<span>'
                    . $name
                    . '<i '
                        . 'class="form-help fa-solid fa-circle-question ms-1" '
                        . 'data-bs-container="body" '
                        . 'data-bs-toggle="popover" '
                        . 'data-bs-placement="bottom" '
                        . 'data-bs-html="true" '
                        . 'data-bs-content="'
                        . "<strong>" . __('lang.user_id') . ":</strong> {$id} "
                        . "<strong>" . __('lang.email') . ":</strong> {$email} "
                        . '"></i>'
                . '</span>';
        }

        if ($visitor) {
            return "<span>" . __('lang.visitor') . "</span>";
        }
        return "<span>{$name}</span>";
    }

    /**
     * Generate all users table buttons
     * @param int $userId
     * @return string
     */
    public static function usersTableButtons(
        int $userId,
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/users/edit/{$userId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/users/delete/{$userId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-url='{$deleteUrl}' "
            . "data-bs-target='#delete-modal' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate all users table badges
     * @param int $verified
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public static function usersTableBadges(
        int $verified,
        string $text1,
        string $text2,
    ): string {
        if ($verified == 1) {
            $badge = "<span class='badge-3'>{$text1}</span>";
        } else {
            $badge = "<span class='badge-4'>{$text2}</span>";
        }

        return $badge;
    }

    /**
     * Generate pages table badges
     * @param int $type
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public static function pagesTableBadges(
        int $data,
        string $text1,
        string $text2,
        string $type,
    ): string {
        if ($type == 'status') {
            return $data == 1
                ? "<span class='badge-3'>{$text1}</span>"
                : "<span class='badge-4'>{$text2}</span>";
        } else {
            return $data == 1
                ? "<span class='badge-2'>{$text1}</span>"
                : "<span class='badge-1'>{$text2}</span>";
        }
    }

    /**
     * Generate pages table buttons
     * @param int $pageId
     * @param string $pageUrl
     * @param string $pageKey
     * @param mixed $pageCustom
     * @return string
     */
    public static function pagesTableButtons(
        int $pageId,
        string $pageUrl,
        string $pageKey,
        mixed $pageCustom = false,
    ): string {
        $path = $pageCustom ? 'custom' : 'default';
        $url = $pageUrl != '/' ? $pageUrl : '';
        $fullUrl = $pageCustom ? "p/{$url}" : $url;
        $disabled = in_array($pageKey,['pay','blog_inner','file'])
            ? ' disabled'
            : '';

        $viewUrl = LaravelLocalization::localizeUrl(
            str_contains($pageKey, 'tools_')
                ? pageSlug('tools') . "/{$fullUrl}" 
                : "/{$fullUrl}"
        );
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/pages/{$path}/edit/{$pageId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/pages/custom/delete/{$pageId}"
        );

        $button = "<a href='{$viewUrl}' "
            . "class='btn btn-sm-2{$disabled}' target='_blank' "
            . "aria-label='view'>"
            . "<i class='fa-solid fa-eye fa-fw pe-none'></i></a>";

        $button .= "<a href='{$editUrl}' "
            . "class='btn btn-sm-3 ms-2' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        if ($pageCustom) {
            $button .= "<button type='button' "
                . "class='btn btn-sm-1 delete-row ms-2' aria-label='delete' "
                . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
                . "data-url='{$deleteUrl}'>"
                . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";
        }

        return $button;
    }

    /**
     * Generate blog posts table buttons
     * @param int $postId
     * @param string $postUrl
     * @return string
     */
    public static function blogPostTableButtons(
        int $postId,
        string $postUrl
    ): string {
        $viewUrl = LaravelLocalization::localizeUrl(
            pageSlug('blog_inner', true) . "/" . $postUrl
        );
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/blog/posts/edit/{$postId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/blog/posts/delete/{$postId}"
        );

        $button = "<a href='{$viewUrl}' "
            . "class='btn btn-sm-2' target='_blank' aria-label='view'>"
            . "<i class='fa-solid fa-eye fa-fw pe-none'></i></a>";

        $button .= "<a href='{$editUrl}' "
            . "class='btn btn-sm-3 ms-2' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate blog categories table buttons
     * @param int $categoryId
     * @return string
     */
    public static function blogCategoriesTableButtons(
        int $categoryId,
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/blog/categories/edit/{$categoryId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/blog/categories/delete/{$categoryId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3 ms-2' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate blog comments table buttons
     * @param int $commentId
     * @param string $postUrl
     * @param int $status
     * @return string
     */
    public static function blogCommentsTableButtons(
        int $commentId,
        string $postUrl,
        int $status,
    ): string {
        $viewUrl = LaravelLocalization::localizeUrl(
            pageSlug('blog_inner', true) . "/" . $postUrl
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/blog/comments/delete/{$commentId}"
        );
        if ($status == 0) {
            $verifyUrl = LaravelLocalization::localizeUrl(
                "/admin/blog/comments/verify/{$commentId}"
            );
        }

        $button = '';

        if ($status == 0) {
            $button .= "<button type='button' "
                . "class='btn btn-sm-3 verify-comment me-2' "
                . "data-bs-toggle='modal' data-bs-target='#verify-modal' "
                . "data-url='{$verifyUrl}' aria-label='verify'>"
                . "<i class='fa-solid fa-check fa-fw pe-none'></i></button>";
        }
        $button .= "<a href='{$viewUrl}' "
            . "class='btn btn-sm-2' target='_blank' aria-label='view'>"
            . "<i class='fa-solid fa-eye fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";
        

        return $button;
    }

    /**
     * Generate payment plans table badges
     * @param int $data
     * @param string $text1
     * @param string $text2
     * @param mixed $type
     * @return string
     */
    public static function paymentPlansTableBadges(
        int $data,
        string $text1,
        string $text2,
        mixed $type = false
    ): string {
        if ($type) {
            return $data == 1
                ? "<span class='badge-2'>{$text1}</span>"
                : "<span class='badge-1'>{$text2}</span>";
        }
        return $data == 1
            ? "<span class='badge-3'>{$text1}</span>"
            : "<span class='badge-4'>{$text2}</span>";
    }

    /**
     * Generate payment plans table buttons
     * @param int $planId
     * @return string
     */
    public static function paymentPlansTableButtons(
        int $planId
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/payments/plans/edit/{$planId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/payments/plans/delete/{$planId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate payment logs table buttons
     * @param int $logId
     * @param int $paymentStatus
     * @return string
     */
    public static function paymentLogsTableButtons(
        int $logId,
        int $paymentStatus
    ): string {
        $verifyUrl = LaravelLocalization::localizeUrl(
            "/admin/payments/logs/verify/{$logId}"
        );
        $rejectUrl = LaravelLocalization::localizeUrl(
            "/admin/payments/logs/reject/{$logId}"
        );

        $button = "<button type='button' class='btn btn-sm-2 view-payment-log'"
            . "data-bs-toggle='offcanvas' data-bs-target='#log-details' "
            . "data-id='{$logId}' aria-label='view'>"
            . "<i class='fa-regular fa-eye fa-fw pe-none'></i></button>";

        if ($paymentStatus == 0) {
            $button .= "<button type='button' "
                . "class='btn btn-sm-3 verify-payment ms-2' "
                . "data-bs-toggle='modal' data-bs-target='#verify-modal' "
                . "data-url='{$verifyUrl}' aria-label='verify'>"
                . "<i class='fa-solid fa-check fa-fw pe-none'></i></button>";

            $button .= "<button type='button' "
                . "class='btn btn-sm-1 delete-row ms-2' "
                . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
                . "data-url='{$rejectUrl}' aria-label='delete'>"
                . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";
        }

        return $button;
    }

    /**
     * Generate all payment logs table badges
     * @param int $status
     * @param string $text1
     * @param string $text2
     * @param string $text3
     * @return string
     */
    public static function paymentLogsTableBadges(
        int $status,
        string $text1,
        string $text2,
        string $text3,
    ): string {
        if ($status == 0) {
            $badge = "<span class='badge-4'>{$text2}</span>";
        } elseif ($status == 2) {
            $badge = "<span class='badge-1'>{$text3}</span>";
        } else {
            $badge = "<span class='badge-3'>{$text1}</span>";
        }

        return $badge;
    }

    /**
     * Generate email contents table buttons
     * @param int $emailId
     * @return string
     */
    public static function emailContentsTableButtons(
        int $emailId
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/emails/contents/edit/{$emailId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3' aria-label='edit'>"
            . "<i class='fa-solid fa-pencil fa-fw pe-none'></i></a>";

        return $button;
    }

    /**
     * Generate email logs table buttons
     * @param int $logId
     * @return string
     */
    public static function emailLogsTableButtons(
        int $logId
    ): string {
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/emails/contact/delete/{$logId}"
        );

        $button = "<button type='button' "
            . "class='btn btn-sm-2 view-contact-email' "
            . "data-bs-toggle='offcanvas' data-bs-target='#contact-email' "
            . "data-id='{$logId}' aria-label='view'>"
            . "<i class='fa-regular fa-eye fa-fw pe-none'></i></button>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate subscribers table data badges
     * @param int $status
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public static function subscribersTableBadges(
        int $status,
        string $text1,
        string $text2,
    ): string {
        return $status == 1
            ? "<span class='badge-3'>{$text1}</span>"
            : "<span class='badge-4'>{$text2}</span>";
    }

    /**
     * Generate subscribers table buttons
     * @param int $subscriberId
     * @return string
     */
    public static function subscribersTableButtons(
        int $subscriberId
    ): string {
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/subscribers/delete/{$subscriberId}"
        );

        $button = "<button type='button' class='btn btn-sm-1 delete-row' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate files table buttons
     * @param string $filekey
     * @return string
     */
    public static function filesTableButtons(
        string $filekey
    ): string {
        $copyUrl = LaravelLocalization::localizeUrl(
            pageSlug('file') . "/" . $filekey
        );

        $downloadUrl = LaravelLocalization::localizeUrl(
            "admin/files/all/download/{$filekey}"
        );

        $deleteUrl = LaravelLocalization::localizeUrl(
            "admin/files/all/delete/{$filekey}"
        );

        $button = "<a href='#' "
            . "class='btn btn-sm-2 copy-this' "
            . "data-copy='{$copyUrl}' aria-label='copy'>"
            . "<i class='fa-solid fa-link fa-fw pe-none'></i></a>";

        $button .= "<a href='{$downloadUrl}' "
            . "class='btn btn-sm-3' aria-label='download'>"
            . "<i class='fa-solid fa-download fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate files table data badges
     * @param string $storage
     * @return string
     */
    public static function filesTableBadges(
        string $storage,
    ): string {
        if ($storage == 'default') {
            return "<span class='badge-4'>" . __('lang.local') . "</span>";
        }

        return "<span class='badge-4'>{$storage}</span>";
    }

    /**
     * Generate reports table buttons
     * @param int $reportId
     * @param mixed $filekey
     * @return string
     */
    public static function reportsTableButtons(
        int $reportId,
        mixed $filekey,
    ): string {
        $button = "<button type='button' class='btn btn-sm-2 view-report-log'"
            . "data-bs-toggle='offcanvas' data-bs-target='#report-details' "
            . "data-id='{$reportId}' aria-label='view'>"
            . "<i class='fa-regular fa-eye fa-fw pe-none'></i></button>";

        if ($filekey) {
            $copyUrl = LaravelLocalization::localizeUrl(
                pageSlug('file') . "/" . $filekey
            );
            $button .= "<a href='#' "
                . "class='btn btn-sm-1 copy-this' "
                . "data-copy='{$copyUrl}' aria-label='copy'>"
                . "<i class='fa-solid fa-link fa-fw pe-none'></i></a>";
            $downloadUrl = LaravelLocalization::localizeUrl(
                "admin/files/all/download/{$filekey}"
            );
            $button .= "<a href='{$downloadUrl}' "
                . "class='btn btn-sm-3' aria-label='download'>"
                . "<i class='fa-solid fa-download fa-fw pe-none'></i></a>";
        }

        $deleteUrl = LaravelLocalization::localizeUrl(
            "admin/files/reports/delete/{$reportId}"
        );
        $button .= "<button type='button' class='btn btn-sm-1 delete-row' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }
    
    /**
     * Generate reports table data badges
     * @param string $storage
     * @return string
     */
    public static function reportsTableBadges(
        string $storage,
    ): string {
        if ($storage == 'default') {
            return "<span class='badge-4'>" . __('lang.local') . "</span>";
        }

        return "<span class='badge-4'>{$storage}</span>";
    }

    /**
     * Generate payout rates table buttons
     * @param int $rateId
     * @return string
     */
    public static function payoutRatesTableButtons(
        int $rateId,
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/affiliate/payout-rates/edit/{$rateId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/affiliate/payout-rates/delete/{$rateId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3 ms-2' aria-label='edit'>"
            . "<i class='fa-solid fa-pen fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }

    /**
     * Generate withdrawal methods table buttons
     * @param int $methodsId
     * @return string
     */
    public static function methodsTableButtons(
        int $methodsId,
    ): string {
        $editUrl = LaravelLocalization::localizeUrl(
            "/admin/affiliate/withdrawal-methods/edit/{$methodsId}"
        );
        $deleteUrl = LaravelLocalization::localizeUrl(
            "/admin/affiliate/withdrawal-methods/delete/{$methodsId}"
        );

        $button = "<a href='{$editUrl}' "
            . "class='btn btn-sm-3 ms-2' aria-label='edit'>"
            . "<i class='fa-solid fa-pen fa-fw pe-none'></i></a>";

        $button .= "<button type='button' class='btn btn-sm-1 delete-row ms-2' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "data-url='{$deleteUrl}' aria-label='delete'>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";

        return $button;
    }
    
    /**
     * Generate withdrawals table data badges
     * @param int $status
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public static function methodsTableBadges(
        int $status,
        string $text1,
        string $text2,
    ): string {
        if ($status == 1) {
            return "<span class='badge-3'>{$text1}</span>";
        } else {
            return "<span class='badge-4'>{$text2}</span>";
        }
    }

    /**
     * Generate withdrawals table buttons
     * @param int $withdrawalId
     * @param int $status
     * @return string
     */
    public static function withdrawalsTableButtons(
        int $withdrawalId,
        int $status,
    ): string {
        if ($status == 0) {
            $verifyUrl = LaravelLocalization::localizeUrl(
                "/admin/withdrawals/verify/{$withdrawalId}"
            );
            $rejectUrl = LaravelLocalization::localizeUrl(
                "/admin/withdrawals/reject/{$withdrawalId}"
            );
        }

        $button = "<button type='button' class='btn btn-sm-2 "
            . "view-withdrawal-log' "
            . "data-bs-toggle='offcanvas' data-bs-target='#withdrawal-details' "
            . "data-id='{$withdrawalId}' aria-label='view'>"
            . "<i class='fa-regular fa-eye fa-fw pe-none'></i></button>";

        if ($status == 0) {
            $button .= "<button type='button' "
                . "class='btn btn-sm-3 verify-withdrawal ms-2' "
                . "data-bs-toggle='modal' data-bs-target='#verify-modal' "
                . "data-url='{$verifyUrl}' aria-label='verify'>"
                . "<i class='fa-solid fa-check fa-fw pe-none'></i></button>";

            $button .= "<button type='button' "
                . "class='btn btn-sm-1 delete-row ms-2' "
                . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
                . "data-url='{$rejectUrl}' aria-label='delete'>"
                . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";
        }

        return $button;
    }
    
    /**
     * Generate withdrawals table data badges
     * @param int $status
     * @param string $text1
     * @param string $text2
     * @param string $text3
     * @return string
     */
    public static function withdrawalsTableBadges(
        int $status,
        string $text1,
        string $text2,
        string $text3,
    ): string {
        if ($status == 0) {
            return "<span class='badge-4'>{$text1}</span>";
        } elseif ($status == 1) {
            return "<span class='badge-3'>{$text2}</span>";
        } else {
            return "<span class='badge-1'>{$text3}</span>";
        }
    }

}
