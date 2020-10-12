<?php

namespace Pangora\Classnames\Tests\Fixtures;

class StringableClass
{
    private string $classes;

    public function __construct(string $string)
    {
        $this->classes = $string;
    }

    public function __toString(): string
    {
        return $this->classes;
    }
}