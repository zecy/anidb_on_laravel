@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('content')

    <div id="animedata" class="container" xmlns="http://www.w3.org/1999/html">

        @include('input.basicdata')

        <div v-if="basicData.id.value != 0">

            {{-- STAFF BIGIN --}}

            @include('input.staff')

            {{-- STAFF END --}}

            <br>

            {{-- CAST BEGIN --}}
            @include('input.cast')
            {{-- CAST END --}}

            {{-- ONAIR BEGIN --}}
            @include('input.onair')
            {{-- ONAIR END --}}
        </div>

    </div>

    @include('input.components')

@stop
