<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function an_authenticated_user_say_participate_in_forum_threads()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->post('/threads/'. $thread->id.'/replies', $reply->toArray());
        $this->get($thread->path())
            ->assertSee($thread->body);
    }
}
