<?php

use App\Http\Middleware\CheckKycStatusMiddleware;
use App\Http\Middleware\EnsureUserIsAdminMiddleware;
use App\Http\Middleware\EnsureUserIsAgentMiddleware;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Jobs\UpdateCurrencyRatesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.mustLogin' => EnsureUserIsAuthenticated::class,
            'onlyAdmin' => EnsureUserIsAdminMiddleware::class,
            'onlyAgent' => EnsureUserIsAgentMiddleware::class,
            'kycMustBeVerified' => CheckKycStatusMiddleware::class,
        ]);
        $middleware->group('authGroup', ['auth.mustLogin']);
        $middleware->group('adminGroup', ['onlyAdmin']);
        $middleware->group('agentGroup', ['onlyAgent']);
        $middleware->group('kycVerifiedGroup', ['kycMustBeVerified']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        //run every 10 minutes
        $schedule->job(new UpdateCurrencyRatesJob())->everyFiveMinutes();

        //  run the job every 10 minutes
        //$schedule->command('currency:update')->everyTenMinutes();
    })
    ->create();
