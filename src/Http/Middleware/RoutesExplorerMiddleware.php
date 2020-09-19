<?php

namespace Support\Http\Middleware;

use Closure;
use Support\DataCollectors\ApiCallsCountCollector;
use Support\DataCollectors\DataCollectorInterface;

class RoutesExplorerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /**
 * @var DataCollectorInterface[] $collectorInstance 
*/
        $collectors = [];

        if (config('infyom.routes_explorer.collections.api_calls_count')) {
            $collectors[] = app(ApiCallsCountCollector::class);
        }

        foreach ($collectors as $collector) {
            $collector->collect($request);
        }

        return $response;
    }
}
