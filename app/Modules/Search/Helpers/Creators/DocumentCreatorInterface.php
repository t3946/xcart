<?php

namespace Modules\Search\Helpers\Creators;

interface DocumentCreatorInterface
{
    public function createDocuments(array $update_models): void;
}