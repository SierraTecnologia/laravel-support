<?php

namespace Finder\Actions\Contracts;

interface RunnerInterface
{
    public function prepare();
    public function execute();
    public function done();
    public function run();
}