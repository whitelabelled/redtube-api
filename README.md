#### PHP API Wrapper for RedTube.com 🍌

WORK IN PROGRESS

    require_once __DIR__ . '/src/Redtube.php';
    $redtube = new Aethletic\Redtube\Redtube;

#### Methods
`getVideo($id = null, $thumbsize = 'all', $output = self::DEFAULT_OUTPUT_TYPE)`

`getCategories($output = self::DEFAULT_OUTPUT_TYPE)`

`search($params = [], $output = self::DEFAULT_OUTPUT_TYPE)`

`getMp4($embedUrl = null, $output = self::DEFAULT_OUTPUT_TYPE)`
