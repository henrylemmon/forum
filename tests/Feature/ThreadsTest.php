<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_browse_threads()
    {
        $thread = Thread::factory()->create();

        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_view_a_specific_thread()
    {
        $thread = Thread::factory()->create();

        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($thread->title);
    }
}
