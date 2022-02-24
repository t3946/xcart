<?php

namespace Modules\Media\Interfaces;

interface LinkVideo {
    function getUploadTo(): string;

    public function saveVideo(int $linked_entity_id, array $video_attributes);
}
