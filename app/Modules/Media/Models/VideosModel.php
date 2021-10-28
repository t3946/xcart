<?php


namespace Modules\Media\Models;

use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Model;

class VideosModel extends Model
{
    const YOUTUBE_PROVIDER = 'youtube';
    const YOUTUBE_VIMEO = 'vimeo';

    public static string $upload_to = '';
    public static string $max_size = '';

    public static function tableName()
    {
        return 'xcart_videos';
    }

    public static function getFields()
    {
        return [
            'video_id' => [
                'class' => AutoField::class,
            ],

            'name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'video' => [
                'class' => FileField::class,
                'null' => false,
                'required' => false,
                'adapterName' => 'www',
                'uploadTo' => self::$upload_to,
                'maxSize' => self::$max_size,
            ],

            'provider' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'image_1' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'image_2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
        ];
    }

    public function getThumbs(): array
    {
        $thumbs = [];
        extract($this->getAttributes(), $video = NULL, $provider = NULL);


        switch ($provider) {
            case self::YOUTUBE_PROVIDER:
                preg_match('/embed\/(\w+)/', $video, $video_key_matches);
                $video_key = $video_key_matches[1];

                if ($video_key) {
                    $thumbs = [
                        sprintf('https://img.youtube.com/vi/%s/default.jpg', $video_key),
                        sprintf('https://img.youtube.com/vi/%s/0.jpg', $video_key),
                        sprintf('https://img.youtube.com/vi/%s/1.jpg', $video_key),
                        sprintf('https://img.youtube.com/vi/%s/2.jpg', $video_key),
                        sprintf('https://img.youtube.com/vi/%s/3.jpg', $video_key),
                    ];
                }
                break;

            case self::YOUTUBE_VIMEO:
                $access_token = Xcart::app()->globals['vimeo_access_token'];
                preg_match('/video\/(\w+)/', $video, $matches);
                $id = $matches[1];
                $format = "https://api.vimeo.com/videos/%d/pictures?access_token=%s";
                $api_url = sprintf($format, $id, $access_token);
                $response = json_decode(file_get_contents($api_url), true);
                $size_groups = $response['data'][0]['sizes'];

                array_walk($size_groups, function($size_group) use(&$thumbs) {
                    $thumbs[] = $size_group['link'];
                });

                break;
        }

        return $thumbs;
    }
}