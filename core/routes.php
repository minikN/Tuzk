<?php

use Tuzk\Core\App;
use Tuzk\App\Parser;
use Tuzk\App\Theme;
use Tuzk\App\Scheme;
use Tuzk\App\Applier;
use Tuzk\Core\Config;
use Tuzk\App\Generator;

if ($tuzk['force']) {
    App::bind('force', true);
}

if ($tuzk['init']) {
    Config::init();
    die();
}

if ($tuzk['list-schemes']) {
    Scheme::list();
    die();
}

if ($tuzk['list-generators']) {
    Generator::list();
    die();
}

if ($tuzk['list-themes']) {
    Theme::list();
    die();
}

if ($tuzk['read'] && $tuzk['rgb']) {
    $color = (new Parser())->read(App::get('config')['CURRENT'], "rgb");
    if ($tuzk['rgb'] === 'r') {
        echo $color->getVar($tuzk['read'])[0] . PHP_EOL;
    } elseif ($tuzk['rgb'] === 'g') {
        echo $color->getVar($tuzk['read'])[1] . PHP_EOL;
    } else {
        echo $color->getVar($tuzk['read'])[2] . PHP_EOL;
    }
    die();
}

if ($tuzk['read']) {
    echo (new Parser())->read(App::get('config')['CURRENT'])->getVar($tuzk['read']) . PHP_EOL;
    die();
}

if ($tuzk['current']) {
    echo (new Parser())->read(App::get('config')['CURRENT'])->getVar('SCHEME_NAME') . PHP_EOL;
}

if ($tuzk['new']) {
    if ($tuzk['scheme'] && $tuzk['generator']) {
        echo "ERROR: You can't specify a scheme AND a generator to be created.\n";
        die();
    }

    if (! $tuzk['scheme'] && ! $tuzk['generator']) {
        echo "ERROR: You have to specify what to create with -g/--generator or -s/--scheme.\n";
        die();
    }

    if ($tuzk['scheme']) {
        (new Parser())->append(
            (new Scheme())->generate($tuzk['scheme']),
            "## NAME\t\t" . $tuzk['scheme'] . PHP_EOL .
            "## AUTHOR\t" . $_SERVER['USER'] . PHP_EOL .
            "## CREATED\t" . date('d-m-Y') . PHP_EOL . PHP_EOL
        );
    } elseif ($tuzk['generator']) {
        $newGenerator = (new Generator())->generate($tuzk['generator'], $tuzk['file']);
        if ($tuzk['file']) {
            $file = "target {$tuzk['file']}";
        } else {
            $file = "# target";
        }
        (new Parser())->replace('NAME', $tuzk['generator'], $newGenerator[1])
                      ->replace('NAME', $tuzk['generator'], $newGenerator[2])
                      ->replace('NAME', $tuzk['generator'], $newGenerator[3])
                      ->replace('TARGET', $file, $newGenerator[1]);
    }
}

if ($tuzk['theme']) {
    (new Theme($tuzk['for'], $tuzk['except'], $tuzk['only']))->prepare();
    die();
}

if ($tuzk['apply']) {
    (new Applier())->apply($tuzk['apply']);
    die();
}
