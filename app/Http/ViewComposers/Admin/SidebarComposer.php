<?php

namespace App\Http\ViewComposers\Admin;

use Illuminate\View\View;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        $user_group_id = Auth::user()->group_id;
        $group = Group::find($user_group_id);
        $data['permission'] = json_decode($group->permission, 1);
        $view->with($data);
    }
}
