<?php

namespace Support\DataCollectors;

use Illuminate\Http\Request;
use Support\Models\ApiCallsCount;

class ApiCallsCountCollector implements DataCollectorInterface
{
    public function collect(Request $request)
    {
        $apiCall = new ApiCallsCount(
            [
            'url' => $request->route()->uri()
            ]
        );

        $apiCall->user_id = (\Auth::guest()) ? null : \Auth::id();
        $apiCall->save();
    }
}