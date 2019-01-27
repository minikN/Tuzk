<?php

namespace Tuzk\App;

use Tuzk\Core\App;
use Tuzk\Core\Config;

class Theme
{
    private $mode;
    private $current;
    private $variables;
    private $GENERATORS_DIR;
    private $SCHEMES_DIR;
    private $schemes;
    private $generators;

    public function __construct($schemes = null, $except = null, $only = null)
    {
        if (! Config::isInitialized()) {
            echo "ERROR: Configuration folder doesn't exist. Maybe run php tuzk --init?\n";
            die();
        }

        if ($except && $only) {
            echo "ERROR: You can not use both --expect and --only. Aborting.\n";
            die();
        }

        $this->GENERATORS_DIR = App::get('config')['GENERATORS'];
        $this->SCHEMES_DIR = App::get('config')['SCHEMES'];

        $this->for($schemes);
        $this->except($except);
        $this->only($only);

        return $this;
    }

    public function except($generators)
    {
        if ($generators) {
            $generators = explode(",", $generators);
            $this->generators = array_diff(Generator::list(true), $generators);
        }
    }
    public function only($generators)
    {
        if ($generators) {
            $this->generators = explode(",", $generators);
        }
    }

    private function for($schemes)
    {
        if ($schemes) {
            $this->schemes = explode(",", $schemes);
            if (array_diff($this->schemes, Scheme::list(true))) {
                echo "A scheme you specified is not a defined color scheme. Aborting\n";
                die();
            }
        } else {
            $this->schemes = Scheme::list(true);
        }
    }

    public function prepare()
    {
        if (! $this->generators) {
            $this->generators = Generator::list(true);
        }
        foreach ($this->generators as $generator) {
            $this->mode = Generator::getMode("$this->GENERATORS_DIR/$generator/$generator" . "_settings");
            foreach ($this->schemes as $scheme) {
                $this->current = $scheme;
                $this->variables = array_merge(
                        (new Parser)->read("$this->SCHEMES_DIR/default", $this->mode)->get(),
                        (new Parser)->read("$this->SCHEMES_DIR/$this->current", $this->mode)->get()
                    );
                $this->generate($generator);
            }
        }
    }

    private function generate($generator)
    {
        if (file_exists("$this->GENERATORS_DIR/$generator/$this->current") && ! App::get('force')) {
            echo "SKIP: Theme $this->current for $generator already exists.\n";
            return 0;
        }

        echo "INFO: Generating theme $this->current for $generator.\n";
        copy("$this->GENERATORS_DIR/$generator/$generator" . "_template", "$this->GENERATORS_DIR/$generator/$this->current");

        foreach ($this->variables as $var => $key) {
            $this->replaceWithMode($var, $key, "$this->GENERATORS_DIR/$generator/$this->current");
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

    public static function list()
    {
        foreach (Generator::list(true) as $generator) {
            $themes = array_diff(
                scandir(App::get('config')['GENERATORS'] . "/$generator"),
                [
                    '..',
                    '.',
                    $generator . "_template",
                    $generator . "_settings",
                    $generator . "_post",
                    $generator . "_pre"
                ]
            );
            foreach ($themes as $theme) {
                echo "$theme\n";
            }
        }
    }
}
