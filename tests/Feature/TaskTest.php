<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Nullable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    /** @test */
    public function it_can_show_user_tasks_being_authed(): void
    {
        //test: given = 10 random tasks for admin
        $tasks = factory(Task::class, 10)->create();

        //test: given = 1 users
        User::create([
            'name' => 'Bob',
            'email' => 'bob@gmail.com',
            'password' => bcrypt('eeeeee'),
            'active' => true,
            'activation_token' => '',
            'deleted_at' => null
        ]);

        //test: given = 20 random tasks for second user
        factory(Task::class, 20)->create([
            'user_id' => User::select('id')->where('name', 'Bob')->first()
        ]);

        //test: user tries to index tasks -> 200
        $response = $this
            ->get(route('tasks.index'), $this->makeHeaders('GET'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully get a listing of the tasks'
            ]);

        $content = $response->getOriginalContent();

        //test: user (auth - admin) get only their tasks
        $this->assertEquals($content['tasks']->count(), 10);
    }

    /** @test */
    public function it_can_show_user_tasks_being_unauthed(): void
    {
        //test: given = 10 random tasks
        $tasks = factory(Task::class, 10)->create();

        //test: user unauthorized -> 401
        $this
            ->get(route('tasks.index'), $this->makeHeaders('GET', false))
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_store_task_default_being_authed(): void
    {
        //test: make - task default
        $fakeInfo = factory(Task::class)->make();

        //test: user tries to store task existent data -> 201
        $this
            ->post(route('tasks.store'),  $fakeInfo->toArray(), $this->makeHeaders('POST'))
            ->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'Successfully create a new task'
            ]);

        // test: checking the created task
        $this->assertDatabaseHas('tasks', [
            'name' => $fakeInfo->name
        ]);
    }

    /** @test */
    public function it_can_store_task_empty_being_authed(): void
    {
        //test: make - task name null
        $fakeInfo = factory(Task::class)->make();
        $fakeInfo->name = null;

        //test: user tries to store task empty name -> 422
        $this
            ->post(route('tasks.store'),  $fakeInfo->toArray(), $this->makeHeaders('POST'))
            ->assertStatus(422);
    }

    /** @test */
    public function it_can_store_task_default_being_unauthed(): void
    {
        //test: make - task default
        $fakeInfo = factory(Task::class)->make();

        //test: user tries to store task empty name -> 401
        $this
            ->post(route('tasks.store'),  $fakeInfo->toArray(), $this->makeHeaders('POST', false))
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_update_task_existent_being_authed(): void
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: given = 1 users
        User::create([
            'name' => 'Bob',
            'email' => 'bob@gmail.com',
            'password' => bcrypt('eeeeee'),
            'active' => true,
            'activation_token' => '',
            'deleted_at' => null
        ]);

        //test: given = 1 random tasks for second user
        $taskBob = factory(Task::class)->create([
            'user_id' => User::select('id')->where('name', 'Bob')->first()
        ]);

        $fakeInfo = 'This is the updated name';

        //test: user tries to update their task existent data -> 200
        $this
            ->patch(route('tasks.update', $task->id), ['name' => $fakeInfo], $this->makeHeaders('PATCH'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully update task'
            ]);

        $task = $task->fresh();
        $this->assertEquals($task->name, $fakeInfo);

        //test: user tries to update not mine task -> 404
        $this
            ->patch(route('tasks.update', [$taskBob->id]), ['name' => $fakeInfo], $this->makeHeaders('PATCH'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);
    }

    /** @test */
    public function it_can_update_task_empty_being_authed(): void
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: user tries to update task empty data -> 422
        $this
            ->patch(route('tasks.update', $task->id), [], $this->makeHeaders('PATCH') )
            ->assertStatus(422);
    }

    /** @test */
    public function it_can_update_task_existent_being_unauthed(): void
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        $fakeInfo = 'This is the updated name';

        //test: unauthed user tries to update task existent data -> 401
        $this
            ->patch(route('tasks.update', $task->id), ['name' => $fakeInfo], $this->makeHeaders('PUT', false))
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_update_task_unexistent_being_authed(): void
    {
        //test: user tries to update unexistent task -> 404
        $this
            ->patch(route('tasks.update', [1161]), ['name' => 'fakeInfo'], $this->makeHeaders('PATCH'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);
    }

    /** @test */
    public function it_can_delete_task_existent_being_authed(): void
    {
        //test: given = 1 random task for admin
        $task = factory(Task::class)->create();

        //test: given = 1 users
        User::create([
            'name' => 'Bob',
            'email' => 'bob@gmail.com',
            'password' => bcrypt('eeeeee'),
            'active' => true,
            'activation_token' => '',
            'deleted_at' => null
        ]);

        //test: given = 1 random tasks for second user
        $taskBob = factory(Task::class)->create([
            'user_id' => User::select('id')->where('name', 'Bob')->first()
        ]);

        //test: user tries to delete exist their task -> 200
        $this
            ->delete(route('tasks.destroy', [$task->id]), [], $this->makeHeaders('DELETE'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully delete task'
            ]);

        //test: user tries to delete exist not mine task -> 404
        $this
            ->delete(route('tasks.destroy', [$taskBob->id]), [], $this->makeHeaders('DELETE'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);
    }

    /** @test */
    public function it_can_delete_task_unexistent_being_authed(): void
    {
        //test: user tries to delete unexistent task -> 404
        $this
            ->delete(route('tasks.destroy', [1161]), [], $this->makeHeaders('DELETE'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);
    }

    /** @test */
    public function it_can_delete_task_existent_being_unauthed(): void
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: user unauthed tries to delete existent task -> 401
        $this
            ->delete(route('tasks.destroy', [$task->id]), [], $this->makeHeaders('DELETE', false))
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.'
            ]);

    }

    /** @test */
    public function it_can_delete_tasks_completed_being_authed(): void
    {
        //test: given = 50 random tasks default with done to false
        factory(Task::class, 50)->create();

        //test: given = 20 random tasks default with done to true
        factory(Task::class, 20)->create([
            'done' => true,
        ]);

        //test: user tries to delete completed tasks -> 200
        $response = $this
            ->delete(route('tasks.destroy.completed'), [], $this->makeHeaders('DELETE'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully remove completed tasks'
            ]);

        $content = $response->getOriginalContent();

        //test: get count delete completed tasks
        $this->assertEquals($content['tasks'], 20);
    }

    /** @test */
    public function it_can_check_task_being_authed(): void
    {
        //test: given = 1 random task for admin
        $task = factory(Task::class)->create();

        //test: given = 1 users
        User::create([
            'name' => 'Bob',
            'email' => 'bob@gmail.com',
            'password' => bcrypt('eeeeee'),
            'active' => true,
            'activation_token' => '',
            'deleted_at' => null
        ]);

        //test: given = 1 random tasks for second user
        $taskBob = factory(Task::class)->create([
            'user_id' => User::select('id')->where('name', 'Bob')->first()
        ]);

        //test: task default create done to false
        $this->assertEquals($task->done, false);

        //test: user tries to check task -> 200
        $this
            ->patch(route('tasks.check', $task->id), ['done' => true], $this->makeHeaders('PATCH'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully set done the task'
            ]);
        $task = $task->fresh();
        $this->assertEquals($task->done, true);

        //test: user tries to uncheck task -> 200
        $this->patch(route('tasks.check', $task->id), ['done' => false], $this->makeHeaders('PATCH'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Successfully set done the task'
            ]);
        $task = $task->fresh();
        $this->assertEquals($task->done, false);

        //test: user tries to check empty data -> 422
        $this->patch(route('tasks.check', $task->id), [], $this->makeHeaders('PATCH'))
            ->assertStatus(422);

        //test: user tries to check done not boolean -> 422
        $this->patch(route('tasks.check', $task->id), ['done' => 'text'], $this->makeHeaders('PATCH'))
            ->assertStatus(422);

        //test: user tries to check done not mine task -> 404
        $this
            ->patch(route('tasks.check', [$taskBob->id]), ['done' => true], $this->makeHeaders('PATCH'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);

    }

    /** @test */
    public function it_can_check_task_being_unauthed(): void
    {
        //test: given = 1 random task
        $task = factory(Task::class)->create();

        //test: task default create done to false
        $this->assertEquals($task->done, false);

        //test: unauthed user tries to check task -> 401
        $this
            ->patch(route('tasks.check', $task->id), ['done' => true], $this->makeHeaders('PATCH', false))
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_check_task_unexistent_being_authed(): void
    {
        //test: user tries to update unexistent task -> 404
        $this
            ->patch(route('tasks.check', [1161]), ['done' => true], $this->makeHeaders('PATCH'))
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'No find task in storage'
            ]);
    }

}
