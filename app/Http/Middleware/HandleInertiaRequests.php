<?php

namespace App\Http\Middleware;

use App\Services\SettingsManager;
use Arr;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Storage;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        $settingsManager = new SettingsManager;

        $settings = Arr::undot($settingsManager->all());

        if (! empty($settings['general']['site_logo'])) {
            $settings['general']['site_logo'] = Storage::disk('public')->url($settings['general']['site_logo']);
        }  if (! empty($settings['general']['site_favicon'])) {
            $settings['general']['site_favicon'] = Storage::disk('public')->url($settings['general']['site_favicon']);
        }

        if (! empty($settings['landing']['hero_image'])) {
            $settings['landing']['hero_image'] = Storage::disk('public')->url($settings['landing']['hero_image']);
        }

        if (! empty($settings['landing']['instructor'])) {
            $settings['landing']['instructor']['image'] = Storage::disk('public')->url($settings['landing']['instructor']['image']);
        }

        return [
            ...parent::share($request),
            'name' => $settings['general']['site_name'] ?? config('app.name'),
            'settings' => $settings,
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'type' => fn () => $request->session()->get('type', 'default'),
                'description' => fn () => $request->session()->get('description'),
            ],
        ];
    }
}
