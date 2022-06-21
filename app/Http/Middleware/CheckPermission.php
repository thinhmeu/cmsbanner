<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use App\Models\Group;
use Illuminate\Support\Facades\URL;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_group_id = Auth::user()->group_id;
        $group = Group::find($user_group_id);
        $permission = json_decode($group->permission, 1);
        define('ADMIN', $group->id == 1 ? 1 : 0);
        #
        $controller = getCurrentController();
        $action = getCurrentAction();

        if (!ADMIN && in_array($controller, $permission) && $action != 'logout') {
            if ($action == 'update') {
                if (!empty(getCurrentParams())) $action = 'edit';
                else $action = 'add';
            }
            if (empty($permission[$controller][$action])) {
                return redirect('/admin/home');
            }
        }

        session_start();
        $_SESSION['user'] = Auth::user()->username;

        return $next($request);
    }
}
