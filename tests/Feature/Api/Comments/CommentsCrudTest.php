<?php

namespace Tests\Feature\Api\Comments;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Tests\Feature\Api\CrudTestTrait;

class CommentsTestCrud extends TestCase{
    use CrudTestTrait;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');   
    }

    public function test_create_comment()
    {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    $route = "api/post/{$post->id}/comment";
    $payload = [
        'context' => 'just test'
    ];
    $commentData = $this->test_can_create($route, $payload);
$this->assertEquals('just test', $commentData['context']);
    }

}