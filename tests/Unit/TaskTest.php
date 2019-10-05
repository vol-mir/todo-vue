<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    /** @test */
    public function test_task_was_author(): void
    {
        //test: make - task default (author - admin)
        $task = factory(Task::class)->make();

        //test: get user author (admin - default)
        $author = User::where('name','admin')->first();

        //test: is author - true
        $this->assertTrue($task->isAuthor($author));
    }

    /** @test */
    public function test_task_was_not_author(): void
    {
        //test: make - task default (author - admin)
        $task = factory(Task::class)->make();

        //test: given = 1 users not author
        $user = User::create([
            'name' => 'Bob',
            'email' => 'bob@gmail.com',
            'password' => bcrypt('eeeeee'),
            'active' => true,
            'activation_token' => '',
            'deleted_at' => null
        ]);

        //test: is author - false
        $this->assertFalse($task->isAuthor($user));
    }
}
