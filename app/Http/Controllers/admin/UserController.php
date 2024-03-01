<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner_site;
use App\Models\User_site;
use Request;
use Auth;
use App\Models\User;
use App\Models\Group;
use Redirect;
use Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index() {
        $keyword = $_GET['keyword'] ?? '';
        $data['listItem'] = User::where('username', 'like', "%$keyword%")->paginate(10);
        $data['listItem']->appends($_GET);
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
        if ($id > 0) $data['oneItem'] = User::findOrFail($id);
        if (!empty(Request::post())) {
            $post_data = Request::post();
            if (!empty($post_data['password'])) $post_data['password'] = bcrypt($post_data['password']);
            else unset($post_data['password']);
            if (isset($post_data['user_site'])){
                $user_site = [];
                foreach ($post_data['user_site'] as $site_id){
                    $user_site[] = [
                        'user_id' => $id,
                        'site_id' => $site_id
                    ];
                }
                unset($post_data['user_site']);

                User_site::where("user_id", "=", $id)->delete();
                User_site::insert($user_site);
            }

            User::updateOrInsert(['id' => $id], $post_data);
            return Redirect::to('/admin/user');
        }
        $data['listGroup'] = Group::all();
        $data['listSite'] = Banner_site::where("type", '=', "website")->get();
        $data['userSite'] = User_site::where("user_id", '=', $id)->get()->pluck("site_id")->toArray();
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
