<?php

namespace Tests\Feature\Api\Comments;

use App\Models\User;
use Tests\Feature\Api\CrudTestTrait;
use Tests\TestCase;

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
    $route = 'api/post/comment';
    $payload = [
        'context' => 'just test'
    ];

    $commentData = $this->test_can_create($route, $payload);
    
    $this->assertNotEmpty($commentData);
}

}