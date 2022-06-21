<?php

namespace App\Http\ViewComposers\Web;

use App\Models\BoxNews;
use Illuminate\View\View;
use App\Models\Post;

class SidebarComposer
{
    public function compose(View $view)
    {
        $oneItem = BoxNews::find(3);
        if (!empty($oneItem)) {
            $oneItem = json_decode($oneItem->data);
            foreach ($oneItem as $key => $value) {
                $oneItem[$key]->item = Post::where('status', 1)->where('category_id', $value->id)->orderBy('id', 'desc')->limit(6)->get();
            }
            $data['sidebar'] = $oneItem;
        }
        $data['new_post'] = Post::where('status', 1)->orderBy('id', 'desc')->limit(5)->get();
        $view->with($data);
    }
}
