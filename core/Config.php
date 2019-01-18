<?php

namespace Tuzk\Core;

class Config
{
    public static function isInitialized()
    {
        if (
            hasDir(App::get('config')['SCHEMES']) &&
            hasDir(App::get('config')['GENERATORS']) &&
            file_exists(App::get('config')['SCHEMES'] . "/template") &&
            file_exists(App::get('config')['SCHEMES'] . "/default")
        ) {
            return true;
        }
        return false;
    }

    private static function createSchemeTemplate()
    {
        echo "INFO: Creating template scheme file in scheme directory.\n";
        copy(App::get('config')['SCH_TEMP'], App::get('config')['SCHEMES'] . "/template");
    }

    private static function createSchemeDefaults()
    {
        echo "INFO: Creating default scheme file in scheme directory.\n";
        copy(App::get('config')['SCH_DEF_TEMP'], App::get('config')['SCHEMES'] . "/default");
    }

    private static function createPreApply()
    {
        echo "INFO: Creating global pre-apply script in config directory.\n";
        copy(App::get('config')['GLOBAL_PRE'], App::get('config')['PRE_APPLY']);
    }

    private static function createPostApply()
    {
        echo "INFO: Creating global post-apply script in config directory.\n";
        copy(App::get('config')['GLOBAL_POST'], App::get('config')['POST_APPLY']);
    }

    public static function init()
    {
        if (! self::isInitialized() && ! App::get('force')) {
            echo "ERROR: " . App::get('config')['CONFIG_DIR'] . " already exists. Aborted.\n";
            die();
        }
        echo "INFO: Starting initialisation...\n";
        removeDir(App::get('config')['CONFIG_DIR']);
        makeDir(App::get('config')['CONFIG_DIR']);
        makeDir(App::get('config')['SCHEMES']);
        makeDir(App::get('config')['GENERATORS']);
        self::createSchemeTemplate();
        self::createSchemeDefaults();
        self::createPreApply();
        self::createPostApply();
    }
}
