<?php

use Commando\Command;

$tuzk = new Command();

$tuzk->option('init')
     ->boolean()
     ->describedAs('Create basic configurations files.');

$tuzk->option('g')
     ->aka('generator')
     ->describedAs('Use with -n/--new: Specify that a generator should be created/deleted. Optionally a source file can be used with -f/--file.');

$tuzk->option('s')
     ->aka('scheme')
     ->describedAs('Use with -n/--new: Specify that a scheme should be created/deleted.');

$tuzk->option('n')
     ->aka('new')
     ->boolean()
     ->describedAs('Creates a new scheme/generator. Specifiy with -s/--scheme or -g/--generator.');

$tuzk->option('f')
     ->aka('file')
     ->expectsFile()
     ->describedAs('Use with -g/--generator: Specify a source file for the generator.');

$tuzk->option('a')
     ->aka('apply')
     ->describedAs('Applies a theme. The global pre-apply script will run first. After that the pre-apply script of each generator. Then the application itself (copying to TARGET), then the post-apply script for each generator and finally the global post-apply script.');

$tuzk->option('t')
     ->aka('theme')
     ->boolean()
     ->describedAs('Generates themes from generators for a given color scheme.');

$tuzk->option('force')
     ->boolean()
     ->describedAs('Forcing the current action and overriding existing files.');

$tuzk->option('list-schemes')
     ->boolean()
     ->describedAs('Writes all created color schemes to stdout.');

$tuzk->option('list-themes')
     ->boolean()
     ->describedAs('Writes all generated themes to stdout.');

$tuzk->option('list-generators')
     ->boolean()
     ->describedAs('Writes all created generators to stdout.');

$tuzk->option('r')
     ->aka('read')
     ->describedAs('Writes the value of a given variable from the currently applied color scheme to stdout.');

$tuzk->option('current')
     ->boolean()
     ->describedAs('Writes the name of the currently applied color scheme to stdout.');

$tuzk->option('rgb')
     ->describedAs('Use with -r/--read: Specify to either read the red (r), green (g) or blue (b) value of a given color.');

$tuzk->option('only')
      ->describedAs('Use with -t/--theme: Specify to only use specific generators. Expects a comma separated list (first,second,third). Not useable in conjunction with --except.');
$tuzk->option('except')
      ->describedAs('Use with -t/--theme: Specify to not use specific generators. Expects a comma separated list (first,second,third). Not useable in conjunction with --only.');
$tuzk->option('for')
      ->describedAs('Use with -t/--theme: Specify to only use specific color schemes. Expects a comma separated list (first,second,third).');
