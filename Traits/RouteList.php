<?php

namespace App\Modules\Basic\Navigation\Traits;

use Illuminate\Support\Facades\Route;

trait RouteList {

    /**
     * The default route (from module config).
     *
     * @var string
     */
    private $defaultRoute;

    /**
     * The delimeter for explode (from module config).
     *
     * @var string
     */
    private $delimeter;

    /**
     * Get from route all navigation.
     *
     * @var array
     */
    private $navigationAll;

    /**
     * Get from route all permission.
     *
     * @var array
     */
    private $middlewarePerm;

    /**
     * Collect only navigation with param.
     *
     * @var array
     */
    private $navigationWithParam;

    /**
     * Collect only navigation without param.
     *
     * @var array
     */
    private $navigationWithoutParam;

    /**
     * loadRouteList
     *
     * Take all links and sort by the parameters.
     * Parameters : (get, post, permission, with param, without param).
     */
    public function loadRouteList()
    {
        $this->defaultRoute             = system_config('modules.navigation.default');
        $this->delimeter                = system_config('modules.navigation.delimeter');

        $this->navigationAll            = [];
        $this->middlewarePerm           = [];
        $this->navigationWithParam      = [];
        $this->navigationWithoutParam   = [];

        $actionsAll                     = [];
        $middlewarePerm                 = [];
        $actionsWithParam               = [];
        $actionsWithoutParam            = [];

        foreach (Route::getRoutes() as $route)
        {
            if(count($middleware = $route->middleware()) > 0) {
                foreach($middleware as $item) {
                    if (strpos($item, 'permission:') !== false) {

                        $explode = @explode(':', $item);

                        $middlewarePerm[] = [
                            'permission_name'   => $explode[1]
                        ];
                    }
                }
            }

            if (strpos($item = $route->getName(), 'get.') !== false) {
                if( count($route->parameterNames()) > 0 ) {
                    $actionsWithParam[] = [
                        'route_name'    => $item
                    ];
                } else if( count($route->parameterNames()) === 0 ) {
                    $actionsWithoutParam[] = [
                        'route_name'    => $item
                    ];
                }
                $actionsAll[] = [
                    'route_name'        => $item,
                    'required_param'    => (count($route->parameterNames()) > 0) ? 1 : 0
                ];
            }
        }

        $this->middlewarePerm = array_values(
            array_unique($middlewarePerm, SORT_REGULAR)
        );

        $this->navigationWithParam = array_values(
            array_unique($actionsWithParam, SORT_REGULAR)
        );

        $this->navigationWithoutParam = array_values(
            array_unique($actionsWithoutParam, SORT_REGULAR)
        );

        $this->navigationAll = array_values(
            array_unique($actionsAll, SORT_REGULAR)
        );
    }

}
