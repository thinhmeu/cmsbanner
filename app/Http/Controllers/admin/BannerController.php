<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Request;
use Redirect;
use App\Models\Banner;
use App\Models\Banner_site;

class BannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /*banner*/
    public function index($id_website = null, $id_position = null) {
        $data['allSite'] = $allSite = Banner_site::where('type', '=', 'website')->get();
        $id_website = $id_website ?? $allSite[0]->id;
        $data['id_website'] = $id_website;

        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->get();
        $id_position = $id_position ?? $allPosition[0]->id;
        $data['id_position'] = $id_position;

        $condition = [
            ['id_website', '=', $id_website],
            ['id_position', '=', $id_position]
        ];
        $listItem = Banner::where($condition)->get();
        $data['listItem'] = $listItem;
        return view('admin.banner.banner_index', $data);
    }

    public function update($id_banner = 0) {
        $data = [];
        if ($id_banner > 0){
            $data['oneItem'] = $oneItem = Banner::findOrFail($id_banner);
        }

        $data['allSite'] = $allSite = Banner_site::where('type', '=', 'website')->get();
        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->get();

        if (!empty(Request::post())) {
            $post_data = Request::post();
            $post_data['slug'] = toSlug($post_data['title']);
            $post_data['status'] = isset($post_data['status']) ? 1 : 0;
            Banner::updateOrInsert(['id' => $id_banner], $post_data);
            return Redirect::to('/admin/banner');
        }
        return view('admin.banner.banner_update', $data);
    }

    public function delete($id) {
        Banner::destroy($id);
        return back();
    }

    /*banner site*/
    public function site($type){
        $data['page'] = $page = $_GET['page'] ?? 1;
        $data['s'] = $keySeach = $_GET['s'] ?? null;
        $data['limit'] = $limit = 10;

        $condition = [
            ['type', '=', $type],
            ['title', 'like', '%'.$keySeach.'%']
        ];
        $data['pagination'] = $pagination = ceil(Banner_site::where($condition)->count() / $limit);

        $allSite = Banner_site::where($condition)
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        $data['allSite'] = $allSite;
        $data['type'] = $type;

        return view('admin.banner.site_index', $data);
    }

    public function updateSite($type, $id = 0) {
        $data = [];
        if ($id > 0)
            $data['oneItem'] = $oneItem = Banner_site::findOrFail($id);
        $data['type'] = $type;

        $post_data = Request::post();

        if (!empty($post_data)) {
            $post_data['type'] = $type;
            $post_data['slug'] = toSlug($post_data['title']);
            Banner_site::updateOrInsert(['id' => $id], $post_data);
            return Redirect::to('/admin/banner/site/'.$type);
        }
        return view('admin.banner.site_update', $data);
    }

    public function deleteSite($id) {
        Banner_site::destroy($id);
        return back();
    }
}
