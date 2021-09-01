<?php
namespace Xcart\App\Storage\Adapters;

use League\Flysystem\AwsS3v3\AwsS3Adapter;

class S3Adapter extends AwsS3Adapter
{
    public function __construct($config = [])
    {
        parent::__construct($config['client'], $config['bucket']);
    }
}