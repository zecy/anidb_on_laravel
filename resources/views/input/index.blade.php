@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/input.css') }}">
@stop

@section('content')

    <div id="animedata" class="container">

        <script>
            const anime_id = {{ $animeID }}
        </script>

        {{-- 搜索组件 --}}
        <searchanime :is_complete="basicData.id.value"></searchanime>

        {{-- 信息情况 --}}
        <h2>信息情况</h2>
        <animedatastates
                :data_states.sync="dataStates"
                :abbr="basicData.abbr.value"
        ></animedatastates>

        {{-- BasicData --}}
        @include('input.basicdata')

        <div v-if="basicData.id.value != 0">

            {{-- STAFF --}}
            @include('input.staff')

            {{-- CAST --}}
            @include('input.cast')

            {{-- ONAIR --}}
            @include('input.onair')
        </div>

        <navbtn></navbtn>

    </div>

@stop

@section('vue_components')
    @include('input.components')
@stop

@section('js_controller')
    <script src="{{ asset('js/input-controller.js') }}"></script>
@stop
