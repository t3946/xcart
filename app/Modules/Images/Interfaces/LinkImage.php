<?php

namespace Modules\Images\Interfaces;

interface LinkImage {
    function getUploadTo(): string;

    function getMaxSize(): string;

    public function saveImage(int $entity_id, array $image_attributes);
}
