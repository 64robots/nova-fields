<?php

namespace R64\NovaFields\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use R64\NovaFields\FilemanagerTool;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return app(FilemanagerTool::class)->authorize($request)
        ? $next($request)
        : abort(403);
    }
}
