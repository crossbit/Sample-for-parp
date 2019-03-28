<?php

namespace App\Modules\Basic\Navigation\Traits;

trait LoadHeader {

    /**
     * loadHeader
     *
     * Set headers for frontend and backend site.
     *
     * @param string $type
     * @return string
     */
    public function loadHeader(string $type) {

        switch($type) {
            case 'back':
                return get_collection('backHeader');
                break;
            case 'front':
                return get_collection('frontHeader');
                break;
            default:
                return get_collection('errorHeader');
        }

    }

}
