<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class PerformanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $end = microtime(true);
        $duration = $end - $start;

        // Simpan data performa ke database
        DB::table('performance_logs')->insert([
            'url' => $request->fullUrl(),
            'response_time' => $duration * 1000, // Convert to milliseconds
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $response;
    }
}