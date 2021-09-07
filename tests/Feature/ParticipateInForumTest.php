<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_may_not_create_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post("/threads/1/replies", []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $reply = Reply::factory()->make();

        $this->followingRedirects()
            ->post("{$thread->path()}/replies", $reply->toArray())
            ->assertSee($reply->body);
    }
}
