<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_validates_spam()
    {
        $spam = new \App\Spam();
        $this->assertFalse($spam->detect('Innocent reply here'));
    }
}
