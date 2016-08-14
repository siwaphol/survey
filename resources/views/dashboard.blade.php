@extends('layouts.app2')

@section('big-content')
    <h5 class="content-group text-semibold">
        จำนวนแบบสอบถามที่กรอกทั้งหมด  ชุด
    </h5>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">ตารางที่ 1 จำนวนของแบบสอบถามที่ได้กรอกเสร็จสิ้นจำแนกตามภูมิภาคและเขตปกครอง</h5>
        </div>
        {{--<div class="table-responsive">--}}
        <table class="table datatable-basic text-center">
            <thead>
            <tr>
                <th class="text-center text-bold">ภูมิภาค</th>
                <th class="text-center text-bold">เขตปกครอง ในเขตเทศบาล</th>
                <th class="text-center text-bold">เขตปกครอง นอกเขตเทศบาล</th>
                <th class="text-center text-bold">รวม</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mainList as $aMain)
                <tr>
                    <td>{{$aMain->main_id}}</td>
                    <td>{{$aMain->updated_at}}</td>
                    <td>{{$aMain->name}}</td>
                    <td>{{$aMain->asfd}}</td>
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
        <table class="table datatable-basic text-center">
            <thead>
            <tr>
                <th class="text-center text-bold">จังหวัด</th>
                <th class="text-center text-bold">เขตปกครอง ในเขตเทศบาล</th>
                <th class="text-center text-bold">เขตปกครอง นอกเขตเทศบาล</th>
                <th class="text-center text-bold">รวม</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mainList as $aMain)
                <tr>
                    <td>{{$aMain->main_id}}</td>
                    <td>{{$aMain->updated_at}}</td>
                    <td>{{$aMain->name}}</td>
                    <td>{{$aMain->asfd}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--</div>--}}
    </div>
@endsection