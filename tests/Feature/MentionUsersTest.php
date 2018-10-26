<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $user = create('App\User', ['name' => 'YiQiao']);
        $this->signIn($user);
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => '@YiQiao look at this.'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $user->notifications);
    }
    
    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);
        $result = $this->json('get', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $result->json());
    }
}
