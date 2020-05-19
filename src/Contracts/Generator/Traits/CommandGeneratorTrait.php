<?php

declare(strict_types=1);

namespace Support\Contracts\Generator\Traits;

use Support\Components\Coders\Parser\ClassReader;
use Support\Exceptions\SetterGetterException;
use Support\Components\Coders\Parser\ComposerParser;

/**
 * https://github.com/usmanhalalit/GetSetGo
 */
trait CommandGeneratorTrait
{
    use ManipuleFile;
}
