<?php

namespace App\Modules\Basic\Navigation\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Modules\Basic\Acl\Models\Role;
use App\Modules\Basic\Navigation\Traits\RouteList;
use App\Modules\Basic\Navigation\Models\Navigation;
use App\Modules\Basic\Navigation\Models\NavigationRole;

class NavigationController extends Controller {

    use RouteList;

    private $authInstance;
    private $authUserRole;

    private $defaultRole;

    /**
     * NavigationController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authInstance = Auth::user();
            $this->authUserRole = Auth::user()->roles()->get();
            return $next($request);
        });
        $this->middleware('auth');

        $this->loadRouteList();

        $this->defaultRole = [
            [
                'id' => 0 ,
                'name' => 'public'
            ]
        ];

    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * getIndex
     *
     * @param Request $request
     * @return mixed
     */
    public function getIndex(Request $request)
    {
        $modelNavigationList = [];
        $groupModel = Role::orderBy('id')
            ->get(['id', 'name'])
            ->toArray();

        $modelNavigationForRole = array_merge_recursive($this->defaultRole, $groupModel);

        foreach($modelNavigationForRole as $items) {
            $modelNavigationList[] = [
                'model' => Navigation::buildTree()
                    ->whereHas('navigationRole', function ($query) use ($items) {
                        $query->where('role_id', $items['id']);
                    })
                    ->orderBy('parent_id')
                    ->get()
            ];
        }

        return view('Navigation::back.navigation.index', [
            'modelNavigationList'       => $modelNavigationList,
            'modelNavigationForRole'    => $modelNavigationForRole,

            'defaultRole' => flatten_array( $this->defaultRole )[1]
        ]);
    }


    /**
     * getEdit
     *
     * @param Request $request
     * @param string|null $param
     * @return mixed
     */
    public function getEdit(Request $request, string $param = null)
    {
        if (is_null($param)) {
            abort('404');
        }

        $modelNavigationList = Navigation::buildTree()
            ->where('id', '<>', $param)
            ->orderBy('parent_id')
            ->get();

        $modelNavigationEdit = Navigation::where('id', $param)
            ->with('navigationRole')
            ->first();

        $modelRoles = Role::orderBy('id')
            ->get(['id', 'name'])
            ->toArray();

        $modelNavigationForRole = array_merge_recursive($this->defaultRole, $modelRoles);

        $countNavi = Navigation::find($param);
        $getTotalChildren = $countNavi->countChildrenNests();

        return view('Navigation::back.navigation.edit', [
            'modelNavigationEdit'        => $modelNavigationEdit,
            'modelNavigationList'        => $modelNavigationList,
            'modelNavigationForRole'     => $modelNavigationForRole,

            'navigationAll'              => $this->navigationAll,
            'defaultRoute'               => $this->defaultRoute,

            'getTotalChildren'           => $getTotalChildren
        ]);
    }

    /**
     * postEdit
     *
     * @param Request $request
     * @param string|null $param
     * @return mixed
     */
    public function postEdit(Request $request, string $param = null)
    {
        $this->validate($request, [
            'title' => 'required|max:140',
            'lp' => 'required',
            'route_name' => 'required|max:170',
            'parent_id' => 'required',
            'destiny' => 'required'
        ], [], [
            'title' => trans('Navigation::navigation.edit.label.title'),
            'lp' => trans('Navigation::navigation.edit.label.lp'),
            'route_name' => trans('Navigation::navigation.edit.label.route_name'),
            'parent_id' => trans('Navigation::navigation.edit.label.parent_id'),
            'destiny' => trans('Navigation::navigation.edit.label.destiny')
        ]);

        if (strpos($request->input('route_name'), $this->defaultRoute) !== false) {
            $explode = explode(
                $this->delimeter,
                $this->defaultRoute
            );
        } else {
            $explode = explode(
                $this->delimeter,
                $request->input('route_name')
            );
        }

        if (is_int(check_array($explode[0], $this->navigationWithParam, 'route_name'))) {
            $this->validate($request, [
                'param' => 'required'
            ], [], [
                'param' => trans('Navigation::navigation.edit.label.param')
            ]);
        }

        $this->validate($request, [
            'is_active' => 'required'
        ], [], [
            'is_active' => trans('Navigation::navigation.edit.label.is_active')
        ]);

        if($request->input('parent_id') === 0) {
            $this->validate($request, [
                'role_id' => 'required'
            ], [], [
                'role_id' => trans('Navigation::navigation.add.label.role_id')
            ]);
        }

        $label = $request->only(
            "title",
            "lp",
            "route_name",
            "parent_id",
            "param",
            "special_param",
            "is_active",
            "role_id",
            "destiny"
        );


        $affectedMiddleware = [
            'title' => $label['title'],
            'lp' => $label['lp'],
            'route_name' => $explode[0],
            'parent_id' => $label['parent_id'],
            'param' => $label['param'],
            'special_param' => $label['special_param'],
            'required_param' => $explode[1],
            'is_active' => $label['is_active'],
            'destiny' => $label['destiny']
        ];


        $affectedRows = Navigation::where('id', $param)
            ->update($affectedMiddleware);


        // Role Id
        $affectedRowsRole = [];
        NavigationRole::where('navigation_id', $param)->forceDelete();
        if($label['parent_id'] === "0") {
            foreach ($label['role_id'] as $items) {
                $affectedRowsRole[] = NavigationRole::create([
                    'navigation_id' => $param,
                    'role_id' => $items
                ]);
            }
        } else {
            $affectedRowsRole = [
                true
            ];
        }

        if($affectedRows &&
            in_array(true, $affectedRowsRole)
        ) {
            return redirect(
                route('get.navigation.index')
            )->with('success', trans('Navigation::navigation.controller.stage_3'));
        }

        return redirect(
            route('get.navigation.index')
        )->with('info', trans('Navigation::navigation.controller.stage_4'));
    }


    /**
     * getAdd
     *
     * @param Request $request
     * @return mixed
     */
    public function getAdd(Request $request)
    {
        $navigationList = Navigation::buildTree()
            ->orderBy('parent_id')
            ->get();

        $groupModel = Role::orderBy('id')
            ->get(['id', 'name'])
            ->toArray();

        $navigationForRole = array_merge_recursive($this->groupModelDefault, $groupModel);

        return view(
            'Navigation::back.navigation.add', [
            'navigationList'        => $navigationList,
            'navigationForRole'     => $navigationForRole,
            'navigationAll'         => $this->navigationAll,
            'defaultRoute'          => $this->defaultRoute
        ]);
    }

    /**
     * postAdd
     *
     * @param Request $request
     * @return mixed
     */
    public function postAdd(Request $request)
    {
        if(is_null($request->input('auto_lp'))){
            $this->validate($request, [
                'lp'    => 'required|integer'
            ], [], [
                'lp'    => trans('Navigation::navigation.add.label.lp')
            ]);
        }

        $this->validate($request, [
            'title'         => 'required|max:140',
            'route_name'    => 'required|max:170',
            'parent_id'     => 'required',
            'destiny'       => 'required'
        ], [], [
            'title'         => trans('Navigation::navigation.add.label.title'),
            'route_name'    => trans('Navigation::navigation.add.label.route_name'),
            'parent_id'     => trans('Navigation::navigation.add.label.parent_id'),
            'destiny'       => trans('Navigation::navigation.add.label.destiny')
        ]);

        if( strpos($request->input('route_name'), $this->defaultRoute) !== false ) {
            $explode = explode(
                $this->delimeter,
                $this->defaultRoute
            );
        } else {
            $explode = explode(
                $this->delimeter,
                $request->input('route_name')
            );
        }

        if( is_int( check_array($explode[0], $this->navigationWithParam, 'route_name') ) ) {
            $this->validate($request, [
                'param' => 'required'
            ], [], [
                'param' => trans('Navigation::navigation.add.label.param')
            ]);
        }

        $this->validate($request, [
            'is_active' => 'required',
            'role_id' => 'required'
        ], [], [
            'is_active' => trans('Navigation::navigation.add.label.is_active'),
            'role_id' => trans('Navigation::navigation.add.label.role_id')
        ]);

        if($request->input('parent_id') === 0) {
            $this->validate($request, [
                'role_id' => 'required'
            ], [], [
                'role_id' => trans('Navigation::navigation.add.label.role_id')
            ]);
        }

        $label = $request->only(
            "title",
            "auto_lp",
            "lp",
            "route_name",
            "parent_id",
            "param",
            "special_param",
            "is_active",
            "role_id",
            "destiny"
        );

        $affectedMiddleware = [
            'title'             => $label['title'],
            'route_name'        => $explode[0],
            'parent_id'         => $label['parent_id'],
            'param'             => $label['param'],
            'special_param'     => $label['special_param'],
            'required_param'    => $explode[1],
            'is_active'         => $label['is_active'],
            'destiny'           => $label['destiny']

        ];

        if(!is_null($label['auto_lp'])) {
            $maxLp = Navigation::where('parent_id', $affectedMiddleware['parent_id'])->max('lp');
            if(is_null($maxLp)) {
                $affectedMiddleware['lp'] = 1;
            } else {
                $affectedMiddleware['lp'] = ($maxLp + 1);
            }
        }


        $affectedRows = Navigation::create($affectedMiddleware);


        // Role Id
        $affectedRowsRole = [];
        if($label['parent_id'] === "0") {
            foreach ($label['role_id'] as $items) {
                $affectedRowsRole[] = NavigationRole::create([
                    'navigation_id' => $affectedRows->id,
                    'role_id' => $items
                ]);
            }
        } else {
            $affectedRowsRole = [
                true
            ];
        }


        if( $affectedRows &&
            in_array(true, $affectedRowsRole)
        ) {
            return redirect(
                route('get.navigation.index')
            )->with('success', trans('Navigation::navigation.controller.stage_1'));
        }

        return redirect(
            route('get.navigation.index')
        )->with('info', trans('Navigation::navigation.controller.stage_2'));

    }


    /**
     * getDelete
     *
     * @param Request $request
     * @param string|null $param
     * @param string|null $type
     * @return mixed
     */
    public function getDelete(Request $request, string $param = null, string $type = null)
    {
        if (is_null($param) || is_null($type)) {
            abort('404');
        }

        $modelNavigationDelete = Navigation::where(
            'id',
            $param
        );

        switch (strtoupper($type)) {
            case "S":
                $result = (is_null($modelNavigationDelete))
                    ? false
                    : $modelNavigationDelete->delete();
                break;
            case "F":
                $result = (is_null($modelNavigationDelete))
                    ? false
                    : $modelNavigationDelete->forceDelete();
                break;
            default:
                $result = false;
        }

        if($result) {
            return redirect(
                route('get.navigation.index')
            )->with('success', trans('Navigation::navigation.controller.stage_5'));
        }

        return redirect(
            route('get.navigation.index')
        )->with('info', trans('Navigation::navigation.controller.stage_6'));
    }

    /**
     * getRestore
     *
     * @param Request $request
     * @param string|null $param
     * @return mixed
     */
    public function getRestore(Request $request, string $param = null)
    {
        if (is_null($param)) {
            abort('404');
        }

        $modelNavigationRestore = Navigation::onlyTrashed()->where(
            'id',
            $param
        );

        $result = ( is_null($modelNavigationRestore) )
            ? false
            : $modelNavigationRestore->restore();

        if($result) {
            return redirect(
                route('get.navigation.index')
            )->with('success', trans('Navigation::navigation.controller.stage_7'));
        }

        return redirect(
            route('get.navigation.index')
        )->with('info', trans('Navigation::navigation.controller.stage_8'));
    }

}

