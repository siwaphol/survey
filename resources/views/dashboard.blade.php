@extends('layouts.afterlogin')

@section('script')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script>
        var mainPageURL = '{{url('main')}}';
        var baseURL = '{{url('/')}}';
    </script>
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
                <h5 class="panel-title">กราฟจำนวนของแบบสอบถามที่ได้กรอกเสร็จสิ้นจำแนกตามภูมิภาคและเขตปกครอง</h5>
            </div>
            <div class="panel body">
                <div class="chart-container">
                    <div class="chart" id="chart1-column-stacked"></div>
                </div>
            </div>
            <div class="panel-footer">
                <h5 style="float: right; margin-right: 5px;">รวม {{$sumTable1}} ชุด</h5>
            </div>
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">กราฟแสดงจำนวนของแบบสอบถามที่ได้กรอกเสร็จสิ้นจำแนกตามจังหวัดและเขตปกครอง</h5>
            </div>
            <div class="panel body">
                <div class="chart-container">
                    <div class="chart" id="chart2-column-stacked"></div>
                </div>
            </div>
            <div class="panel-footer">
                <h5 style="float: right; margin-right: 5px;">รวม {{$sumTable2}} ชุด</h5>
            </div>
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">กราฟแสดงจำนวนของแบบสอบถามที่กรอกได้ในแต่ละวัน</h5>
            </div>
            <div class="panel body">
                <div class="chart-container">
                    <div class="chart" id="chart3-line-chart"></div>
                </div>
            </div>
            <div class="panel-footer">
                <h5 style="float: right; margin-right: 5px;">รวม {{$sumTable3}} ชุด</h5>
            </div>
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางแสดงจำนวนของแบบสอบถามที่กรอกแยกตามผู้บันทึกข้อมูล</h5>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center" id="table4">
                <thead>
                <tr>
                    <th class="text-center text-bold">ชื่อผู้บันทึก</th>
                    <th class="text-center text-bold">จำนวน</th>
                    <th class="text-center text-bold"></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th style="text-align:right"></th>
                    <th style="text-align:center"></th>
                    <th style="text-align:center"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($table4Arr as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->count}}</td>
                        <td><button class="btn btn-default person-list-btn" data-name="{{$row->name}}">ดูรายละเอียด</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>

    </div>
@endsection