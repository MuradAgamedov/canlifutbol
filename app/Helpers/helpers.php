<?php

use Illuminate\Support\Facades\Route;
use App\Models\Page;

if (!function_exists('get_page')) {
    function get_page($code)
    {
        return Page::where("code", $code)->first();
    }
}

if (!function_exists('get_page_seo')) {
    function get_page_seo($code)
    {
        $page = get_page($code);

        $title = $page->seo_title ?? $page->title ?? config('app.name');
        $description = $page->seo_description ?? '';
        $keywords = $page->seo_keywords ?? '';

        return <<<HTML
        <title>$title</title>
        <meta name="description" content="$description">
        <meta name="keywords" content="$keywords">
        HTML;
    }
}

if (!function_exists('get_details_page_seo')) {
    function get_details_page_seo($model)
    {
        $title = $model->seo_title ?? $model->title ?? config('app.name');
        $description = $model->seo_description ?? '';
        $keywords = $model->keywords ?? '';

        return <<<HTML
        <title>$title</title>
        <meta name="description" content="$description">
        <meta name="keywords" content="$keywords">
        HTML;
    }
}

if (!function_exists('get_page_tags')) {
    function get_page_tags($code, $type)
    {
        $page = get_page($code);

        if (!$page) {
            return '';
        }

        $content = '';

        if ($type === 'scripts' && !empty($page->page_header)) {
            $content = $page->seo_scripts;
        } elseif ($type === 'links' && !empty($page->page_footer)) {
            $content = $page->seo_links;
        }

        return $content;
    }
}

if (!function_exists('get_details_page_tags')) {
    function get_details_page_tags($model, $type)
    {
        $content = '';

        if ($type === 'scripts' && !empty($model->seo_scripts)) {
            $content = $model->seo_scripts;
        } elseif ($type === 'links' && !empty($model->seo_links)) {
            $content = $model->seo_links;
        }

        return $content;
    }
}

if (!function_exists('get_image')) {
    /**
     * Şəkili əldə etmək üçün funksiyanı yaradır.
     *
     * @param string $imagePath
     * @return string
     */
    function get_image($imagePath)
    {
        $url = \Illuminate\Support\Facades\Storage::url($imagePath);

        // Windows slash problemini həll edək
        return str_replace('\\', '/', $url);
    }
}




if (!function_exists('get_price')) {
    /**
     * Qiyməti endirimlə birlikdə formatlı şəkildə qaytarır.
     *
     * @param float $price
     * @param float|null $discountPercent
     * @return string
     */
    if (!function_exists('get_price')) {
        /**
         * Qiyməti endirimlə birlikdə div ilə birlikdə qaytarır.
         *
         * @param float $price
         * @param float|null $discountPercent
         * @return string
         */
        function get_price($price, $discountPercent = null)
        {
            if (!$price || $price <= 0) {
                return '';
            }

            $output = '<div class="rbt-price">';

            // Endirim faizinin 0-dan böyük olub olmadığını dəqiq yoxlayırıq
            if (!is_null($discountPercent) && floatval($discountPercent) > 0) {
                $discountedPrice = $price - ($price * $discountPercent / 100);
                $output .= '<span class="current-price">₼' . round($discountedPrice, 2) . '</span>';
                $output .= ' <span class="off-price">₼' . round($price, 2) . '</span>';
            } else {
                $output .= '<span class="current-price">₼' . round($price, 2) . '</span>';
            }

            $output .= '</div>';

            return $output;
        }
    }
}


if (!function_exists('translate_value')) {
    function translate_value(string $code): ?string
    {
        $lang = $lang ?? app()->getLocale(); // əgər dil göndərilməyibsə, cari dili istifadə et

        return \App\Models\Translate::where('code', $code)->value('value');
    }
}
if (!function_exists('get_page_banner')) {
    /**
     * Page code əsasında səhifənin banner image-ni qaytarır.
     *
     * @param string $code
     * @return string|null
     */
    function get_page_banner(string $code): ?string
    {
        $page = \App\Models\Page::where('code', $code)->first();

        if ($page && $page->banner_image) {
            return get_image($page->banner_image); // Əgər Storage istifadə olunubsa
        }

        return null;
    }
}
