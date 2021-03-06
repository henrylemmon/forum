<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function has_favorites()
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf(Collection::class, $reply->favorites);
    }
}
