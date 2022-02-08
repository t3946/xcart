<?php

namespace Modules\Search\Helpers\Processors;

use Xcart\App\Main\Xcart;

class QueueDeleteProcessor implements QueueProcessorInterface
{
    private object $document;

    public function __construct(object $document)
    {
        $this->document = $document;
    }

    public function process(string $engine_name): void
    {
        Xcart::app()->elastic->delete($engine_name, array_map(static fn($doc) => $doc->id, [$this->document]));
    }
}