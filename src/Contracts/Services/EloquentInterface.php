<?php

namespace Support\Contracts\Services;

interface EloquentInterface
{
    /**
     * Get the URL for a given page.
     *
     * @param  int $page
     * @return string
     */
    public function getPrimaryKey();

    public function getIndexes();

    public function hasColumn($column): bool;

    public function columnIsType($columnName, $typeClass): bool;
    public function getName($plural = false);
    public function getIcon();
    public function getGroupPackage();
    public function getGroupType();
    public function getHistoryType();
    public function getRegisterType();
}