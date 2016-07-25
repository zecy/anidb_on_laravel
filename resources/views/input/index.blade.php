@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('content')

    <div id="animedata" class="container">

        {{-- BasicData --}}
        @include('input.basicdata')

        {{--<div v-if="basicData.id.value != 0">--}}

        <div>
            {{-- STAFF --}}
            @include('input.staff')

            {{-- CAST --}}
            @include('input.cast')

            {{-- ONAIR --}}
            @include('input.onair')
        </div>

    </div>

    @include('input.components')

@stop
