@extends('layouts.app-back')
@section('title', trans('Navigation::navigation.index.head') )
@section('class_contener', 'col-sm-12 col-md-12 col-xs-12' )
@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-offset-0">

            @include('partial.form')

        </div>
    </div>

    <div class="row">

        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-offset-0">

            <div class="panel panel-default">
                <div class="panel-body">

                    <a href="{{ route('get.navigation.add') }}" class="btn btn-default pull-right">
                        <i class="fas fa-plus-circle"
                           data-placement="top"
                           data-toggle="tooltip"
                           title="{{ trans('Navigation::navigation.index.buttons.add') }}">
                        </i>
                    </a>

                </div>
            </div>

            {{--<!-- Nav tabs -->--}}
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($modelNavigationForRole as $count => $value)
                    <li role="presentation" class="{{ ($count === 0) ? "active" : "" }}">
                        <a href="#navigation-{{ $count }}" aria-controls="navigation-{{ $count }}" role="tab" data-toggle="tab">
                            {{ ucfirst($value['name']) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content panel wrapper">

                @forelse ($modelNavigationList as $item => $value)

                    <div role="tabpanel" class="tab-pane panel-pad fade {{ ($item === 0) ? "active in" : "" }}" id="navigation-{{ $item }}">

                        <table class="table table-striped">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>#</th>
                                    <th>#</th>
                                    <th>{{ trans('Navigation::navigation.index.label.lp') }}</th>
                                    <th>{{ trans('Navigation::navigation.index.label.title') }}</th>
                                    <th>{{ trans('Navigation::navigation.index.label.route_name') }}</th>
                                    <th>{{ trans('Navigation::navigation.index.label.route_link') }}</th>
                                    <th class="text-center col-md-0"></th>
                                    <th class="text-center col-md-1">{{ trans('Navigation::navigation.index.label.param') }}</th>
                                    <th class="text-center col-md-1">{{ trans('Navigation::navigation.index.label.special_param') }}</th>
                                    <th class="text-center">{{ trans('Navigation::navigation.index.label.is_active') }}</th>
                                    <th class="text-center">{{ trans('Navigation::navigation.index.label.destiny') }}</th>
                                    <th class="text-center">{{ trans('Navigation::navigation.index.label.role') }}</th>

                                    <th class="text-center">{{ trans('Navigation::navigation.index.label.is_trashed') }}</th>
                                    <th class="text-left col-lg-2">{{ trans('Navigation::navigation.index.label.date') }}</th>
                                    <th class="text-center"></th>
                                </tr>

                            </thead>

                            <tbody>

                                @include('Navigation::back.navigation.partial.tree', [
                                    'modelNavigationList' => $value['model'],
                                    'defaultRole' => $defaultRole,
                                    'looper' => 1
                                ])

                            </tbody>

                        </table>

                    </div>

                @empty

                    <tr>
                        <td colspan="15">{{ trans('Navigation::navigation.index.label.info_empty') }}</td>
                    </tr>

                @endforelse

            </div>

        </div>

    </div>

@endsection