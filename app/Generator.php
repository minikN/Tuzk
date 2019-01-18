<?php

namespace Tuzk\App;

use Tuzk\Core\App;
use Tuzk\Core\Config;

class Generator
{
    private $GENERATORS;

    public function __construct()
    {
        if (! Config::isInitialized()) {
            echo "ERROR: Configuration folder doesn't exist. Maybe run php tuzk --init?\n";
            die();
        }
        $this->GENERATORS = App::get('config')['GENERATORS'];
    }

    public function generate($name, $source = null)
    {
        if (file_exists("$this->GENERATORS/$name/$name" . "_template") && ! App::get('force')) {
            echo "ERROR: Generator $name already exists.\n";
            die();
        }
        removeDir("$this->GENERATORS/$name");
        makeDir("$this->GENERATORS/$name");

        if ($source) {
            copy($source, "$this->GENERATORS/$name/$name" . "_template");
        } else {
            copy(App::get('config')['GEN_TEMPLATE'], "$this->GENERATORS/$name/$name" . "_template");
        }
        copy(App::get('config')['GEN_SETTINGS'], "$this->GENERATORS/$name/$name" . "_settings");
        copy(App::get('config')['GEN_PRE'], "$this->GENERATORS/$name/$name" . "_pre");
        copy(App::get('config')['GEN_POST'], "$this->GENERATORS/$name/$name" . "_post");

        return [
            "$this->GENERATORS/$name/$name" . "_template",
            "$this->GENERATORS/$name/$name" . "_settings",
            "$this->GENERATORS/$name/$name" . "_pre",
            "$this->GENERATORS/$name/$name" . "_post"
        ];
    }

    public static function list($array = false)
    {
        $generators = array_diff(scandir(App::get('config')['GENERATORS']), ['..', '.']);
        if ($array) {
            return $generators;
        }
        foreach ($generators as $generator) {
            echo "$generator\n";
        }
    }

    public static function getMode($scheme)
    {
        return (new Parser())->read($scheme)->getVar("mode");
    }
}
