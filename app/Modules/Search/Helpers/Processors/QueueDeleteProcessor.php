<?php

namespace Modules\Search\Helpers\Processors;

use Xcart\App\Main\Xcart;

class QueueDeleteProcessor implements QueueProcessorInterface
{
    private array $documents = [];

    public function addDocument($document): void
    {
        $this->documents[] = $document;
    }

    public function process(string $engine_name): void
    {
        Xcart::app()->elastic->delete($engine_name, array_map(static fn($doc) => $doc->id, $this->documents));
    }
}