<?php

namespace App\Modules\Basic\Navigation\Traits;

trait LoadNavigation {

    /**
     * loadNavigation
     *
     * Set collection for navigation
     * for frontend and backend site.
     *
     * @param string $type
     * @return array
     */
    public function loadNavigation(string $type) {

        switch($type) {
            case 'back':
                return get_collection('backNavigation');
                break;
            case 'front':
                return get_collection('frontNavigation');
                break;
            default:
                return get_collection('errorNavigation');
        }

    }

}
