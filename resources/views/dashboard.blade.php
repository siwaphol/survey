@extends('layouts.afterlogin')

@section('script')
    <script src="{{asset('js/dashboard.js')}}"></script>
@endsection

@section('content')
    <div class="content">
        <h5 class="content-group text-semibold">
            จำนวนแบบสอบถามที่กรอกทั้งหมด {{$countAll->allCount}} ชุด
            <span><a href="{{url('main')}}" style="background-color: white;" type="button" class="btn border-slate text-slate-800 btn-flat"><i class="icon-stack position-left"></i> กรอกแบบสอบถาม <i class="icon-arrow-right5 position-right"></i> </a></span>
        </h5>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางที่ 1 จำนวนของแบบสอบถามที่ได้กรอกเสร็จสิ้นจำแนกตามภูมิภาคและเขตปกครอง</h5>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center" id="table1">
                <thead>
                <tr>
                    <th class="text-center text-bold">ภูมิภาค</th>
                    <th class="text-center text-bold">เขตปกครอง ในเขตเทศบาล</th>
                    <th class="text-center text-bold">เขตปกครอง นอกเขตเทศบาล</th>
                    <th class="text-center text-bold">รวม</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right"></th>
                    <th style="text-align:center"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($table1Arr as $row)
                    <tr>
                        <td>{{$row->column1}}</td>
                        <td>{{$row->column2}}</td>
                        <td>{{$row->column3}}</td>
                        <td>{{$row->column4}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางที่ 2 จำนวนของแบบสอบถามที่ได้กรอกเสร็จสิ้นจำแนกตามจังหวัดและเขตปกครอง</h5>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center" id="table2">
                <thead>
                <tr>
                    <th class="text-center text-bold">จังหวัด</th>
                    <th class="text-center text-bold">เขตปกครอง ในเขตเทศบาล</th>
                    <th class="text-center text-bold">เขตปกครอง นอกเขตเทศบาล</th>
                    <th class="text-center text-bold">รวม</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right"></th>
                    <th style="text-align:center"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($table2Arr as $row)
                    <tr>
                        <td>{{$row->column1}}</td>
                        <td>{{$row->column2}}</td>
                        <td>{{$row->column3}}</td>
                        <td>{{$row->column4}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางที่ 3 จำนวนของแบบสอบถามที่กรอกได้ในแต่ละวัน</h5>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center" id="table3">
                <thead>
                <tr>
                    <th class="text-center text-bold">วันที่</th>
                    <th class="text-center text-bold">จำนวน</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th style="text-align:right"></th>
                    <th style="text-align:center"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($table3Arr as $row)
                    <tr>
                        <td>{{$row->submitted_at}}</td>
                        <td>{{$row->count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางที่ 4 จำนวนของแบบสอบถามที่กรอกแยกตามผู้บันทึกข้อมูล</h5>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center" id="table4">
                <thead>
                <tr>
                    <th class="text-center text-bold">ชื่อผู้บันทึก</th>
                    <th class="text-center text-bold">จำนวน</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th style="text-align:right"></th>
                    <th style="text-align:center"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($table4Arr as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>

    </div>
@endsection