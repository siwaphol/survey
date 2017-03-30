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
                            <th>คำนวณใหม่</th>
                            <th>ดาวน์โหลดไฟล์ที่มีอยู่</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($main as $row)
                            <?php
                            $subMenus = \App\Menu::where('parent_id', $row->id)->get();
                            ?>
                            <tr>
                                <td>{{$row->name}}</td>
                                <td>-</td>
                                <td>
                                    @if(count($subMenus)==0)
                                        <?php
                                        $menuLink = \App\Http\Controllers\MenuController::getReportDownloadLink($row->id);
                                        ?>
                                        @if(!is_null($menuLink))
                                            <a class="btn btn-success" href="{{url($menuLink)}}">Download</a>
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($row->id, array(2,3,4,34,35,5,7,11,13,16,18,21,23,25,26,27,28,29,30,31,32,33)))
                                        <a class="btn btn-success" href="{{url('download')}}/{{$row->id}}">Download</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @foreach($subMenus as $sub_menu)
                                <?php
                                $menuLink = \App\Http\Controllers\MenuController::getReportDownloadLink($sub_menu->parent_id, $sub_menu->id);
                                ?>
                                <tr>
                                    <td></td>
                                    <td>{{$sub_menu->name}}</td>
                                    <td>
                                        @if(!is_null($menuLink))
                                            <a class="btn btn-success" href="{{url($menuLink)}}">Download</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($sub_menu->parent_id, array(2,3,4,34,35,5,7,11,13,16,18,21,23,25,26,27,28,29,30,31,32,33)))
                                            <a class="btn btn-success" href="{{url('download')}}/{{$sub_menu->id}}">Download</a>
                                        @else
                                            -
                                        @endif
                                    </td>
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