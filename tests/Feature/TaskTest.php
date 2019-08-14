<?php

namespace Tests\Feature;

use App\Task;
use phpDocumentor\Reflection\Types\Nullable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function it_can_show_all_tasks()
    {
        //test: given = 10 random tasks
        $tasks = factory(Task::class, 10)->create();

        //test: 1. @User tries to index tasks -> 200
        $this
            ->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertJson($tasks->toArray())
            ->assertJsonCount(10);
    }

    /** @test */

    public function it_can_delete_task_existent_being()
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: 1. @User tries to delete exist task -> 200
        $this
            ->delete(route('tasks.destroy', $task->id))
            ->assertStatus(200);
    }

    /** @test */

    public function it_can_delete_task_unexistent_being()
    {
        //test: 1. @User tries to delete unexistent task -> 404
        $this
            ->delete(route('tasks.destroy', [1165]))
            ->assertStatus(404);
    }

    /** @test */

    public function it_can_delete_tasks_completed()
    {
        //test: given = 50 random tasks default with done to false
        $task_default = factory(Task::class, 50)->create();

        //test: given = 20 random tasks default with done to true
        factory(Task::class, 20)->create([
            'done' => true,
        ]);

        //test: 1. @User tries to index all tasks (70 items) -> 200
        $this->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertJson($task_default->toArray())
            ->assertJsonCount(70);

        //test: 2. @User tries to delete completed tasks -> 200
        $this->delete(route('tasks.destroy_completed'))
            ->assertStatus(200);

        //test: 3. @User tries to index not completed tasks (50 items) -> 200
        $this->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertJson($task_default->toArray())
            ->assertJsonCount(50);
    }

    /** @test */

    public function it_can_create_task_existent_data()
    {
        //test: 1. @User tries to store task existent data -> 200
        $this
            ->post(route('tasks.store'), ['name' => 'This is a name'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'done',
                'updated_at',
                'created_at',
                'id'
            ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'This is a name'
        ]);
    }

    /** @test */

    public function it_can_create_task_unexistent_data()
    {
        //test: 1. @User tries to store task unexistent data -> 302
        $this->post(route('tasks.store'), [])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }

    /** @test */

    public function it_can_update_task_existent_data()
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: 1. @User tries to update task existent data -> 200
        $this->put(route('tasks.update', $task->id), ['name' => 'This is the updated name'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'done',
                'updated_at',
                'created_at',
                'id'
            ]);
        $task = $task->fresh();
        $this->assertEquals($task->name, 'This is the updated name');
    }

    /** @test */

    public function it_can_update_task_unexistent_data()
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: 1. @User tries to update task unexistent data -> 302
        $this->put(route('tasks.update', $task->id), [])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }

    /** @test */

    public function it_can_check_task()
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: 1. @Task default  create done to false
        $this->assertEquals($task->done, false);

        //test: 2. @User tries to check task -> 200
        $this->patch(route('tasks.check', $task->id), ['done' => true])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'done',
                'updated_at',
                'created_at',
                'id'
            ]);
        $task = $task->fresh();
        $this->assertEquals($task->done, true);

        //test: 3. @User tries to uncheck task -> 200
        $this->patch(route('tasks.check', $task->id), ['done' => false])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'done',
                'updated_at',
                'created_at',
                'id'
            ]);
        $task = $task->fresh();
        $this->assertEquals($task->done, false);

        //test: 4. @User tries to check unexistent data -> 302
        $t =  $this->patch(route('tasks.check', $task->id), [])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'done' => 'The done field is required.'
            ]);

        //test: 5. @User tries to check done not boolean -> 302
        $this->patch(route('tasks.check', $task->id), ['done' => 'text'])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'done' => 'The done field must be true or false.'
            ]);


    }

}
