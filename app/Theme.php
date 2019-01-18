<?php

namespace Tuzk\App;

use Tuzk\Core\App;
use Tuzk\Core\Config;

class Theme
{
    private $mode;
    private $current;
    private $variables;
    private $GENERATORS;
    private $SCHEMES;

    public function __construct()
    {
        if (! Config::isInitialized()) {
            echo "ERROR: Configuration folder doesn't exist. Maybe run php tuzk --init?\n";
            die();
        }
        $this->GENERATORS = App::get('config')['GENERATORS'];
        $this->SCHEMES = App::get('config')['SCHEMES'];
    }

    public function generate($scheme)
    {
        foreach (Generator::list(true) as $generator) {
            $this->mode = Generator::getMode(
                "$this->GENERATORS/$generator/$generator" . "_settings"
            );
            foreach (Scheme::list(true) as $scheme) {
                $this->current = $scheme;
                $this->variables = array_merge(
                    (new Parser)->read("$this->SCHEMES/default", $this->mode)->get(),
                    (new Parser)->read("$this->SCHEMES/$this->current", $this->mode)->get()
                );
                $this->generateTheme($generator);
            }
        }
    }

    private function generateTheme($generator)
    {
        if (file_exists("$this->GENERATORS/$generator/$this->current") && ! App::get('force')) {
            echo "SKIP: Theme $this->current for $generator already exists.\n";
            return 0;
        }

        echo "INFO: Generating theme $this->current for $generator.\n";
        copy("$this->GENERATORS/$generator/$generator" . "_template", "$this->GENERATORS/$generator/$this->current");

        foreach ($this->variables as $var => $key) {
            $this->replaceWithMode($var, $key, "$this->GENERATORS/$generator/$this->current");
        }
    }

    private function replaceWithMode($var, $key, $target)
    {
        $parser = new Parser();
        if ($this->mode === "rgb") {
            $parser->replace($var . "_R", $key[0], $target);
            $parser->replace($var . "_G", $key[1], $target);
            $parser->replace($var . "_B", $key[2], $target);
        } else {
            $parser->replace($var, $key, $target);
        }
    }
}
