<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Channel;
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

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // three threads
        // 2 replies, 3 replies, and 0 replies respectively
        $threadWithTwoReplies = factory(Thread::class, 'create');
        factory(Reply::class, 'create', [
            'thread_id' => $threadWithTwoReplies->id,
        ], 2);

        $threadWithTreeReplies = factory(Thread::class, 'create');
        factory(Reply::class, 'create', [
            'thread_id' => $threadWithTreeReplies->id,
        ], 3);

        $threadWithNoReplies = $this->thread;

        // when filter threads by popularity
        $response = $this->getJson('/threads?popular=1')->json();

        // they should be returned by most replies to least
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));

        $this->assertNotEquals([0, 2, 3], array_column($response, 'replies_count'));

        // below used to see if test was testing
        /*$this->assertEquals([0, 3, 2], array_column($response, 'replies_count'));*/
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity_more_modern_form_without_using_json()
    {
        // three threads
        // 2 replies, 3 replies, and 0 replies respectively
        $threadWithTwoReplies = factory(Thread::class, 'create');
        factory(Reply::class, 'create', [
            'thread_id' => $threadWithTwoReplies->id,
        ], 2);

        $threadWithThreeReplies = factory(Thread::class, 'create');
        factory(Reply::class, 'create', [
            'thread_id' => $threadWithThreeReplies->id,
        ], 3);

        $threadWithNoReplies = $this->thread;

        // when filter threads by popularity
        $this->get('/threads?popular=1')
            ->assertSeeInOrder([
                $threadWithThreeReplies->title,
                $threadWithTwoReplies->title,
                $threadWithNoReplies->title,
            ]);
    }
}
