<?php

namespace Modules\Search\Helpers\Processors;

interface QueueProcessorInterface
{
    public function process(string $engine_name): void;
}