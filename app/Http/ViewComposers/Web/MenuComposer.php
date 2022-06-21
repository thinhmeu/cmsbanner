<?php

namespace App\Http\ViewComposers\Web;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $mainMenuPc = Menu::find(3);
        if (!empty($mainMenuPc)) {
            $mainMenuPc = json_decode($mainMenuPc->data, 1);
            $data['MenuFooter'] = $mainMenuPc;
        }
        $subMenuPc = Menu::find(1);
        if (!empty($subMenuPc)) {
            $subMenuPc = json_decode($subMenuPc->data, 1);
            $data['MainMenu'] = $subMenuPc;
        }
        $view->with($data);
    }
}
