<?php

namespace Support\Components\Errors;

class TableNotExistError extends CodeError
{
    const NAME = 'Tabela não existe';
    const DESCRIPTION = 'Tabela {target} não existe no banco de dados';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'real';
    }
}
