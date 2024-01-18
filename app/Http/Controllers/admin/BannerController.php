<?php
namespace App\Http\Controllers\admin;

use App\Helpers\AdminUrl;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Models\Banner;
use App\Models\Banner_site;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Route;

class BannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /*banner*/
    public function index($id_website = null, $id_position = null) {
        $data['allSite'] = $allSite = Banner_site::where('type', '=', 'website')->orderBy('title', 'ASC')->get();
        $id_website = $id_website ?? $allSite[0]->id;
        $data['id_website'] = $id_website;

        foreach ($allSite as $site) {
            $listPosition = DB::select("
                SELECT distinct (id_position)
                FROM banner
                WHERE id_website = $site->id
                AND status=1
                AND NOW() BETWEEN IFNULL(start_date,'1900-01-01') AND IFNULL(end_date,NOW())
                ORDER BY `order` ASC, id ASC
            ");
            $site->count_position = count($listPosition);
        }

        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->orderBy('title', 'ASC')->get();
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
            ORDER BY `status` DESC, `order` ASC, id ASC
        ");
        $data['listItem'] = $listItem;
        return view('admin.banner.banner_index', $data);
    }

    public function update($id_banner = 0, $id_website = '', $id_position = '', Request $request) {

        $data = [];
        if ($id_banner > 0){
            $data['oneItem'] = $oneItem = Banner::findOrFail($id_banner);
        }
        $data['type'] = $oneItem->type ?? 'default';

        $data['allSite'] = $allSite = Banner_site::where('type', '=', 'website')->get();
        $data['allPosition'] = $allPosition = Banner_site::where('type', '=', 'position')->get();

        $data['id_website'] = $id_website;
        $data['id_position'] = $id_position;

        if (!empty($request->post())) {
            $post_data = $request->post();
            if (!isset($post_data['clickSubmit'])){
                if (isset($post_data['type']))
                    $data['type'] = $post_data['type'];
                return view('admin.banner.banner_update', $data);
            }
            $post_data['slug'] = toSlug($post_data['title']);
            $post_data['status'] = isset($post_data['status']) ? 1 : 0;
            unset($post_data['clickSubmit']);
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
    public function site($type, $id = null){
        if ($id === null){
            return $this->listSite($type);
        } else {
            return $this->updateOrInsertSite($type, $id);
        }
    }
    private function listSite($type){
        $limit = 10;
        $keySeach = $_GET['keyword'] ?? null;

        $condition = [
            ['type', '=', $type],
            ['title', 'like', '%'.$keySeach.'%']
        ];

        $allSite = Banner_site::where($condition)
            ->paginate($limit);
        $allSite->appends(Request::input());

        $data = [
            'keyword' => $keySeach,
            'data' => $allSite,
            'type' => $type
        ];
        return view('admin.banner.site_index', $data);
    }
    private function updateOrInsertSite($type, $id){
        if ($id > 0)
            $oneItem = Banner_site::findOrFail($id);
        $post_data = Request::post();

        if (!empty($post_data)) {
            $post_data['type'] = $type;
            $post_data['slug'] = toSlug($post_data['title']);
            Banner_site::updateOrInsert(['id' => $id], $post_data);
            return Redirect::to(AdminUrl::getUrlBannerSite($type, null));
        }
        $data = [
            'oneItem' => $oneItem ?? null,
            'type' => $type
        ];
        return view('admin.banner.site_update', $data);
    }

    public function deleteSite($id) {
        Banner_site::destroy($id);
        return back();
    }

    public function duplicate($id) {
        $banner = Banner::where('id', '=', $id)->get()->toArray();
        if (empty($banner))
            return back();

        unset($banner[0]['id']);
        foreach ($banner[0] as $k => $v){
            $dataSave[$k] = $v;
        }

        Banner::updateOrInsert(['id' => 0], $dataSave);
        return back();
    }
    public function updateOrder(Request $input){
        $data = $input->input('order') ?? die();
        $backLink = $input->input('backLink') ?? die();
        foreach ($data as $id => $value){
            Banner::where('id', '=', $id)->update(['order' => $value]);
        }
        return Redirect::to($backLink);
    }
}
