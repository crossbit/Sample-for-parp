<?php

namespace App\Modules\Basic\Navigation\Utils\Composers\Back;

use App\Modules\Basic\Navigation\Traits\LoadAvatar;
use App\Modules\Basic\Navigation\Traits\LoadHeader;
use Illuminate\View\View;

class HeaderComposer
{
    use LoadAvatar,
        LoadHeader;

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('backHeader', $this->loadHeader('back'));

        $view->with('backAvatar', $this->loadAvatar());
    }

}