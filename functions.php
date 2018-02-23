<?php

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

if (! function_exists('dd')) {

    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($value) {
            if (class_exists(CliDumper::class)) {
                $dumper = 'cli' === PHP_SAPI ? new CliDumper : new HtmlDumper;

                $dumper->dump((new VarCloner)->cloneVar($value));
            } else {
                var_dump($value);
            }
        }, func_get_args());

        die(1);
    }

}
