<?php
namespace Xcart\App\Storage\Adapters;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;

class LocalGoogleAdapter extends GoogleDriveAdapter {

    public function __construct(array $config)
    {
        parent::__construct($config['service'], $config['root_folder'], []);
    }
}