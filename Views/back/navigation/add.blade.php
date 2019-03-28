@extends('layouts.app-back')
@section('title', trans('Navigation::navigation.add.head') )
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
                       class="btn btn-default">{{ trans('Navigation::navigation.add.buttons.return') }}
                    </a>

                    <hr />

                    <form method="post"
                          action="{{ route('get.navigation.add') }}"
                          class="col-sm-12 col-md-12 col-xs-12">

                        {{ csrf_field() }}

                        <label>{{ trans('Navigation::navigation.add.label.lp') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('lp') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-sort-numeric-up"></i>
                            </span>

                            <input type="text" class="form-control" id="inputTitle" name="lp" value="{{
                                old('lp')
                            }}">
                        </div>

                        <div class="input-group mb-10 {{ $errors->has('lp') ? ' has-danger' : '' }}">
                            <div class="checkbox">
                                <label class="i-checks">
                                    <input type="checkbox" value="1" name="auto_lp" {{
                                        old('auto_lp')
                                            ? 'checked'
                                            : ''
                                    }}> <i></i> {{ trans('Navigation::navigation.add.label.auto_lp') }}
                                </label>
                            </div>
                        </div>

                        <hr />

                        <label>{{ trans('Navigation::navigation.add.label.title') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('title') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-pencil-alt"></i>
                            </span>

                            <input type="text" class="form-control" id="inputTitle" name="title" value="{{
                                old('title')
                            }}">
                        </div>

                        <hr />

                        <div class="panel-body">

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.add.label.route_name') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('route_name') ? ' has-danger' : '' }}">
                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-link"></i>
                                    </span>
                                    <select class="form-control" id="routeSelect" name="route_name">

                                        <option value="{{ $defaultRoute }}" {{
                                        ( old('route_name') === $defaultRoute )
                                            ? "selected"
                                            : ""
                                        }}> ---
                                        </option>

                                        @foreach($navigationAll as $items)
                                            <option value="{{ $items["route_name"] . ':' . $items["required_param"] }}" {{
                                            ( old('route_name') === (string)$items["route_name"] . ':' . (string)$items["required_param"] )
                                                ? "selected"
                                                : ""
                                            }}> {{ $items["route_name"] }} [ Require param: {{ ($items['required_param'] === 1) ? "YES" : "NO" }} ]
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.add.label.param') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('param') ? ' has-danger' : '' }}">
                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-terminal"></i>
                                    </span>
                                    <input type="text" class="form-control" id="inputParameter" name="param" value="{{
                                        old('param')
                                    }}">
                                </div>

                            </div>

                            <div class="col-md-4">

                                <label>{{ trans('Navigation::navigation.add.label.special_param') }}</label>

                                <div class="input-group mb-10 {{ $errors->has('special_param') ? ' has-danger' : '' }}">
                                    <span class="input-group-addon btn-default">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" class="form-control" id="inputSParameter" name="special_param" value="{{
                                        old('special_param')
                                    }}">
                                </div>
                            </div>

                        </div>

                        <hr/>

                        <label>{{ trans('Navigation::navigation.add.label.parent_id') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('parent_id') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-sitemap"></i>
                            </span>

                            <select class="form-control" id="parentSelect" name="parent_id">
                                <option value="0" {{
                                    ( old('parent_id') === 0 )
                                        ? "selected"
                                        : ""
                                    }}> ---
                                </option>
                                @foreach($navigationList as $items)
                                    <option value="{{ $items->id }}" {{
                                        ( old('parent_id') === (string)$items->id )
                                            ? "selected"
                                            : ""
                                        }}> --- {{ $items->title }}
                                    </option>
                                    @foreach($items->children as $master)
                                        <option value="{{ $master->id }}" {{
                                            ( old('parent_id') === (string)$master->id )
                                                ? "selected"
                                                : ""
                                            }}> --- --- {{ $master->title }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <label>{{ trans('Navigation::navigation.add.label.role_id') }}</label>
                        <div class="input-group mb-10 {{ $errors->has('role_id') ? ' has-danger' : '' }}">

                            <span class="input-group-addon btn-default">
                                <i class="fas fa-user-circle"></i>
                            </span>

                            <select class="form-control" id="roleSelect" name="role_id[]">
                                @foreach($navigationForRole as $items)
                                    <option value="{{ $items['id'] }}" {{
                                        (collect(old('role_id'))->contains($items['id']))
                                            ? "selected"
                                            : ""
                                        }}> {{ ucfirst($items['name']) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr />

                        <div class="col-sm-12">

                            <div class="col-sm-6">

                                <div class="form-group mb-10 {{ $errors->has('is_active') ? ' has-danger' : '' }}">

                                    <label>{{ trans('Navigation::navigation.add.label.is_active') }}</label>

                                    <hr />

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="is_active" id="active_0" value="0" checked {{
                                            (old('is_active') === "0")
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.add.label.active_no') }}
                                    </label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="is_active" id="active_1" value="1" {{
                                            (old('is_active') === "1")
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.add.label.active_yes') }}
                                    </label>

                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group mb-10 {{ $errors->has('destiny') ? ' has-danger' : '' }}">

                                    <label>{{ trans('Navigation::navigation.add.label.destiny') }}</label>

                                    <hr />

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="destiny" id="destiny_0" value="0" checked {{
                                            (old('destiny') === "0")
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.add.label.destiny_top') }}
                                    </label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" name="destiny" id="destiny_1" value="1" {{
                                            (old('destiny') === "1")
                                                ? "checked"
                                                : ""
                                        }}> <i></i> {{ trans('Navigation::navigation.add.label.destiny_left') }}
                                    </label>

                                </div>

                            </div>

                        </div>


                        <hr />

                        <button type="submit" class="btn btn-success pull-right">
                            {{ trans('Navigation::navigation.add.buttons.submit') }}
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

@endsection