@extends('layouts.app-back')
@section('title', trans('Navigation::navigation.edit.head') )
@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-offset-3">

            @include('partial.form')

        </div>
    </div>

    <div class="row">

        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-offset-3">

            <div class="panel panel-default">

                <div class="panel-body">

                    <a href="{{ route('get.navigation.index') }}"
                       class="btn btn-default">{{ trans('Navigation::navigation.edit.buttons.return') }}
                    </a>

                    <hr />

                    <form method="post"
                          action="{{ route('get.navigation.edit', ['param' => $modelNavigationEdit->id]) }}"
                          class="col-sm-12 col-md-12 col-xs-12">

                        {{ csrf_field() }}

                        <label for="inputTitle">{{ trans('Navigation::navigation.edit.label.lp') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('lp') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-sort-numeric-up"></i>
                            </span>

                            <input type="text" class="form-control" id="inputTitle" name="lp" value="{{
                                (old('lp'))
                                    ? old('lp')
                                    : $modelNavigationEdit->lp
                            }}">
                        </div>

                        <label for="inputTitle">{{ trans('Navigation::navigation.edit.label.title') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('title') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-pencil-alt"></i>
                            </span>

                            <input type="text" class="form-control" id="inputTitle" name="title" value="{{
                                (old('title'))
                                    ? old('title')
                                    : $modelNavigationEdit->title
                            }}">
                        </div>

                        <hr />

                        <div class="panel-body">

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.edit.label.route_name') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('route_name') ? ' has-danger' : '' }}">

                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-link"></i>
                                    </span>

                                    <select class="form-control" id="routeSelect" name="route_name">
                                        <option value="{{ $defaultRoute }}" {{
                                            ( (!empty(old('route_name')))
                                                ? old('route_name') === $defaultRoute
                                                : $modelNavigationEdit->route_name . ':' . $modelNavigationEdit->required_param === $defaultRoute
                                            ) ? "selected" : ""
                                        }}> ---
                                        </option>
                                        @foreach($navigationAll as $items)
                                            <option value="{{ $items["route_name"] . ':' . $items["required_param"] }}" {{
                                            ( (!empty(old('route_name')))
                                                ? old('route_name') === (string)$items["route_name"] . ':' . $items["required_param"]
                                                : $modelNavigationEdit->route_name . ':' . $modelNavigationEdit->required_param === (string)$items["route_name"] . ':' . $items["required_param"]
                                            ) ? "selected" : ""
                                            }}> {{ $items["route_name"] }} [ Require param: {{ ($items['required_param'] === 1) ? "YES" : "NO" }} ]
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.edit.label.param') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('param') ? ' has-danger' : '' }}">

                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-terminal"></i>
                                    </span>

                                    <input type="text" class="form-control" id="inputParameter" name="param" value="{{
                                        (old('param'))
                                            ? old('param')
                                            : $modelNavigationEdit->param
                                    }}">
                                </div>

                            </div>

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.edit.label.special_param') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('special_param') ? ' has-danger' : '' }}">

                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-hashtag"></i>
                                    </span>

                                    <input type="text" class="form-control" id="inputSParameter" name="special_param" value="{{
                                        ( (!empty(old('special_param')))
                                           ? old('special_param')
                                           : $modelNavigationEdit->special_param )
                                    }}">
                                </div>
                            </div>

                        </div>

                        <hr />

                        <label>{{ trans('Navigation::navigation.edit.label.parent_id') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('parent_id') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-sitemap"></i>
                            </span>

                            @if($getTotalChildren !== 2)
                                <select class="form-control" id="parentSelect" name="parent_id">
                                    <option value="0" {{
                                        ( old('parent_id') === "0" OR $modelNavigationEdit->parent_id === 0)
                                            ? "selected"
                                            : ""
                                        }}> ---
                                    </option>
                                    @foreach($modelNavigationList as $items)
                                        <option value="{{ $items->id }}" {{
                                            ( old('parent_id') === (string)$items->id OR $modelNavigationEdit->parent_id === $items->id)
                                                ? "selected"
                                                : ""
                                            }}> --- {{ $items->title }}
                                        </option>
                                        @if($getTotalChildren !== 1)
                                            @foreach($items->children as $master)
                                                <option value="{{ $master->id }}" {{
                                                    ( old('parent_id') === (string)$items->id OR $modelNavigationEdit->parent_id === $master->id)
                                                        ? "selected"
                                                        : ""
                                                    }}> --- --- {{ $master->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <p class="form-control disabled">Not available to change.</p>
                                <input type="hidden" name="parent_id" value="{{ $modelNavigationEdit->parent_id }}" readonly>
                            @endif

                        </div>

                        <label>{{ trans('Navigation::navigation.edit.label.role_id') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('role_id') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-user-circle"></i>
                            </span>

                            <select class="form-control" id="roleSelect" name="role_id[]">

                                @foreach($modelNavigationForRole as $items)
                                    <option value="{{ $items['id'] }}" {{
                                        (collect(old('role_id'))->contains($items['id'])
                                            ? true
                                            : is_int( check_array($items['id'], $modelNavigationEdit->navigationRole->toArray(), 'role_id' ) )
                                        )   ? 'selected'
                                            : ''
                                        }}> {{ ucfirst($items['name']) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr />

                        <div class="col-sm-12">

                            <div class="col-sm-6">

                                <div class="form-group mb-10 {{ $errors->has('is_active') ? ' has-danger' : '' }}">

                                    <label>{{ trans('Navigation::navigation.edit.label.is_active') }}</label>

                                    <hr />

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="is_active" id="active_0" value="0" {{
                                            (old('is_active') === "0" OR $modelNavigationEdit->is_active === 0 )
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.edit.label.active_no') }}
                                    </label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="is_active" id="active_1" value="1" {{
                                            (old('is_active') === "1" OR $modelNavigationEdit->is_active === 1 )
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.edit.label.active_yes') }}
                                    </label>

                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group mb-10 {{ $errors->has('destiny') ? ' has-danger' : '' }}">

                                    <label>{{ trans('Navigation::navigation.edit.label.destiny') }}</label>

                                    <hr />

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="destiny" id="destiny_0" value="0" {{
                                            (old('destiny') === "0" OR $modelNavigationEdit->destiny === 0 )
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.edit.label.destiny_top') }}
                                    </label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="destiny" id="destiny_1" value="1" {{
                                            (old('destiny') === "1" OR $modelNavigationEdit->destiny === 1 )
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.edit.label.destiny_left') }}
                                    </label>

                                </div>

                            </div>

                        </div>

                        <hr />

                        <button type="submit" class="btn btn-success pull-right">
                            {{ trans('Navigation::navigation.edit.buttons.submit') }}
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

@endsection