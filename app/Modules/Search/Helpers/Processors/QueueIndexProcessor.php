<?php

namespace Modules\Search\Helpers\Processors;

use Xcart\App\Main\Xcart;

class QueueIndexProcessor implements QueueProcessorInterface
{
    private array $documents = [];

    public function addDocument($document): void
    {
        $this->documents[] = $document;
    }

    public function process(string $engine_name): void
    {
        Xcart::app()->elastic->index($engine_name, $this->documents);
    }
}