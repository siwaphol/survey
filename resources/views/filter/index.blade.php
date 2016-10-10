@extends('layouts.afterlogin')

@section('script')
    <style>
        thead tr th{
            text-align: center;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">หมวดของรายงาน</h5>
            </div>
            <div class="panel body">
                <table class="table datatable-basic text-center">
                    <thead>
                    <tr>
                        <th>ชื่อหมวด</th>
                        <th>ชื่อหมวดย่อย</th>
                        <th>ดาวน์โหลดค่าที่ถูกกรอกในแต่ละชุด</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $menu)
                        <?php
                        $subMenus = \App\Menu::where('parent_id', $menu->id)->get();
                        ?>
                        <tr>
                            <td>{{$menu->name}}</td>
                            <td>-</td>
                            <td>
                                @if(count($subMenus)==0)
                                    <a class="btn btn-success" href="{{url('test-export-3')}}/{{$menu->id}}">Download</a>
                                @endif
                            </td>
                        </tr>
                        @foreach($subMenus as $sub_menu)
                            <tr>
                                <td></td>
                                <td>{{$sub_menu->name}}</td>
                                <td><a class="btn btn-success" href="{{url('test-export-3')}}/{{$menu->id}}/{{$sub_menu->id}}">Download</a></td>
                            </tr>
                        @endforeach

                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
            </div>
        </div>
    </div>
@endsection