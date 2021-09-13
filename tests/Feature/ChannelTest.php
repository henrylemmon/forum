<?php

namespace Tests\Feature;

use App\Models\Thread;
use Tests\TestCase;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_threads()
    {
        $channel = factory(Channel::class, 'create');

        $thread = factory(Thread::class, 'create', [
            'channel_id' => $channel->id,
        ]);

        $this->assertInstanceOf(Collection::class, $channel->threads);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
