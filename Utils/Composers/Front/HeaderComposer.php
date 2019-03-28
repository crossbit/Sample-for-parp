<?php

namespace App\Modules\Basic\Navigation\Utils\Composers\Front;

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
        $view->with('frontHeader', $this->loadHeader('front'));

        $view->with('frontAvatar', $this->loadAvatar());
    }

}