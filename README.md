Install using composer :
```
composer require pierreminiggio/youtube-video-updater
```

```php
use PierreMiniggio\YoutubeVideoUpdater\VideoUpdater;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$uploader = new VideoUpdater();
$uploader->upload(
    'accessToken',
    'videoId',
    'thumbnail.png'
);
```