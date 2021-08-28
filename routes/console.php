<?php

use App\Http\Services\ClearUnusedResourcesTask;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('storage:optimize', function () { (new ClearUnusedResourcesTask())->__invoke(); })->purpose('Clear storage from unused files and resources');
