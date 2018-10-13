<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_check_for_invalid_keywords()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');
        $spam->detect('yahoo customer support');
    }

    /** @test */
    function it_checks_for_any_key_being_down()
    {
        $spam = new Spam();
        $this->expectException('Exception');
        $spam->detect('Hello world aaaaaaaaa');
    }
}
