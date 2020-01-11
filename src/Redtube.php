<?php

namespace Aethletic\RedtubeApi;

/**
 * Redtube API Wrapper
 *
 * @package Redtube
 * @author  aethletic <hello@h3ro.ru>
 * @version 1.0.0
 *
 * @see     https://github.com/aethletic/redtube-api
 */
class Redtube
{
    private const BASE_API_URL           = 'https://api.redtube.com/';
    private const DEFAULT_OUTPUT_TYPE    = 'json';
    private const AVAILABLE_OUTPUT_TYPE  = ['json', 'xml'];
    private const AVAILABLE_THUMB_FORMAT = ['medium','small','big','all','medium1','medium2'];

    private static $errorMessage = '';

    /**
     * Get video by id
     *
     * Retrieves additional information about specific video by $id parameter.
     *
     * @param  integer $id          Video indetificator.
     * @param  string  $thumbsize   Thumb size, possible values are AVAILABLE_THUMB_FORMAT.
     * @param  string  $output      Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function getVideoById($id = null, $thumbsize = 'all', $output = self::DEFAULT_OUTPUT_TYPE)
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

        // if output xml
        if (!is_array($response)) {
            return $response;
        }

        $response['video']['url_video'] = !is_array($videoUrl = self::getVideoMp4($response['video']['embed_url'])) ? $videoUrl : null;

        return $response;
    }

    /**
     * Get all categories
     *
     * Retrieves all available categories.
     *
     * @param  string  $output Output type, possible values AVAILABLE_OUTPUT_TYPE
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
     * Get all tags
     *
     * Retrieves all tags available.
     *
     * @param  string  $output Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function getTags($output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        return self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Tags.getTagList',
                'output'    => $output
            ]
        ), $output);
    }

    /**
     * Get all pronstars
     *
     * Retrieves all pornstars available.
     * If pass $isDetailedList (not NULL or FALSE), retrieves
     * all pornstars available with details (page url and star's thumb).
     *
     * @param  mixed   $isDetailedList Detailed list
     * @param  integer $page           Page of list
     * @param  string  $output         Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function getStars($isDetailedList = null, $page = '1', $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($page, '$page', ['is_numeric'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        $callMethod = $isDetailedList !== null && $isDetailedList !== false ? 'redtube.Stars.getStarDetailedList' : 'redtube.Stars.getStarList';

        return self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => $callMethod,
                'page'      => $page,
                'output'    => $output
            ]
        ), $output);
    }

    /**
     * Get all deleted videos
     *
     * Retrieves all deleted videos.
     *
     * @param  integer $page   Page of list
     * @param  string  $output Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function getDeletedVideos($page = '1', $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($page, '$page', ['is_numeric'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        return self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Videos.getDeletedVideos',
                'page'      => $page,
                'output'    => $output
            ]
        ), $output);
    }

    /**
     * Search video by filter
     *
     * Retrieves video list, can be filtered by multiple parameters,
     * including the possibility to query the API for videos containing a specific
     * string in the title or description.
     *
     * @param  array   $filter Filter params
     * @param  string  $output Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function searchVideo($params = [], $output = self::DEFAULT_OUTPUT_TYPE)
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
     * Checks active video or deleted
     *
     * Retrieves state of a specific video specified by video_id parameter,
     * which is useful in order to keep your embedded videos up to date.
     *
     * @param  integer $id      Video indetificator
     * @param  string  $output  Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function isVideoActive($id = null, $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($id, '$id', ['is_null', 'is_numeric'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        return self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Videos.isVideoActive',
                'video_id'  => $id,
                'output'    => $output
            ]
        ), $output);
    }

    /**
     * Get embed video code & url by video id
     *
     * Retrieves embed code (BASE64 encoded) for specific video by video_id parameter,
     * which is useful to automatically embed videos.
     *
     * @param  integer $id      Video indetificator
     * @param  string  $output  Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return array|string
     */
    public static function getVideoEmbed($id = null, $output = self::DEFAULT_OUTPUT_TYPE)
    {
        if (self::$errorMessage = self::validate($id, '$id', ['is_null', 'is_numeric'])) {
            return self::$errorMessage;
        }

        if (self::$errorMessage = self::validate($output, '$output', ['is_null', 'is_output'])) {
            return self::$errorMessage;
        }

        $response = self::execute(self::BASE_API_URL . '?' . http_build_query(
            [
                'data'      => 'redtube.Videos.getVideoEmbedCode',
                'video_id'  => $id,
                'output'    => $output
            ]
        ), $output);

        // if output xml
        if (!is_array($response)) {
            return $response;
        }

        if (array_key_exists('code', $response)) {
            return $response;
        }

        $newResponse['code'] = $response['embed']['code'];
        $newResponse['embed_url'] = base64_decode($newResponse['code']);

        return $newResponse;
    }


    /**
     * Get source video URL (*.mp4) from embed URL
     *
     * @param  string  $embedUrl Embed URL (like: https://embed.redtube.com/?id=9999999)
     * @param  string  $output   Output type, possible values AVAILABLE_OUTPUT_TYPE
     *
     * @return string|array
     */
    public static function getVideoMp4($embedUrl = null, $output = self::DEFAULT_OUTPUT_TYPE)
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
