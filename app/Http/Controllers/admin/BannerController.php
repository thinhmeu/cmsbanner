<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner_numsofshow;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Models\Banner;
use App\Models\Banner_site;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public $request;
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    /*banner*/
    public function index() {
        if ($this->request->get('addBanner')){
            return \redirect()->route("getUrlBannerUpdate", array_merge([0], $_GET));
        } elseif ($this->request->get('updateOrder')){
            return $this->updateOrder();
        } elseif ($this->request->get('numsOfShow')){
            return $this->updateNumsOfShow();
        } else {
            return $this->showListBanner();
        }
    }
    private function showListBanner(){
        $id_website = $this->request->get('id_website');
        $id_position = $this->request->get('id_position');
        $keyword = $this->request->get('keyword');

        $allSite = Banner_site::getWebsiteWithCountPosition();
        $allPosition = Banner_site::getPositionWithCountBanner($id_website);
        $listItem = Banner::getListBanner($id_website, $id_position, $keyword);
        if ($id_website && $id_position){
            $nums_of_show = Banner_numsofshow::where("id_website", $id_website)
                ->where("id_position", $id_position)
                ->first()->nums_of_show ?? 1;
        }

        $data = [
            'id_website' => $id_website,
            'id_position' => $id_position,
            'keyword' => $keyword,
            'listItem' => $listItem,
            'allPosition' => $allPosition,
            'allSite' => $allSite,
            'nums_of_show' => $nums_of_show ?? null
        ];
        return view('admin.banner.banner_index', $data);
    }
    private function updateOrder(){
        $data = $this->request->input();
        foreach ($data['order'] as $id => $value){
            Banner::where('id', '=', $id)->update(['order' => $value]);
        }
        unset($data['updateOrder'], $data['order']);
        return Redirect::route("getUrlBannerList", $data);
    }
    private function updateNumsOfShow(){
        $id_website = $this->request->get("id_website");
        $id_position = $this->request->get("id_position");
        $numsOfShow = $this->request->get("nums_of_show");
        if ($id_website && $id_position){
            Banner_numsofshow::updateOrInsert([
                'id_website' => $id_website,
                'id_position' => $id_position
            ], ["nums_of_show" => $numsOfShow]);
        }
        return Redirect::to($this->request->fullUrlWithoutQuery(["numsOfShow", "nums_of_show"]));
    }
    public function updateOrInsertBanner($id_banner = 0) {
        if ($id_banner == 0){
            $id_website = $this->request->get('id_website');
            $id_position = $this->request->get('id_position');
            $type = "default";
        } else {
            $oneItem = Banner::find($id_banner);
            if (empty($oneItem))
                return Redirect::route("getUrlBannerUpdate", [0]);
            $id_website = $oneItem->id_website;
            $id_position = $oneItem->id_position;
            $type = $oneItem->type;
        }

        $allSite = Banner_site::getWebsiteWithCountPosition();
        $allPosition = Banner_site::getPositionWithCountBanner();

        $post_data = $_POST;

        if (!empty($post_data)) {
            if (!isset($post_data['clickSubmit'])){
                /*chuyển type*/
                $type = $_POST['type'] ?? $type;
            } else {
                $post_data = $this->request->post();
                $post_data['slug'] = toSlug($post_data['title']);
                $post_data['alt'] = $post_data['title'];
                unset($post_data['clickSubmit'], $post_data['_token']);
                $post_data['status'] = isset($post_data['status']) ? 1 : 0;
                Banner::updateOrInsert(['id' => $id_banner], $post_data);
                return Redirect::route("getUrlBannerList", ["id_website" => $post_data['id_website'], 'id_position' => $post_data['id_position']]);
            }
        }
        $data = [
            'id_website' => $id_website,
            'id_position' => $id_position,
            'type' => $type,
            'allSite' => $allSite,
            'allPosition' => $allPosition,
            'oneItem' => $oneItem ?? null
        ];
        return view('admin.banner.banner_update', $data);
    }
    public function delete($id) {
        Banner::destroy($id);
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
            ->orderBy('title', 'asc')
            ->paginate($limit);
        $allSite->appends($this->request->input());

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
        $post_data = $this->request->post();

        if (!empty($post_data)) {
            $post_data['type'] = $type;
            $post_data['slug'] = toSlug($post_data['title']);
            Banner_site::updateOrInsert(['id' => $id], $post_data);
            return Redirect::route("getUrlBannerSite", [$type, null]);
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
}
