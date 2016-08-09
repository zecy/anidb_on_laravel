@extends('layout.master')

@section('page_title')
    数据录入
@stop

@section('content')

    <div id="animedata" class="container">

        <script>
            const anime_id = {{ $animeID }}
        </script>

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

    @include('input.components')

@stop
