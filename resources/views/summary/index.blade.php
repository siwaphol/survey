@extends('layouts.afterlogin')

@section('script')
    <style>
        thead tr th{
            text-align: center;
        }
    </style>

    <script>
        $(function () {
            $(".select").select2()
            $("#tool-electric").change(function () {
                var toolID = $(this).val()
                var downloadURL = "{{url("report/tool_electric")}}"
                $("#tool-electric-download").attr("href", downloadURL + "/" + toolID)
            })
            $("#tool-fuel").change(function () {
                var toolID = $(this).val()
                var downloadURL = "{{url("report/tool_fuel")}}"
                $("#tool-fuel-download").attr("href", downloadURL + "/" + toolID)
            })
            $("#tool-renew").change(function () {
                var toolID = $(this).val()
                var downloadURL = "{{url("report/tool_renew")}}"
                $("#tool-renew-download").attr("href", downloadURL + "/" + toolID)
            })
        })
    </script>
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
                            @if((int)$row->id>=5 && (int)$row->id<=24)

                            @else
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
                            @endif
                            @if((int)$row->id===35)
                                <tr>
                                    <td>หมวด ข</td>
                                    <td>ไฟฟ้า</td>
                                    <td>
                                        {!! Form::select(null,\App\Summary::$electricMenu,null,['class'=>"select", 'id'=>'tool-electric']) !!}
                                        <a class="btn btn-success" href="{{url("report/tool_electric/1")}}" id="tool-electric-download">Download</a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>หมวด ข</td>
                                    <td>น้ำมันสำเร็จรูป</td>
                                    <td>
                                        {!! Form::select(null,\App\Summary::$fuelMenu,null,['class'=>"select", 'id'=>'tool-fuel']) !!}
                                        <a class="btn btn-success" href="{{url("report/tool_fuel/1")}}" id="tool-fuel-download">Download</a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>หมวด ข</td>
                                    <td>ดั้งเดิม</td>
                                    <td>
                                        {!! Form::select(null,\App\Summary::$renewMenu,null,['class'=>"select", 'id'=>'tool-renew']) !!}
                                        <a class="btn btn-success" href="{{url("report/tool_renew/1")}}" id="tool-renew-download">Download</a>
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
            </div>
        </div>
    </div>
@endsection

