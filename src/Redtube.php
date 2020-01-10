<?php

namespace Aethletic\RedtubeApi;

class Redtube
{
    private const BASE_API_URL           = 'https://api.redtube.com/';
    private const DEFAULT_OUTPUT_TYPE    = 'json';
    private const AVAILABLE_OUTPUT_TYPE  = ['json', 'xml'];
    private const AVAILABLE_THUMB_FORMAT = ['medium','small','big','all','medium1','medium2'];

    private static $errorMessage = '';

    /**
     * Get video by id.
     * @param  integer $id Video indetificator.
     * @param  string  $thumbsize Possible values are AVAILABLE_THUMB_FORMAT.
     * @param  string  $output Possible values are AVAILABLE_OUTPUT_TYPE.
     *
     * @return array|string
     */
    public static function getVideo($id = null, $thumbsize = 'all', $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($id, '$id', ['is_null', 'is_numeric'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($thumbsize, '$thumbsize', ['is_null', 'is_thumb'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        $response = self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Videos.getVideoById',
                'video_id'  => $id,
                'thumbsize' => $thumbsize,
                'output'    => $output
            ]
        ), $output);

        $response['video']['url_video'] = !is_array($videoUrl = self::getMp4($response['video']['embed_url'])) ? $videoUrl : null;

        return $response;
    }

    /**
     * Get all categories.
     *
     * @param  string  $output Possible values AVAILABLE_OUTPUT_TYPE.
     *
     * @return array|string
     */
    public static function getCategories($output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        return self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Categories.getCategoriesList',
                'output'    => $output
            ]
        ), $output);
    }

    /**
     * Search video by filter.
     *
     * @param  array   $filter
     * @param  string  $output Possible values AVAILABLE_OUTPUT_TYPE.
     *
     * @return array|string
     */
    public static function search($params = [], $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        return self::execute(
            self::BASE_API_URL . '?' . http_build_query(
            array_merge(['data' => 'redtube.Videos.searchVideos'], $params)
        ),
            $output
        );
    }

    /**
     * Get source video URL (*.mp4) from embed URL.
     *
     * @param  string  $embedUrl Embed URL (like: https://embed.redtube.com/?id=9999999)
     * @param  string  $output Possible values AVAILABLE_OUTPUT_TYPE.
     *
     * @return string|array
     */
    public static function getMp4($embedUrl = null, $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($embedUrl, '$embedUrl', ['is_null', 'is_url'])) {
            return self::$errorMessage;
        }

        $html = self::execute($embedUrl, null);

        preg_match_all('/"quality_480p":"(.+?)"/im', $html, $array);

        if (!array_key_exists('0', $array[1])) {
            return self::response('Oops whoops, URL not found on page.', 99);
        }

        return stripslashes($array[1][0]);
    }

    /* Utils functions */
    private static function validate($target, $varName = 'Variable', $params)
    {
        foreach ($params as $key => $value) {
            if ($value == 'is_null') {
                if ($target == null) {
                    return self::response("$varName not passed, this is a required parameter.", '1');
                }
            }

            if ($value == 'is_thumb') {
                if (!in_array($target, self::AVAILABLE_THUMB_FORMAT)) {
                    return self::response("Unknown $varName ($target) value. Possible values are " . implode(', ', self::AVAILABLE_THUMB_FORMAT) . ".", '2');
                }
            }

            if ($value == 'is_output') {
                if (!in_array($target, self::AVAILABLE_OUTPUT_TYPE)) {
                    return self::response("Unknown $varName ($target) type. Possible values are " . implode(', ', self::AVAILABLE_OUTPUT_TYPE) . ".", '3');
                }
            }

            if ($value == 'is_numeric') {
                if (!is_numeric($target)) {
                    return self::response("$varName is not a numeric.", '3');
                }
            }

            if ($value == 'is_url') {
                if (filter_var($target, FILTER_VALIDATE_URL) === false) {
                    return self::response("$varName not a valid URL.", '4');
                }
            }
        }

        return false;
    }

    private static function response($message = 'Unknown error occurred.', $code = '99')
    {
        return [
            'message' => $message,
            'code'    => $code
        ];
    }

    private static function execute($url, $output = self::DEFAULT_OUTPUT_TYPE)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = ($output == 'json') ? json_decode(curl_exec($ch), true) : curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}
