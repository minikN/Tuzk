<?php

namespace Tuzk\App;

use Tuzk\Core\App;

class Applier
{
    private $GENERATORS;
    private $SCHEMES;
    private $GLOBAL_PRE;
    private $GLOBAL_POST;

    public function __construct()
    {
        $config = App::get('config');
        $this->GENERATORS = $config['GENERATORS'];
        $this->SCHEMES = $config['SCHEMES'];
        $this->GLOBAL_PRE = $config['PRE_APPLY'];
        $this->GLOBAL_POST = $config['POST_APPLY'];
    }

    private function setCurrent()
    {
        echo "INFO: Setting current scheme to $this->scheme.\n";
        if (file_exists("$this->SCHEMES/current")) {
            unlink("$this->SCHEMES/current");
        }
        $file = (array_merge(
            (new Parser)->read("$this->SCHEMES/default")->get(),
            (new Parser)->read("$this->SCHEMES/$this->scheme")->get()
        ));
        makeFile("$this->SCHEMES/current");
        foreach ($file as $key => $value) {
            append("$this->SCHEMES/current", "$key\t$value" . PHP_EOL);
        }
        append("$this->SCHEMES/current", "SCHEME_NAME\t$this->scheme" . PHP_EOL);
    }

    private function preApplyGlobal()
    {
        echo "INFO: Running global pre apply script.\n";
        $output = shell_exec("sh $this->GLOBAL_PRE");
        echo $output;
    }

    private function postApplyGlobal()
    {
        echo "INFO: Running global post apply script.\n";
        $output = shell_exec("sh $this->GLOBAL_POST");
        echo $output;
    }

    private function preApply($generator)
    {
        echo "INFO: Running $generator pre apply script.\n";
        $output = shell_exec("sh $this->GENERATORS/$generator/$generator" . "_pre");
        echo $output;
    }

    private function postApply($generator)
    {
        echo "INFO: Running $generator post apply script.\n";
        $output = shell_exec("sh $this->GENERATORS/$generator/$generator" . "_post");
        echo $output;
    }

    public function apply($scheme)
    {
        $this->scheme = $scheme;
        if (file_exists("$this->SCHEMES/current") && ! App::get('force') && (new Parser())->read(App::get('config')['CURRENT'])->getVar('SCHEME_NAME') == $this->scheme) {
            echo "ERROR: Scheme $this->scheme is already applied.\n";
            die();
        }

        $this->setCurrent();
        $this->preApplyGlobal();
        foreach (Generator::list(true) as $generator) {
            if (! file_exists("$this->GENERATORS/$generator/$this->scheme")) {
                echo "WARNING: Theme $this->scheme doesn't exist for $generator. Skipping...\n";
                continue;
            }
            $this->preApply($generator);
            $target = (new Parser())->read("$this->GENERATORS/$generator/$generator" . "_settings")->getVar('target');
            if ($target) {
                echo "INFO: Copying $this->scheme for $generator to $target\n";
                shell_exec("cp -rf $this->GENERATORS/$generator/$this->scheme $target");
            }
            $this->postApply($generator);
        }
        $this->postApplyGlobal();
        echo "INFO: Everything done.\n";
    }
}
