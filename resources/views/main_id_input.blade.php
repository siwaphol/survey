<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แบบสอบถาม</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/core.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/colors.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/core/app')}}.js"></script>
    <script type="text/javascript" src="{{asset('assets/js/pages/login')}}.js"></script>
    <!-- /theme JS files -->

</head>

<body class="login-container">

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
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
            <!-- /content area -->

            <div class="content">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">10 ชุดที่แก้ไขล่าสุด</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <tbody>
                                    @foreach($latest10Main as $aMain)
                                        <tr>
                                            <td>{{$aMain->id}}</td>
                                            <td>{{$aMain->updated_at}}</td>
                                            <td>{{$aMain->name}}</td>
                                            <td>
                                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/main') }}">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="main_id" value="{{$aMain->id}}">
                                                    <input type="submit" class="btn btn-default" value="Go">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
