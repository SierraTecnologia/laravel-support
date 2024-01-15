<?php

declare(strict_types=1);

namespace Muleta\Utils\Semantics;

/**
 * @todo fazer aqui
 */

class ReturnSimilars
{
    protected $word;

    protected $similars = [
        'person' => [
            'user',
        ],
    ];

    public function __construct($word)
    {
        $this->word = strtolower($word);    
    }

    public function returnSimilars(): array
    {
        $word = $this->word;
        
        if (isset($this->similars[$word])) {
            return array_merge(
                $this->similars[$word],
                [$word]
            );
        }

        return [$word];
    }


    public static function getSimilarsFor($word): array
    {
        if (empty($word)) {
            return [];
        }

        $classInstance = new self($word);
        return $classInstance->returnSimilars();
    }
}
