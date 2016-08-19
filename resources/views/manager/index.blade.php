@extends('layout.master')

@section('page_title')
    动画管理
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">
@stop

@section('content')

    <div id="anime-manager">
        <router-view></router-view>
    </div>

@stop

@section('vue_components')
    @include('manager.components')
@stop

@section('js_controller')
    <script src="{{ asset('js/manager-controller.js') }}"></script>
@stop
