<?php

namespace App\Modules\Basic\Navigation\Traits;

use Illuminate\Support\Facades\Auth;

trait LoadAvatar {

    /**
     * loadAvatar
     *
     * Get current avatar for user.
     * If not defined set default.
     *
     * @return string
     */
    public function loadAvatar() {
        $defaultAVImage = system_config('modules.users.default_avatar');
        $userAvatar = set_avatar_display(
            $defaultAVImage,
            'jpg',
            '',
            ''
        );
        if (Auth::guard()->check()) {
            $getUser = Auth::user()->avatars()->first();
            if(!is_null($getUser)){
                $userAvatar = set_avatar_display(
                    $getUser->avatar_data,
                    $getUser->avatar_ext,
                    '',
                    ''
                );
            }
        }
        return $userAvatar;
    }

}
