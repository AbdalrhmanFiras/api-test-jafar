<?php

namespace Tests\Feature\Api\Posts;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Tests\Feature\Api\CrudTestTrait;

class PostCrudTest extends TestCase{
    
    use CrudTestTrait;

    protected $route = 'api/post';
    protected $user;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');   
    }

    /** @test */
    public function test_create_post(){
        $payload = [
            'name' => 'test post',
            'dec' => 'test dec'
        ];
        $postData = $this->test_can_create($this->route, $payload);
         return $postData;
    }

      /** @test */
    public function test_read_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $data = $this->test_can_read($this->route, $post->id);

        $this->assertEquals($post->id, $data['id']);
    }
    /** @test */
       public function test_update_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $payload = ['name' => 'Updated Post'];

        $data = $this->test_can_update($this->route, $post->id, $payload);

        $this->assertEquals('Updated Post', $data['name']);
    }


       /** @test */
    public function test_delete_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->test_can_delete($this->route, $post->id);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }



}
