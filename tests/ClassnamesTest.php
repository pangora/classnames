<?php

namespace Pangora\Classnames\Tests;

use Pangora\Classnames\Classnames;
use PHPUnit\Framework\TestCase;

class ClassnameTest extends TestCase
{
    /** @test */
    public function it_works_as_expected_and_it_respects_the_argument_order()
    {
        $classes = Classnames::from(
            'card',
            'nav',
            ['nav-item', 'nav-item-icon'],
            ['added' => true, 'discarded' => false],
            new StringableClass('link link-primary')
        );

        $this->assertSame(
            'card nav nav-item nav-item-icon added link link-primary',
            $classes
        );
    }

    /** @test */
    public function it_accepts_a_string()
    {
        $classes = Classnames::from('simple string');
        $this->assertSame('simple string', $classes);
    }

    /** @test */
    public function it_does_not_overwrite_values()
    {
        $classes = Classnames::from('same same');
        $this->assertSame('same same', $classes);
    }

    /** @test */
    public function it_accepts_an_integer_and_converts_it_to_a_string()
    {
        $classes = Classnames::from(7);
        $this->assertSame('7', $classes);
    }

    /** @test */
    public function it_trims_whitespace_on_both_ends()
    {
        $classes = Classnames::from(' firstclass', 'lastclass ');
        $this->assertSame('firstclass lastclass', $classes);
    }

    /** @test */
    public function it_replaces_every_repeating_whitespace_with_a_single_whitespace()
    {
        $classes = Classnames::from('    firstclass', 'middleclass   lastclass ');
        $this->assertSame('firstclass middleclass lastclass', $classes);
    }

    /** @test */
    public function it_accepts_an_sequencial_array()
    {
        $classes = Classnames::from(['btn', 'btn-lg']);
        $this->assertSame('btn btn-lg', $classes);
    }

    /** @test */
    public function it_adds_every_array_item_with_a_evaluates_to_true()
    {
        $classes = Classnames::from(['btn' => true]);
        $this->assertSame('btn', $classes);
    }

    /** @test */
    public function it_ignores_every_array_item_with_a_evaluates_to_false()
    {
        $classes = Classnames::from(['btn' => false]);
        $this->assertSame('', $classes);
    }

    /** @test */
    public function it_throws_an_exception_when_given_a_multidimensional_array()
    {
        $this->expectException('InvalidArgumentException');
        
        Classnames::from(
            ['this will' => ['not work']]
        );
    }

    /** @test */
    public function it_can_be_used_without_duplicates()
    {
        $classes = Classnames::dedupeFrom('duplicate', 'duplicate');
        $this->assertSame('duplicate', $classes);
    }

    /** @test */
    public function it_keeps_the_first_instance_while_deduplicating()
    {
        $classes = Classnames::dedupeFrom('a duplicate b duplicate c');
        $this->assertSame('a duplicate b c', $classes);
    }
}

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