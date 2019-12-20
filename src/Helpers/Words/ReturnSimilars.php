<?php

declare(strict_types=1);

namespace Support\Helpers\Words;

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

    public function returnSimilars()
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


    public static function getSimilarsFor($word)
    {
        $classInstance = new self($word);

        return $classInstance->returnSimilars();
    }
}
