<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = $request->user()?->role;

        if ($role === 'admin') {
            return redirect()->route('admin.login');
        }

        if ($role === 'staff') {
            return redirect()->route('staff.login');
        }

        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect('/');
    }
}
