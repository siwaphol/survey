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
                {!! Form::model($setting, ['url'=> ('setting/'.$setting->id),'method'=>'put']) !!}
                <fieldset class="content-group">
                    <legend class="text-bold">ค่าตัวแปร</legend>

                    <div class="form-group {{$errors->has('name_en')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">ชื่อภาษาอังกฤษ</label>
                        <div class="col-lg-10">
                            <input type="text" name="name_en" value="{{$setting->name_en}}" class="form-control">
                            @if($errors->has('name_en'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('name_en')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('name_th')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">ชื่อภาษาไทย *</label>
                        <div class="col-lg-10">
                            <input type="text" name="name_th" value="{{$setting->name_th}}" class="form-control">
                            @if($errors->has('name_th'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('name_th')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('code')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">รหัส *</label>
                        <div class="col-lg-10">
                            <input type="text" name="code" value="{{$setting->code}}" class="form-control">
                            @if($errors->has('code'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('code')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('value')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">ค่า</label>
                        <div class="col-lg-10">
                            <input type="text" name="value" value="{{$setting->value}}" class="form-control">
                            @if($errors->has('value'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('value')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('unit_of_measure')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">หน่วย *</label>
                        <div class="col-lg-10">
                            <input type="text" name="unit_of_measure" value="{{$setting->unit_of_measure}}" class="form-control">
                            @if($errors->has('unit_of_measure'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('unit_of_measure')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('category')?"has-error has-feedback":""}}">
                        <label class="control-label col-lg-2">หมวด</label>
                        <div class="col-lg-10">
                            <input type="text" name="category" value="{{$setting->category}}" class="form-control">
                            @if($errors->has('category'))
                                <div class="form-control-feedback">
                                    <i class="icon-cancel-circle2"></i>
                                </div>
                                <span class="help-block">{{$errors->first('category')}}</span>
                            @endif
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label col-lg-2">กลุ่ม</label>--}}
                        {{--<div class="col-lg-10">--}}
                            {{--<input type="text" name="group_id" value="{{$setting->group_id}}" class="form-control">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="control-label col-lg-2">กลุ่ม</label>
                        <div class="col-lg-10">
                            <select name="group_id" class="form-control">
                                @foreach($settingGroups as $aGroup)
                                <option value="{{$aGroup->id}}">{{$aGroup->name_th}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
                {!! Form::close() !!}
                {{--<form class="form-horizontal" action="{{url('setting')}}/{{$setting->id}}" method="POST">--}}
                    {{--<fieldset class="content-group">--}}
                        {{--<legend class="text-bold">ค่าตัวแปร</legend>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">ชื่อภาษาอังกฤษ</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="name_en" value="{{$setting->name_en}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">ชื่อภาษาไทย</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="name_th" value="{{$setting->name_th}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">รหัส</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="code" value="{{$setting->code}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">ค่า</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="value" value="{{$setting->value}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">หน่วย</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="unit_of_measure" value="{{$setting->unit_of_measure}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">หมวด</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="category" value="{{$setting->category}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">กลุ่ม</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<input type="text" name="group_id" value="{{$setting->group_id}}" class="form-control">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--<div class="text-right">--}}
                        {{--<button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>--}}
                    {{--</div>--}}
                {{--</form>--}}
            </div>
        </div>
    </div>

@endsection