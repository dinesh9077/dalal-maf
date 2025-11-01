<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class TrackVisitor
{
  public function handle($request, Closure $next)
  {
      // Prefer session id
      if ($request->hasSession()) {
          $sessionId = $request->session()->getId();
      } else {
          // Fallback to a persistent visitor_id cookie
          $sessionId = $request->cookie('visitor_id');
          if (!$sessionId) {
              $sessionId = (string) \Illuminate\Support\Str::uuid();
              cookie()->queue('visitor_id', $sessionId, 120); // 2 hours
          }
      }

      // Get current list
      $visitors = Cache::get('visitors', []);

      // Update timestamp for this single ID
      $visitors[$sessionId] = now()->timestamp;

      // Save back
      Cache::put('visitors', $visitors, now()->addMinutes(10));

      return $next($request);
  }
}
