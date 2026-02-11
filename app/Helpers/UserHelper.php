<?php

namespace App\Helpers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserHelper
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
            "user/files/download/{$filekey}"
        );

        $deleteUrl = LaravelLocalization::localizeUrl(
            "user/files/delete/{$filekey}"
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
     * Generate files table buttons
     * @param int $withdrawalId
     * @param int $status
     * @return string
     */
    public static function withdrawalsTableButtons(
        int $withdrawalId,
        int $status
    ): string {
        $cancelUrl = LaravelLocalization::localizeUrl(
            "user/affiliate/withdrawal/cancel/{$withdrawalId}"
        );
        $disabled = $status == 0 ? '' : ' disabled';
        return "<button type='button' "
            . "class='btn btn-sm-1 delete-row{$disabled}' "
            . "data-bs-toggle='modal' data-bs-target='#delete-modal' "
            . "aria-label='delete' "
            . "data-url='{$cancelUrl}'$disabled>"
            . "<i class='fa-solid fa-xmark fa-fw pe-none'></i></button>";
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

    /**
     * Generate payment logs table buttons
     * @param int $logId
     * @param int $paymentStatus
     * @return string
     */
    public static function paymentLogsTableButtons(
        int $logId,
    ): string {
        $button = "<button type='button' class='btn btn-sm-2 view-payment-log'"
            . "data-bs-toggle='offcanvas' data-bs-target='#log-details' "
            . "data-id='{$logId}' aria-label='view'>"
            . "<i class='fa-regular fa-eye fa-fw pe-none'></i></button>";
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

}
