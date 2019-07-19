<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function it_will_show_all_tasks()
    {
        $tasks = factory(Task::class, 10)->create();
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
        $response->assertJson($tasks->toArray());
    }

    /** @test */

    public function it_will_create_tasks()
    {
        $response = $this->post(route('tasks.store'), [
            'name' => 'This is a name'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'This is a name'
        ]);
        $response->assertJsonStructure([
            'name',
            'done',
            'updated_at',
            'created_at',
            'id'
        ]);
    }

    /** @test */

    public function it_will_show_a_task()
    {
        $this->post(route('tasks.store'), [
            'name' => 'This is a name'
        ]);
        $task = Task::all()->first();
        $response = $this->get(route('tasks.show', $task->id));
        $response->assertStatus(200);
        $response->assertJson($task->toArray());
    }

    /** @test */

    public function it_will_update_a_task()
    {
        $this->post(route('tasks.store'), [
            'name' => 'This is a name'
        ]);
        $task = Task::all()->first();
        $response = $this->put(route('tasks.update', $task->id), [
            'name' => 'This is the updated name'
        ]);
        $response->assertStatus(200);
        $task = $task->fresh();
        $this->assertEquals($task->name, 'This is the updated name');
        $response->assertJsonStructure([
            'message',
            'task' => [
                'name',
                'done',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    /** @test */

    public function it_will_delete_a_task()
    {
        $this->post(route('tasks.store'), [
            'name' => 'This is a name'
        ]);
        $task = Task::all()->first();
        $response = $this->delete(route('tasks.destroy', $task->id));
        $task = $task->fresh();
        $this->assertNull($task);
        $response->assertStatus(200);
    }
}
