@extends('layouts.afterlogin')

@section('script')

@endsection

@section('content')

    <div class="content">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">filter</h5>
            </div>
            <div class="panel-body">
                @foreach($menus as $menu)
                    <h3>{{$menu->name}}</h3>
                    @if($menu->submenu)
                        @foreach($menu->submenu as $sub)
                            <h4 style="margin: 20px;">{{$sub->name}}</h4>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection