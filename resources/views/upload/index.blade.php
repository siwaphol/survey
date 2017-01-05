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
                <h5 class="panel-title">อัพโหลดเพื่อแก้ไขค่า</h5>
            </div>
            <div class="panel body">
                <table class="table datatable-basic text-center">
                    <thead>
                    <tr>
                        <th>ชื่อหมวด</th>
                        <th>ชื่อหมวดย่อย</th>
                        <th>ข้อมูลดิบภาคเหนือ</th>
                        <th>ข้อมูลดิบกรุงเทพ และปริมณฑล</th>
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
                                    <a class="btn btn-success" href="#upload-norther">Download</a>
                                @endif
                            </td>
                            <td>
                                @if(count($subMenus)==0)
                                    <a class="btn btn-success" href="#upload-bangkok">Download</a>
                                @endif
                            </td>
                        </tr>
                        @foreach($subMenus as $sub_menu)
                            <tr>
                                <td></td>
                                <td>{{$sub_menu->name}}</td>
                                <td><a class="btn btn-success" href="#upload-sub-menu-northern">Download</a></td>
                                <td><a class="btn btn-success" href="#upload-sub-menu-bangkok">Download</a></td>
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