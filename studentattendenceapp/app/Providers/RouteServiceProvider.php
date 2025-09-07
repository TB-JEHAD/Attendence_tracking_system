<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * This constant is no longer used for redirection.
     */
    public const HOME = '/redirect';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // 🎯 Role-based redirect route (added here inside boot method)
            Route::middleware('web')->get('/redirect', function () {
                $user = Auth::user();

                if ($user->hasRole('admin')) {
                    return redirect('/admin-dashboard');
                } elseif ($user->hasRole('teacher')) {
                    return redirect('/teacher-dashboard');
                } elseif ($user->hasRole('student')) {
                    return redirect('/student-dashboard');
                }

                return abort(403, 'Unauthorized');
            });
        });
    }
}
