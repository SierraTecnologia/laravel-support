<?php

declare(strict_types=1);

namespace Support\Contracts\Generators\Traits;

use Support\Components\Coders\Parser\ClassReader;
use Support\Exceptions\SetterGetterException;

/**
 * https://github.com/usmanhalalit/GetSetGo
 */
trait ManipuleFile
{
    
    /**
     * Export a CSV
     * https://csv.thephpleague.com/9.0/connections/output/
     *
     * @return void
     */
    public function csv()
    {
        $items = $this->makeCsvQuery()->get();
        if ($items->isEmpty()) { abort(404);
        }
        $csv = $this->makeCsv($items);
        return response($csv->getContent())->withHeaders(
            [
            'Content-Encoding' => 'none',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf(
                'attachment; filename="%s"',
                $this->makeCsvFileTitle()
            ),
            'Content-Description' => 'File Transfer',
            ]
        );
    }

}
