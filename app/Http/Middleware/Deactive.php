<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Deactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('vendor')->user()->status == 0) {
            if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'deactive' => 'Your account is deactive or pending. Please contact the admin!',
                    ], 403);
                } else {
                  session()->flash('warning', 'Your account is deactive or pending now. Please Contact with admin!');
                  return redirect()->back();
                }
            }
        }
        return $next($request);
        // if (Auth::guard('vendor')->user()->status == 0 || Auth::guard('vendor')->user()->is_kyc_approved == 0) {
        //     if ($request->isMethod('POST') || $request->isMethod('PUT')) {
        //         if ($request->expectsJson()) {
        //             return response()->json([
        //                 'status' => 'error',
        //                 'deactive' => 'Your account is deactive or pending. Please contact the admin!',
        //             ], 403);
        //         } else {
        //           if(Auth::guard('vendor')->user()->is_kyc_approved == 0) {
        //             session()->flash('warning', 'Your KYC is pending now. Please Submit your kyc first!');
        //           } else {
        //             session()->flash('warning', 'Your account is deactive or pending now. Please Contact with admin!');
        //           }

        //             return redirect()->back();
        //         }
        //     }
        // }
        // return $next($request);
    }
}
