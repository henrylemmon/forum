<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\User;
use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class, 'create');
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_view_a_specific_thread()
    {
        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class, 'create', [
            'thread_id' => $this->thread->id
        ]);

        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_tag()
    {
        /*$this->withoutExceptionHandling();*/

        $channel = factory(Channel::class, 'create');

        $threadInChannel = factory(Thread::class, 'create', [
            'channel_id' => $channel->id,
        ]);

        $threadNotInChannel = factory(Thread::class, 'create');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(factory(User::class, 'create', [
            'name' => 'john',
        ]));

        $threadByJohn = factory(Thread::class, 'create', [
            'user_id' => auth()->id(),
        ]);
        $threadNotByJohn = factory(Thread::class, 'create');

        $this->get('/threads?by=john')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
