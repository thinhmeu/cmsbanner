<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Request;
use Auth;
use App\Models\User;
use App\Models\Group;
use Redirect;
use Response;

class UserController extends Controller
{
    public function index() {
        $data['listItem'] = User::all();
        return view('admin.user.index', $data);
    }

    public function login() {
        if(Auth::check()) return redirect('/admin/home');
        if (!empty(Request::post())) {
            if (Auth::attempt(Request::post())) {
                return Response::json(['status' => 'success']);
            } else {
                return Response::json(['status' => 'fail']);
            }
        }
        return view('admin.user.login');
    }

    public function update($id = 0) {
        $data = [];
        if ($id > 0) $data['oneItem'] = $oneItem = User::findOrFail($id);
        if (!empty(Request::post())) {
            $post_data = Request::post();
            if (!empty($post_data['password'])) $post_data['password'] = bcrypt($post_data['password']);
            else unset($post_data['password']);
            User::updateOrInsert(['id' => $id], $post_data);
            return Redirect::to('/admin/user');
        }
        $data['listGroup'] = Group::all();
        return view('admin.user.update', $data);
    }

    public function delete($id) {
        User::destroy($id);
        return back();
    }

    public function logout() {
        Auth::logout();
        session_destroy();
        return Redirect::to('/admin/login');
    }
}
