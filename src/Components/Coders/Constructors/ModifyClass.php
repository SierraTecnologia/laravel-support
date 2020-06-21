<?php

declare(strict_types=1);


namespace Support\Components\Coders\Constructors;

use PhpParser\Error;
use PhpParser\ParserFactory;


class ModifyClass
{

    /**
     * Gets the class name.
     *
     * @return string
     */
    public static function parsed($class)
    {
        $code = <<<'CODE'
        <?php
        function printLine($msg) {
            echo $msg, "\n";
        }
        printLine('Hello World!!!');
        CODE;
        
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        
        try {
            $stmts = $parser->parse($code);
            // $stmts is an array of statement nodes
        } catch (Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }
}
