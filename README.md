Install using composer :
```
composer require pierreminiggio/youtube-video-updater
```

```php
use PierreMiniggio\YoutubeVideoUpdater\VideoUpdater;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$updater = new VideoUpdater();
$updater->update(
    'accessToken',
    'videoId',
    'title',
    'description',
    ['tag1', 'tag2', 'tag3'],
    27,
    false
);
```
