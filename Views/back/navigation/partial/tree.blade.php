
    @forelse ($modelNavigationList as $count => $item)

        <tr class="table_{{ $looper }}_dimension">
            @if($looper === 1)
                <td class="text-center col-md-0">
                    <i class="fas fa-caret-square-o-right"
                       aria-hidden="true"
                       data-placement="top"
                       data-toggle="tooltip"
                       title={{ trans('Navigation::navigation.index.label.id') }}></i>
                    <i>{{ $item->id }}</i>
                    | <i class="fas fa-clone fa-rotate-180"
                         aria-hidden="true"
                         data-placement="top"
                         data-toggle="tooltip"
                         title="{{ trans('Navigation::navigation.index.label.children') }}"></i>
                    <i>{{ $item->countChildren() }}</i>
                </td>
                <td class="text-center col-md-0"></td>
                <td class="text-center col-md-0"></td>
            @elseif($looper === 2)
                <td class="text-center col-md-0"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></td>
                <td class="text-center col-md-0">
                    <i class="fas fa-caret-square-o-right"
                       aria-hidden="true"
                       data-placement="top"
                       data-toggle="tooltip"
                       title={{ trans('Navigation::navigation.index.label.id') }}></i>
                    <i>{{ $item->id }}</i>
                    | <i class="fas fa-clone fa-rotate-180"
                         aria-hidden="true"
                         data-placement="top"
                         data-toggle="tooltip"
                         title="{{ trans('Navigation::navigation.index.label.children') }}"></i>
                    <i>{{ $item->countChildren() }}</i>
                </td>
                <td class="text-center col-md-0"></td>
            @elseif($looper === 3)
                <td class="text-center col-md-0"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></td>
                <td class="text-center col-md-0"><i class="fas fa-ellipsis-h" aria-hidden="true"></i></td>
                <td class="text-center col-md-0">
                    <i class="fas fa-caret-square-o-right"
                       aria-hidden="true"
                       data-placement="top"
                       data-toggle="tooltip"
                       title={{ trans('Navigation::navigation.index.label.id') }}></i>
                    <i>{{ $item->id }}</i>
                    | <i class="fas fa-clone fa-rotate-180"
                         aria-hidden="true"
                         data-placement="top"
                         data-toggle="tooltip"
                         title="{{ trans('Navigation::navigation.index.label.children') }}"></i>
                    <i>{{ $item->countChildren() }}</i>
                </td>
            @endif
            <td>{{ $item->lp }}</td>
            <td>
                <b>{{ $item->title }}</b>
            </td>
            <td>{{ $item->route_name }}</td>
            <td>
                <div class="input-group mb-10">
                    <span class="input-group-addon btn-default">{{ config('app.url') }}</span>
                    <div class="form-control" >{{ route($item->route_name, [], false)  }}</div>
                </div>
            </td>
            <td class="text-center col-md-0">{!! check_if_param($item->required_param) !!}</td>
            <td class="text-center col-md-1">{{ $item->param }}</td>
            <td class="text-center col-md-1">{{ $item->special_param }}</td>
            <td class="text-center">{!! check_status($item->is_active) !!}</td>
            <td class="text-center">{!! check_destiny($item->destiny) !!}</td>
            <td class="text-center">
                <ul class="list-group">
                    @forelse($item->navigationRole as $ix)
                        <li class="list-group-item">
                            <p class="label label-primary">
                                {{
                                    is_null($role = $ix->translateRole)
                                        ? ucfirst($defaultRole)
                                        : ucfirst($role->name)
                                }}
                            </p>
                        </li>
                    @empty
                        <li class="list-group-item">
                            <p class="label label-dark">
                                {{ trans('Navigation::navigation.index.label.parent_id') }}
                            </p>
                        </li>
                    @endforelse
                </ul>
            </td>
            <td class="text-center">{!! check_status($item->trashed()) !!}</td>
            <td class="col-md-2">
                <b>{{ trans('Navigation::navigation.index.label.create') }}</b>: {{ ($item->created_at) }}<br>
                <b>{{ trans('Navigation::navigation.index.label.update') }}</b>: {{ ($item->updated_at) }}<br>
                <b>{{ trans('Navigation::navigation.index.label.delete') }}</b>: {{ ($item->deleted_at) }}<br>
            </td>
            <td class="text-center">

                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cogs"></i> {{ trans('Navigation::navigation.index.label.tools') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">

                        @if($item->trashed())

                            <li>
                                <a href="{{
                                    route('get.navigation.delete', ['param' => $item->id, 'type' => 'F'])
                                }}">{{ trans('Navigation::navigation.index.buttons.force_delete') }}</a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a href="{{
                                    route('get.navigation.restore', ['param' => $item->id])
                                }}">{{ trans('Navigation::navigation.index.buttons.restore') }}</a>
                            </li>

                        @else

                            <li>
                                <a href="{{
                                    route('get.navigation.delete', ['param' => $item->id, 'type' => 'S'])
                                }}">{{ trans('Navigation::navigation.index.buttons.soft_delete') }}</a>
                            </li>

                            <li>
                                <a href="{{
                                    route('get.navigation.delete', ['param' => $item->id, 'type' => 'F'])
                                }}" onclick="return confirm('{{ trans('Navigation::navigation.index.confirm') }}');">
                                    {{ trans('Navigation::navigation.index.buttons.force_delete') }}
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a href="{{
                                    route('get.navigation.edit', [ 'param' => $item->id ])
                                }}">{{ trans('Navigation::navigation.index.buttons.edit') }}</a>
                            </li>

                        @endif

                    </ul>
                </div>

            </td>
        </tr>


        @include('Navigation::back.navigation.partial.tree', [
            'modelNavigationList' => $item->children,
            'defaultRole' => $defaultRole,
            'looper' => $looper + 1
        ])

    @empty

    @endforelse

