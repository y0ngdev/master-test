<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\Models\Lesson;
use App\Models\Module;
use Auth;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WatchController extends Controller
{
    public function index(): RedirectResponse
    {
        $module = Module::published()
            ->orderBy('order')
            ->firstOrFail();

        $lessons = \App\Models\Lesson::query()->where('module_id', $module->id)
            ->where('is_published', true)
            ->where('status', 'READY')
            ->get();

        $user = Auth::user();

        $lesson = $lessons->first(fn ($lesson): bool => $lesson->canWatch($user));

        if (! $lesson) {
            return redirect()->route('home');
        }

        return redirect()->route('watch.lesson', $lesson->slug);

    }

    public function handle(): Response
    {
        $modules = Module::published()
            ->with(['lessons' => fn ($query) => $query->ready()])
            ->orderBy('order')
            ->get();

        return Inertia::render('Watch/Show', [
            'lesson' => Lesson::ready()->where('slug', request()->route('slug'))->firstOrFail()->toResource(),
            'modules' => new ModuleResource($modules),
            'pricing' => \App\Models\Plan::query()->first(),

        ]);

    }
}
