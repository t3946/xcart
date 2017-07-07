<?php

namespace Xcart\App\Controller;

class FrontendController extends Controller
{
    private $meta = [];

    /**
     * Used to generate a caching key
     *
     * @return array array of strings
     */
    public function getAdvancedCacheData() { return []; }

    public function setMetas($meta = null)
    {
        if (is_array($meta)) {
            $this->meta = $meta;
        }

        $this->meta = [];
    }

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}