<div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('/')}}"><i class="icon-home"></i> Dashboard</a>
        <a class="navbar-brand" href="{{url('main')}}"><i class="icon-stack"></i> กรอกแบบสอบถาม</a>
        <a class="navbar-brand" href="{{url('report')}}"><i class="icon-stack"></i> Report</a>
        <a class="navbar-brand" href="{{url('filter')}}"><i class="icon-stack"></i> Filter</a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav navbar-right">

            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <span>{{Auth::user()->name}}</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="{{url('logout')}}"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->