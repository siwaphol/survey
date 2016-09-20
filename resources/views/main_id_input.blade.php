@extends('layouts.afterlogin')

@section('script')
    <script type="text/javascript" src="{{asset('js/main/main_custom.js')}}"></script>
@endsection

@section('content')

    <div class="content pb-20">

        <!-- Advanced login -->
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/main') }}">
            {{csrf_field()}}
            <div class="panel panel-body login-form">
                <div class="text-center">
                    <h5 class="content-group-lg">ระบุหมายเลขแบบสอบถาม</h5>
                </div>

                <div class="form-group has-feedback has-feedback-left{{ $errors->has('main_id') ? ' has-error' : '' }}">
                    <input type="number" class="form-control" name="main_id" placeholder="หมายเลขแบบสอบถาม" value="{{old('main_id')}}" min="1">
                    <div class="form-control-feedback">
                        <i class="icon-stack text-muted"></i>
                    </div>
                    @if ($errors->has('main_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('main_id') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn bg-pink-400 btn-block">ตกลง <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </form>
        <!-- /advanced login -->

    </div>

    <div class="content">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ชุดที่แก้ไขล่าสุด</h5>
            </div>
            <div class="panel-body">
                <form action="{{url('filter-main')}}" method="post" class="form-horizontal">
                    {{csrf_field()}}
                    <fieldset class="content-group">
                        <legend class="text-bold">Filter</legend>

                        <div class="form-group">
                            <label for="" class="control-label col-lg-2">เขตเทศบาล</label>
                            <div class="col-lg-2">
                                <select name="no_ra11" id="" class="select">
                                    <option value="all">ทั้งหมด</option>
                                    <option value="inborder" {{isset($no_ra11)&&$no_ra11==='inborder'?'selected="selected"':""}}>ในเขต</option>
                                    <option value="outborder" {{isset($no_ra11)&&$no_ra11==='outborder'?'selected="selected"':""}}>นอกเขต</option>
                                </select>
                            </div>

                            <label for="" class="control-label col-lg-1">ภูมิภาค</label>
                            <div class="col-lg-2">
                                <select name="no_ra14" id="" class="select">
                                    <option value="all">ทั้งหมด</option>
                                    <option value="northern" {{isset($no_ra14)&&$no_ra14==='northern'?'selected="selected"':""}}>ภาคเหนือ</option>
                                    <option value="bangkok" {{isset($no_ra14)&&$no_ra14==='bangkok'?'selected="selected"':""}}>กรุงเทพฯ และปริมณฑล</option>
                                </select>
                            </div>

                            <label for="" class="control-label col-lg-1">จังหวัด</label>
                            <div class="col-lg-2">
                                <select name="no_ra14_o7_ra2003" id="" class="select">
                                    <option value="all">ทั้งหมด</option>
                                    <option value="chiangmai" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='chiangmai'?'selected="selected"':""}}>เชียงใหม่</option>
                                    <option value="nan" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='nan'?'selected="selected"':""}}>น่าน</option>
                                    <option value="utaradit" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='utaradit'?'selected="selected"':""}}>อุตรดิตถ์</option>
                                    <option value="pitsanurok" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='pitsanurok'?'selected="selected"':""}}>พิษณุโลก</option>
                                    <option value="petchabul" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='petchabul'?'selected="selected"':""}}>เพชรบูรณ์</option>
                                    <option value="bangkok" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='bangkok'?'selected="selected"':""}}>กรุงเทพ</option>
                                    <option value="patumtani" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='patumtani'?'selected="selected"':""}}>ปทุมธานี</option>
                                    <option value="nontaburi" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='nontaburi'?'selected="selected"':""}}>นนทบุรี</option>
                                    <option value="samutprakarn" {{isset($no_ra14_o7_ra2003)&&$no_ra14_o7_ra2003==='samutprakarn'?'selected="selected"':""}}>สมุทรปราการ</option>
                                </select>
                            </div>

                            <div class="col-lg-1">
                                <input type="submit" class="btn border-slate text-slate-800 btn-flat" value="Filter">
                            </div>
                            <div class="col-lg-1">
                                <a class="btn border-slate text-slate-800 btn-flat" href="{{url('main')}}">Clear</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            {{--<div class="table-responsive">--}}
            <table class="table datatable-basic text-center">
                <thead>
                <tr>
                    <th class="text-center text-bold">หมายเลขชุด</th>
                    <th class="text-center text-bold">แก้ไขครั้งล่าสุด</th>
                    <th class="text-center text-bold">ชื่อผู้แก้ไข</th>
                    <th class="text-center text-bold"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($mainList as $aMain)
                    <tr>
                        <td>{{$aMain->main_id}}</td>
                        <td>{{is_null($aMain->submitted_at)?$aMain->updated_at:$aMain->submitted_at}}</td>
                        <td>{{$aMain->name}}</td>
                        <td>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/main') }}">
                                {{csrf_field()}}
                                <input type="hidden" name="main_id" value="{{$aMain->main_id}}">
                                <input type="submit" class="btn btn-default" value="Go">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--</div>--}}
        </div>
    </div>

@endsection