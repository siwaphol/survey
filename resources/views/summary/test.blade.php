@extends('layouts.afterlogin')

@section('script')

@endsection

@section('content')
    <div class="content">
        <h5 class="content-group text-semibold">บทที่ 9 ข้อมูลการใช้พลังงาน</h5>
        <h5 class="content-group text-semibold">9.1.1 หมวดแสงสว่าง</h5>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">ตารางที่ 9.1 จำนวนและร้อยละของครัวเรือนจำแนกตามอุปกรณ์ในหมวดแสงสว่างและเขตปกครอง</h5>
            </div>
            <div class="panel body">
                <table class="table datatable-basic text-center">
                    <thead>
                        <tr>
                            <th colspan="3" rowspan="2">อุปกรณ์ในหมวดแสงสว่าง</th>
                            <th colspan="6">ภาคเหนือ</th>
                            <th colspan="6">กทม. และปริมณฑล</th>
                        </tr>
                        <tr>
                            <th colspan="2">ในเขตเทศบาล</th>
                            <th colspan="2">นอกเขตเทศบาล</th>
                            <th colspan="2">รวม</th>
                            <th colspan="2">ในเขตเทศบาล</th>
                            <th colspan="2">นอกเขตเทศบาล</th>
                            <th colspan="2">รวม</th>
                        </tr>
                        <tr>
                            <th>อุปกรณ์</th>
                            <th>ประเภท</th>
                            <th>ขนาด</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                            <th>จำนวน</th>
                            <th>ร้อยละ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="6" colspan="1">หลอดไฟ (ในบ้าน)</td>
                            <td rowspan="1" colspan="1">หลอดไส้</td>
                            <td colspan="1">-</td>
                            <td colspan="1">1</td>
                            <td colspan="1">2</td>
                            <td colspan="1">3</td>
                            <td colspan="1">4</td>
                        </tr>
                        <tr>
                            <td rowspan="3">หลอดฟลูออเรสเซนต์</td>
                            <td>ชนิดกลม</td>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td>ชนิดตรง ขนาดยาว</td>
                            <td>21</td>
                            <td>22</td>
                            <td>23</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>ชนิดตรง ขนาดสั้น</td>
                        </tr>
                        <tr>
                            <td>หลอดคอมแพค</td>
                        </tr>
                        <tr>
                            <td>หลอดแอลอีดี</td>
                        </tr>
                        <tr>
                            <td>หลอดไฟ (นอกบ้าน)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
            </div>
        </div>
    </div>
@endsection