<?php
namespace Xcart\App\Commands;

abstract class Command
{
    const FOREGROUND_BLACK = '0;30';
    const FOREGROUND_DARK_GRAY = '1;30';
    const FOREGROUND_BLUE = '0;34';
    const FOREGROUND_LIGHT_BLUE = '1;34';
    const FOREGROUND_GREEN = '0;32';
    const FOREGROUND_LIGHT_GREEN = '1;32';
    const FOREGROUND_CYAN = '0;36';
    const FOREGROUND_LIGHT_CYAN = '1;36';
    const FOREGROUND_RED = '0;31';
    const FOREGROUND_LIGHT_RED = '1;31';
    const FOREGROUND_PURPLE = '0;35';
    const FOREGROUND_LIGHT_PURPLE = '1;35';
    const FOREGROUND_BROWN = '0;33';
    const FOREGROUND_YELLOW = '1;33';
    const FOREGROUND_LIGHT_GRAY = '0;37';
    const FOREGROUND_WHITE = '1;37';

    const BACKGROUND_BLACK = '40';
    const BACKGROUND_RED = '41';
    const BACKGROUND_GREEN = '42';
    const BACKGROUND_YELLOW = '43';
    const BACKGROUND_BLUE = '44';
    const BACKGROUND_MAGENTA = '45';
    const BACKGROUND_CYAN = '46';
    const BACKGROUND_LIGHT_GRAY = '47';

    protected $foregroundColors = [];
    protected $backgroundColors = [];

    public function __construct() {
        // Set up shell colors
        $this->foregroundColors['black'] = self::FOREGROUND_BLACK;
        $this->foregroundColors['blue'] = self::FOREGROUND_BLUE;
        $this->foregroundColors['green'] = self::FOREGROUND_GREEN;
        $this->foregroundColors['cyan'] = self::FOREGROUND_CYAN;
        $this->foregroundColors['red'] = self::FOREGROUND_RED;
        $this->foregroundColors['purple'] = self::FOREGROUND_PURPLE;
        $this->foregroundColors['brown'] = self::FOREGROUND_BROWN;
        $this->foregroundColors['white'] = self::FOREGROUND_WHITE;
        $this->foregroundColors['yellow'] = self::FOREGROUND_YELLOW;
        $this->foregroundColors['light_blue'] = self::FOREGROUND_LIGHT_BLUE;
        $this->foregroundColors['light_green'] = self::FOREGROUND_LIGHT_GREEN;
        $this->foregroundColors['light_cyan'] = self::FOREGROUND_LIGHT_CYAN;
        $this->foregroundColors['light_red'] = self::FOREGROUND_LIGHT_RED;
        $this->foregroundColors['light_purple'] = self::FOREGROUND_LIGHT_PURPLE;
        $this->foregroundColors['light_gray'] = self::FOREGROUND_LIGHT_GRAY;
        $this->foregroundColors['dark_gray'] = self::FOREGROUND_DARK_GRAY;

        $this->backgroundColors['black'] = self::BACKGROUND_BLACK;
        $this->backgroundColors['red'] = self::BACKGROUND_RED;
        $this->backgroundColors['green'] = self::BACKGROUND_GREEN;
        $this->backgroundColors['yellow'] = self::BACKGROUND_YELLOW;
        $this->backgroundColors['blue'] = self::BACKGROUND_BLUE;
        $this->backgroundColors['magenta'] = self::BACKGROUND_MAGENTA;
        $this->backgroundColors['cyan'] = self::BACKGROUND_CYAN;
        $this->backgroundColors['light_gray'] = self::BACKGROUND_LIGHT_GRAY;
    }

    public function color($string, $foreground_color = null, $background_color = null) {
        $colored_string = "";

        if (isset($this->foregroundColors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foregroundColors[$foreground_color] . "m";
        }
        elseif (!empty($foreground_color)) {
            $colored_string .= "\033[" . $foreground_color . "m";
        }

        if (isset($this->backgroundColors[$background_color])) {
            $colored_string .= "\033[" . $this->backgroundColors[$background_color] . "m";
        }
        elseif (!empty($background_color)) {
            $colored_string .= "\033[" . $background_color . "m";
        }

        $colored_string .=  $string . "\033[0m";
        return $colored_string;
    }

    /**
     * Description for help
     */
    public function getDescription()
    {
        return '';
    }

    abstract public function handle($arguments = []);
}