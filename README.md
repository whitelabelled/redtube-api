## PHP API Wrapper for RedTube.com 🍌

## Installation
Composer:

```bash
$ composer require chipslays/redtube-api
```

Clone or download:

```bash
$ git clone https://github.com/chipslays/redtube-api.git
```

Use:
    
    require_once __DIR__ . '/vendor/autoload.php';
    
    // if download or clone
    // require_once __DIR__ . '/src/Redtube.php';
    
    $redtube = new Aethletic\RedtubeApi\Redtube;

## Examples
```php
/*
    thumbsizes: 'medium','small','big','all','medium1','medium2' (default: all)
    output: 'json', 'xml' (default: json)
*/

/* Get video by ID */
print_r($redtube::getVideoById('9535301', 'big', 'json'));

/* Get embed video code & url */
print_r($redtube::getVideoEmbed('9535301'));

/* Get source video link (mp4) for download */
print_r($redtube::getVideoMp4('https://embed.redtube.com/?id=9535301'));
// https://ce.rdtcdn.com/media/videos/201808/17/9535301/480P_600K_9535301.mp4?mEK8rrMJDQAtZZnHE-Kl1lQWZWia28noH4Gi3Y5NEKNKndaFkISJjs8sHahzufOXVs5HMSS7_Ur_-vqFmMSrOspZcaKorAjTxVX6JmuO2iR_-EY5NKtLl9as98a8ff7aaAfsw0Pijt_hC1InAtgEHHah7f7SIAf-_DcfESFzc1l3hEqZZK2caD9yuvt18

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

/* Get detailed list of actors (name, url, thumb (photo)) */
print_r($redtube::getStars(true, 3));

/* Video is active*/
print_r($redtube::isVideoActive(123563));

/* Get deleted videos */
print_r($redtube::getDeletedVideos());
```
