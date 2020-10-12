<?php

namespace Pangora\Classnames;

use InvalidArgumentException;

class Classnames
{
    private array $classlist = [];
    private bool $removeDuplicates = false;

    /** @param mixed ...$args **/
    public static function from(...$args): string
    {   
        return (new self)->build(...$args);
    }

    /** @param mixed ...$args **/
    public static function dedupeFrom(...$args): string
    {   
        return (new self)->withoutDuplicates()->build(...$args);
    }

    public function withoutDuplicates(): Classnames
    {
        $this->removeDuplicates = true;

        return $this;
    }

    /** @param mixed ...$args **/
    private function build(...$args): string
    {
        foreach ($args as $arg) {
            $this->addIfStringable($arg);
            
            if (is_array($arg)) {
                $this->processArray($arg);
            }
        }

        if ($this->removeDuplicates) {
            $this->removeDuplicatesFromList();
        }

        return $this->formatAsString();
    }

    private function formatAsString(): string
    {
        return $this->replaceConsecutiveSpaces(
            trim(implode(' ', $this->classlist))
        );
    }

    private function removeDuplicatesFromList(): void
    {
        $this->classlist = array_unique($this->classlist, SORT_STRING);
    }

    private function replaceConsecutiveSpaces(string $value): string
    {
        return preg_replace("/\s+/u", " ", $value);
    }

    private function addString(string $classes): void
    {
        $arrayedString = explode(' ', (string) $classes);

        array_push($this->classlist, ...array_values($arrayedString));
    }

    /** @param mixed $object **/
    private function isStringableObject($object): bool
    {
        return is_object($object) && method_exists($object, '__toString');
    }

    private function processArray(array $array): void
    {
        $this->checkForMultidimensionalArray($array);

        if (! $this->arrayIsAssociative($array)) {
            array_push($this->classlist, ...$array);

            return;
        }

        foreach($array as $classes => $condition) {
            if ($condition === false) {
                return;
            }

            $this->addIfStringable($classes);
        }
    }

    /** @param mixed $classes **/
    private function addIfStringable($classes): void
    {
        if (
            is_string($classes)
            || is_int($classes)
            || $this->isStringableObject($classes)
        ) {
            $this->addString((string) $classes);
        }
    }

    private function arrayIsAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function checkForMultidimensionalArray(array $array): void
    {
        foreach ($array as $_ => $value) {
            if (!is_array($value)) {
                return;
            }

            throw new InvalidArgumentException(
                'Multidimensional arrays are not accepted as an argument.'
            );
        }
    }
}
