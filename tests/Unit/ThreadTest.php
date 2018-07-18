<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function a_thread_has_replies()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf(Collection::class, $thread->replies);
    }

    function a_thread_has_a_creator()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('App\User', $thread->creator);
    }
}
