<?php

$PATH = "{$_SERVER['HOME']}/.config/tuzk";
$BASE = __DIR__;

return [
    'CONFIG_DIR'    => $PATH,
    'SCHEMES'       => "$PATH/schemes",
    'GENERATORS'    => "$PATH/generators",
    'CURRENT'       => "$PATH/schemes/current",
    'PRE_APPLY'     => "$PATH/global_pre",
    'POST_APPLY'    => "$PATH/global_post",
    'STUD_DIR'      => "$BASE/studs",
    'GEN_POST'      => "$BASE/studs/generator_post.stud",
    'GEN_PRE'       => "$BASE/studs/generator_pre.stud",
    'GEN_SETTINGS'  => "$BASE/studs/generator_settings.stud",
    'GEN_TEMPLATE'  => "$BASE/studs/generator_template.stud",
    'GLOBAL_POST'   => "$BASE/studs/global_post.stud",
    'GLOBAL_PRE'    => "$BASE/studs/global_pre.stud",
    'SCH_DEF_TEMP'  => "$BASE/studs/scheme_default_template.stud",
    'SCH_TEMP'      => "$BASE/studs/scheme_template.stud"
];
