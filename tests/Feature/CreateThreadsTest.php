<?php

namespace Tests\Feature;

use App\Models\Channel;
use Tests\TestCase;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $this->get('/threads/create')
            ->assertStatus(200)
            ->assertSee('Create a New Thread');

        $thread = factory(Thread::class, 'raw');

        $response = $this->followingRedirects()
            ->post('/threads', $thread)
            ->assertStatus(200)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);

        $this->assertDatabaseHas('threads', [
            'title' => $thread['title'],
            'body' => $thread['body'],
        ]);

        // overkill and does not work with followingRedirects
        /*$this->get($response->headers->get('Location'))
            ->assertStatus(200)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);*/
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        Channel::factory(2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = factory(Thread::class, 'make', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
