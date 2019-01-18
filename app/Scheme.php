<?php

namespace Tuzk\App;

use Tuzk\Core\App;
use Tuzk\Core\Config;

class Scheme
{
    private $SCHEMES_DIR;

    public function __construct()
    {
        if (! Config::isInitialized()) {
            echo "ERROR: Configuration folder doesn't exist. Maybe run php tuzk --init?\n";
            die();
        }
        $this->SCHEMES_DIR = App::get('config')['SCHEMES'];
    }

    public function generate($scheme)
    {
        if (file_exists("$this->SCHEMES_DIR/$scheme") && ! App::get('force')) {
            echo "ERROR: Scheme $scheme already exists.\n";
            die();
        }
        echo "INFO: Generating scheme $scheme in $this->SCHEMES_DIR\n";
        copy("$this->SCHEMES_DIR/template", "$this->SCHEMES_DIR/$scheme");

        return "$this->SCHEMES_DIR/$scheme";
    }

    public static function list($array = false)
    {
        $schemes = array_diff(
            scandir(App::get('config')['SCHEMES']),
            ['..', '.', "template", "current", "default"]
        );

        if ($array) {
            return $schemes;
        }

        foreach ($schemes as $scheme) {
            echo "$scheme\n";
        }
    }
}
