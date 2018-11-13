<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function it_has_an_owner()
    {
        $reply = create('App\Reply');
        $this->assertInstanceOf('App\User', $reply->owner);
    }
    
    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new \App\Reply([
            'body' => '@YiQiao wants to talk @xiaoming'
        ]);

        $this->assertEquals(['YiQiao', 'xiaoming'], $reply->mentionedUsers());
    }
    
    /** @test */
    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @Yi-Qiao'
        ]);
        $this->assertEquals(
            'Hello <a href="/profiles/Yi-Qiao">@Yi-Qiao</a>',
            $reply->body
        );
    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');
        $this->assertFalse($reply->isBest());
        $reply->thread->update(['best_reply_id'=>$reply->id]);
        $this->assertTrue($reply->fresh()->isBest());

    }
}
