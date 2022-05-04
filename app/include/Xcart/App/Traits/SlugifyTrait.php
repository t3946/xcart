<?php

namespace Xcart\App\Traits;

use Cocur\Slugify\Slugify;

/**
 * Class UniqueUrl.
 */
trait SlugifyTrait
{
    protected ?Slugify $slugify = null;
    protected ?string $slug = null;

    /**
     * @param $source
     *
     * @return string
     */
    protected function createSlug($source): string
    {
        if ($this->slugify === null) {
            $this->slugify = new Slugify(['rulesets' => ['default']]);
        }

        if ($this->slug === null) {
            $this->slug = $this->slugify->slugify($source);
        }

        return $this->slug;
    }

    /**
     * @param $url
     * @param int $count
     * @param null $pk
     *
     * @return string
     */
    public function uniqueUrl($url, int $count = 0, $pk = null): string
    {
        $newUrl = $url;

        if ($count) {
            $newUrl .= '-'.$count;
        }

        $qs = $this->getModel()::objects()->filter([$this->getName() => $newUrl]);

        if ($pk) {
            $qs = $qs->exclude(['pk' => $pk]);
        }
        if ($qs->count() > 0) {
            ++$count;

            return $this->uniqueUrl($url, $count, $pk);
        }

        return $newUrl;
    }
}
