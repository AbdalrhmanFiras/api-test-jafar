<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait CrudTestTrait{

 use RefreshDatabase;

    public function test_can_create($route,$payload){
        $res = $this->postJson($route,$payload);
        $res->assertstatus(201);
        return $res->json('data');//work
    }
    
    public function test_can_get($route,$id){
        $res = $this->getJson("$route/$id");
        $res->assertStatus(200);
        return $res->json('data');
    }

    public function test_can_update($route,$id,$payload){
        $res = $this->putJson("$route/$id",$payload);
        $res->assertStatus(200);
        return $res->json('data');
    }

    public function test_can_delete($route, $id)
    {
        $response = $this->deleteJson("$route/$id");
        $response->assertStatus(200);
    }


}