<?php


namespace Modules\Translate\Helpers;



use Gettext\Extractors\PhpCode;
use Gettext\Generators\Po;
use Gettext\Merge;
use Gettext\Translations;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class TranslateHelper
{
    /**
     * @var Translations
     */
    private $_translations;

    public function __construct()
    {
        $this->_translations = new Translations();
    }

    public function collect()
    {
        $this->collectPath(Paths::get('base').'/include/Xcart');
        $this->collectPath(Paths::get('base').'/Modules');
        $this->collectPath(Paths::get('base').'/templates');
        $this->processSave();
    }

    public function processSave()
    {
        $path = Xcart::app()->getModule('Translate')->getPath();
        $file = $path.'\lang\ru_RU.po';
        $translations_o = file_exists($file) ? Translations::fromPoFile($file) : new Translations;
        $translations_o->mergeWith($this->_translations, Merge::DEFAULTS | Merge::REMOVE);
        Po::toFile($translations_o, $file);
    }

    public function collectPath($path)
    {
        $Directory = new RecursiveDirectoryIterator($path);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+\.(php|tpl)$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($Regex as $file) {
            $filename = $file[0] ?? null;
            if ($filename) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                switch ($ext) {
                    case 'php':
                        $this->collectPhpFile($filename);
                        break;
                    case 'tpl':
                        if (!$this->onlyAdditional) {
                            $this->collectTplFile($filename);
                        }
                        break;
                }
            }
        }
    }

    public function collectTplFile($filename): void
    {
        $file = file_get_contents($filename);

        preg_match_all('/{t ([^\'\"]*[\'\"][^}]*[\'\"][^}]*)}/m', $file, $matches);
        if ($matches && $matches[1]) {
            foreach ($matches[1] as $original) {
                preg_match_all($tpl = '/[\'](.*)[\']|[\"](.*)[\"]/mU', $original, $m);
                if ($m && $m[2] && $m[2][0]) {
                    [$o, $p] = $m[2];
                }
                if ($m && $m[1] && $m[1][0]) {
                    [$o, $p] = $m[1];
                }
                $this->_translations->insert($o, $o, $p ?? '');
            }
        }
    }

    public function collectPhpFile($filename)
    {
        $file = file_get_contents($filename);
        preg_match_all('/::t\([^\'\"]*[\'\"]([^\'\"]*)[\'\"][)]/m', $file, $matches);
        if ($matches && $matches[1]) {
            foreach ($matches[1] as $original) {
                $this->_translations->insert($original, $original);
            }
        }

        PhpCode::fromString($file, $this->_translations, []);
    }
}