@extends('layouts.afterlogin')

@section('script')
    <script type="text/javascript" src="{{asset('js/main/setting.js')}}"></script>
@endsection

@section('content')

    <div class="content">

        @include('flash::message')

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ค่าตัวแปรต่างๆ</h5>
            </div>
            <table class="table datatable-basic text-center">
                <thead>
                <tr>
                    <th class="text-center text-bold">หมวด</th>
                    <th class="text-bold">ชื่อภาษาอังกฤษ</th>
                    <th class="text-bold">ชื่อภาษาไทย</th>
                    <th class="text-bold">รหัส</th>
                    <th class="text-center text-bold">ค่า</th>
                    <th class="text-center text-bold">หน่วย</th>
                    <th class="text-center text-bold">กลุ่ม</th>
                    <th class="text-center text-bold">แก้ไข</th>
                </tr>
                </thead>
                <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{$setting->category}}</td>
                        <td class="text-left">{{$setting->name_en}}</td>
                        <td class="text-left">{{$setting->name_th}}</td>
                        <td>{{$setting->code}}</td>
                        <td>{{$setting->value}}</td>
                        <td>{{$setting->unit_of_measure}}</td>
                        <td data-group-id="{{$setting->group_id}}">{{$setting->group_name_th}}</td>
                        <td>
                            <a href="{{url('setting')}}/{{$setting->id}}" class="btn btn-default">แก้ไข</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection