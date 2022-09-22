<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Request;
use Redirect;
use App\Models\Banner;
use App\Models\Banner_site;
use Illuminate\Support\Facades\DB;

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

        foreach ($allSite as $site) {
            $listPosition = DB::select("
                SELECT distinct(id_position)
                FROM banner
                WHERE id_website = $site->id
                AND status=1
                AND NOW() BETWEEN IFNULL(start_date,'1900-01-01') AND IFNULL(end_date,NOW())
                ORDER BY `order` ASC, id ASC
            ");
            $site->count_position = count($listPosition);
        }

        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->get();
        $id_position = $id_position ?? $allPosition[0]->id;
        $data['id_position'] = $id_position;

        foreach ($allPosition as $position) {
            $listBanner = DB::select("
                SELECT *
                FROM banner
                WHERE id_website = $id_website
                AND id_position = $position->id
                AND status=1
                AND NOW() BETWEEN IFNULL(start_date,'1900-01-01') AND IFNULL(end_date,NOW())
            ");
            $position->count_banner = count($listBanner);
        }

        $listItem = DB::select("
            SELECT id, `order`, title, image, link, width, height, (STATUS = 1 AND NOW() BETWEEN IFNULL(start_date, NOW()) AND IFNULL(end_date, NOW())) AS really_status
            FROM banner
            WHERE id_website = $id_website
            AND id_position = $id_position
            ORDER BY `order` ASC, id ASC
        ");
        $data['listItem'] = $listItem;
        return view('admin.banner.banner_index', $data);
    }

    public function update($id_banner = 0, $id_website = '', $id_position = '') {
        $data = [];
        if ($id_banner > 0){
            $data['oneItem'] = $oneItem = Banner::findOrFail($id_banner);
        }

        $data['allSite'] = $allSite = Banner_site::where('type', '=', 'website')->get();
        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->get();

        $data['id_website'] = $id_website;
        $data['id_position'] = $id_position;

        if (!empty(Request::post())) {
            $post_data = Request::post();
            $post_data['slug'] = toSlug($post_data['title']);
            $post_data['status'] = isset($post_data['status']) ? 1 : 0;
            Banner::updateOrInsert(['id' => $id_banner], $post_data);
            return Redirect::to('/admin/banner/'.$post_data['id_website'].'/'.$post_data['id_position']);
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
