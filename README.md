## PHP API Wrapper for RedTube.com 🍌

### Requirements

PHP >= 5.4.x

### Install
Composer:

`$ composer require aethletic/redtube-api`

Clone or download:

`$ git clone https://github.com/aethletic/redtube-api.git`

Use:
    
    require_once __DIR__ . '/vendor/autoload.php';
    
    // if download or clone
    // require_once __DIR__ . '/src/Redtube.php';
    
    $redtube = new Aethletic\RedtubeApi\Redtube;

#### Example
```php
/*
    thumbsizes: 'medium','small','big','all','medium1','medium2' (default: all)
    output: 'json', 'xml' (default: json)
*/

/* Get video by ID */
print_r($redtube::getVideoById('9535301', 'big', 'json'));
// ['video']['url_video'] only if output JSON
// [video] => Array
//         (
//             [duration] => 9:05
//             [views] => 334416
//             [video_id] => 9535301
//             [rating] => 69.7635
//             [ratings] => 592
//             [title] => LOAN4K. Blonde lassie gives herself to agent in office in loan porn
//             [url] => https://www.redtube.com/9535301
//             [embed_url] => https://embed.redtube.com/?id=9535301
//             [default_thumb] => https://ei.rdtcdn.com/m=e0YH8f/media/videos/201808/17/9535301/original/12.jpg
//             [thumb] => https://ei.rdtcdn.com/m=e0YH8f/media/videos/201808/17/9535301/original/12.jpg
//             [publish_date] => 2018-08-17 18:52:51
//             [thumbs] => Array
//                 (
//                     [0] => Array
//                         (
//                             [size] => big
//                             [width] => 432
//                             [height] => 324
//                             [src] => https://ei.rdtcdn.com/m=eWgr9f/media/videos/201808/17/9535301/original/1.jpg
//                         )
//                 )
//
//             [tags] => Array
//                 (
//                     [0] => Amateur
//                     [1] => Blonde
//                     [2] => Blowjob
//                     [3] => Casting
//                     [4] => Couple
//                     [5] => Czech
//                     [6] => HD
//                     [7] => High Heels
//                     [8] => Natural Tits
//                     [9] => Office
//                     [10] => Petite
//                     [11] => POV
//                     [12] => Teen
//                     [13] => Vaginal Sex
//                 )
//
//             [url_video] => https://ee.rdtcdn.com/media/videos/201808/17/9535301/480P_600K_9535301.mp4?validfrom=1578736067&validto=1578743267&rate=78k&burst=1400k&hash=Lq5NyA8wRjLt4z2knrDxHRIjRM0%3D
//         )

/* Get embed video code & url */
// print_r($redtube::getVideoEmbed('9535301'));
// ['embed_url'] only if output JSON
// Array
// (
//     [code] => aHR0cHM6Ly9lbWJlZC5yZWR0dWJlLmNvbS8/aWQ9OTUzNTMwMQ==
//     [embed_url] => https://embed.redtube.com/?id=9535301
// )

/* Get source video link (mp4) for download */
print_r($redtube::getVideoMp4('https://embed.redtube.com/?id=9535301'));
// https://ce.rdtcdn.com/media/videos/201808/17/9535301/480P_600K_9535301.mp4?mEK8rrMJDQAtZZnHE-Kl1lQWZWia28noH4Gi3Y5NEKNKndaFkIS4bRUvMn0kZU8CLufOXVs5HMSS7_Ur_-vqFmMSrny7GLHItGirKorAjTxVX6JmuO2iR_-EY5NKtLl99wKCyIDDaI0qJ5iMelw0Pijt_hC1InAtgEy0XH8rUSIAf-_DcfESFzc1l3hEqZZK2caD9yuvt18

/* Search video with filter */
print_r($redtube::searchVideo([
    'search'    => 'swap',
    'category'  => 'teens',         // see: getCategories()
    'tags'      => ['czech'],       // see: getTags()
    'stars'     => [''],            // see: getStars()
    'ordering'  => 'mostviewed',    // newest, mostviewed, rating
    'period'    => 'weekly',        // only if set "ordering": weekly, monthly, alltime
    'page'      => '1',
    'thumbsize' => 'big',
]));

/* Get all categories */
print_r($redtube::getCategories());

/* Get all tags */
print_r($redtube::getTags());

/* Get the names of all actors (stars) */
print_r($redtube::getStars()); // list of only names
// output: xml
print_r($redtube::getStars(false, 0, 'xml'));

/* Get detailed list of actors (stars) */
print_r($redtube::getStars(true, 3));

/* Video is active*/
print_r($redtube::isVideoActive(123563));

/* Get deleted videos */
print_r($redtube::getDeletedVideos());
```
