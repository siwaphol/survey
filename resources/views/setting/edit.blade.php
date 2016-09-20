@extends('layouts.afterlogin')

@section('script')

@endsection

@section('content')

    <div class="content">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">แก้ไขค่า</h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="{{url('setting')}}/{{$setting->id}}" method="PUT">
                    <fieldset class="content-group">
                        <legend class="text-bold">ค่าตัวแปร</legend>

                        <div class="form-group">
                            <label class="control-label col-lg-2">ชื่อภาษาอังกฤษ</label>
                            <div class="col-lg-10">
                                <input type="text" name="name_en" value="{{$setting->name_en}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">ชื่อภาษาไทย</label>
                            <div class="col-lg-10">
                                <input type="text" name="name_th" value="{{$setting->name_th}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">รหัส</label>
                            <div class="col-lg-10">
                                <input type="text" name="code" value="{{$setting->code}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">ค่า</label>
                            <div class="col-lg-10">
                                <input type="text" name="value" value="{{$setting->value}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">หน่วย</label>
                            <div class="col-lg-10">
                                <input type="text" name="unit_of_measure" value="{{$setting->unit_of_measure}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">หมวด</label>
                            <div class="col-lg-10">
                                <input type="text" name="category" value="{{$setting->category}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">กลุ่ม</label>
                            <div class="col-lg-10">
                                <input type="text" name="group_id" value="{{$setting->group_id}}" class="form-control">
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>
    </div>

@endsection