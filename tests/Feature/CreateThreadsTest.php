<?php

namespace Tests\Feature;

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

        $this->followingRedirects()
            ->post('/threads', $thread)
            ->assertStatus(200)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);

        $this->assertDatabaseHas('threads', [
            'title' => $thread['title'],
            'body' => $thread['body'],
        ]);
    }
}
