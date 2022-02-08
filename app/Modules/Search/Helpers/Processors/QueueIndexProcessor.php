<?php

namespace Modules\Search\Helpers\Processors;

use Xcart\App\Main\Xcart;

class QueueIndexProcessor implements QueueProcessorInterface
{
    private object $document;

    public function __construct(object $document)
    {
        $this->document = $document;
    }

    public function process(string $engine_name): void
    {
        Xcart::app()->elastic->index($engine_name, [$this->document]);
    }
}