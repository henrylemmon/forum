<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_replies()
    {
        $this->post("/threads/some-channel/1/replies", [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class, 'create');

        $reply = factory(Reply::class, 'make');

        $this->followingRedirects()
            ->post("{$thread->path()}/replies", $reply->toArray())
            ->assertStatus(200)
            ->assertSee($reply->body);

        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();

        $thread = factory(Thread::class, 'create');

        $reply = factory(Reply::class, 'make', ['body' => null]);

        $this->post("{$thread->path()}/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
