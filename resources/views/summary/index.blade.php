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
                            <th>ตาวน์โหลด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($main as $row)
                            {{--<?php--}}
                        {{--$subMain = \App\Menu::where('parent_id', $row->id)->get();--}}
                        {{--?>--}}
                        {{--@if(count($subMain) > 0)--}}
                            {{--@foreach($subMain as $subRow)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$row->name}}</td>--}}
                                    {{--<td>{{$subRow->name}}</td>--}}
                                    {{--<td>--}}
                                        {{--@if($row->id===5&&$subRow->id===6)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report911')}}">Download</a>--}}
                                        {{--@elseif($row->id===7&&$subRow->id===8)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report912')}}">Download</a>--}}
                                        {{--@elseif($row->id===11&&$subRow->id===12)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report913')}}">Download</a>--}}
                                        {{--@elseif($row->id===13&&$subRow->id===14)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report913')}}">Download</a>--}}
                                        {{--@elseif($row->id===13&&$subRow->id===14)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report913')}}">Download</a>--}}
                                        {{--@elseif($row->id===13&&$subRow->id===14)--}}
                                            {{--<a class="btn btn-success" href="{{url('get-report913')}}">Download</a>--}}
                                        {{--@else--}}
                                            {{-----}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                        {{--@else--}}
                            {{--<tr>--}}
                                {{--<td>{{$row->name}}</td>--}}
                                {{--<td>-</td>--}}
                                {{--<td>-</td>--}}
                            {{--</tr>--}}
                        {{--@endif--}}
                            <tr>
                                <td>{{$row->name}}</td>
                                <td>-</td>
                                <td>
                                    @if($row->id===5)
                                        <a class="btn btn-success" href="{{url('get-report911')}}">Download</a>
                                    @elseif($row->id===7)
                                        <a class="btn btn-success" href="{{url('get-report912')}}">Download</a>
                                    @elseif($row->id===11)
                                        <a class="btn btn-success" href="{{url('get-report913')}}">Download</a>
                                    @elseif($row->id===13)
                                        <a class="btn btn-success" href="{{url('get-report914')}}">Download</a>
                                    @elseif($row->id===16)
                                        <a class="btn btn-success" href="{{url('get-report915')}}">Download</a>
                                    @elseif($row->id===18)
                                        <a class="btn btn-success" href="{{url('get-report916')}}">Download</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
            </div>
        </div>
    </div>
@endsection