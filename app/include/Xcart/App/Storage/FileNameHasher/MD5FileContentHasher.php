<?php

namespace Xcart\App\Storage\FileNameHasher;

use League\Flysystem\FilesystemInterface;

class MD5FileContentHasher extends DefaultHasher
{
    public function resolveUploadPath(FilesystemInterface $filesystem, $uploadTo, $name, $default_extension = '',  $file = null)
    {
        $uploadTo = ltrim($uploadTo, '/');

        if (!$file) {
            throw new \RuntimeException('Empty file received');
        }

        $ext = pathinfo($name, PATHINFO_EXTENSION) ?: $default_extension;
        $hash = md5(file_get_contents($file->getRealPath()));

        $resolvedName = sprintf('%s.%s', $hash, $ext);

        return $uploadTo.'/'.$resolvedName;
    }
}
