## PHP API Wrapper for RedTube.com 🍌

WORK IN PROGRESS

### Requirements

PHP >= 7.0

### Install
Clone or download:

`$ git clone https://github.com/aethletic/redtube-api.git`

Use:
    
    
    require_once __DIR__ . '/src/Redtube.php';
    $redtube = new Aethletic\Redtube\Redtube;

#### Example
```php
/*
    thumbsizes: 'medium','small','big','all','medium1','medium2' (default: all)
    output: 'json', 'xml' (default: json)
*/

/* Get video by ID */
print_r($redtube::getVideo('9535301', 'big', 'json'));

/* Search video with filter */
print_r($redtube::search([
    'search'    => '',
    'category'  => 'teens',
    'tags'      => ['czech'],
    'stars'     => [''],
    'ordering'  => 'mostviewed',
    'period'    => '',
    'page'      => '1',
    'thumbsize' => 'big',
]));

/* Get source video link (mp4) for download */
print_r($redtube::getMp4('https://embed.redtube.com/?id=9535301'));

/* Get all categories */
print_r($redtube::getCategories());
```
#### Methods
`getVideo($id = null, $thumbsize = 'all', $output = self::DEFAULT_OUTPUT_TYPE)`

`getCategories($output = self::DEFAULT_OUTPUT_TYPE)`

`search($params = [], $output = self::DEFAULT_OUTPUT_TYPE)`

`getMp4($embedUrl = null, $output = self::DEFAULT_OUTPUT_TYPE)`
