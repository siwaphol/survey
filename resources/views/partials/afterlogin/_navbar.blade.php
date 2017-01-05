<div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('/')}}"><i class="icon-home"></i> Dashboard</a>
        <a class="navbar-brand" href="{{url('main')}}"><i class="icon-stack"></i> กรอกแบบสอบถาม</a>
        <a class="navbar-brand" href="{{url('report')}}"><i class="icon-stack"></i> Report</a>
        @if(in_array(auth()->user()->email, array('test@email.com','boy.kittikun@gmail.com','aiw_w@hotmail.com','pimphram.setaphram@gmail.com')))
        <a class="navbar-brand" href="{{url('filter')}}"><i class="icon-stack"></i> Filter</a>
        <a class="navbar-brand" href="{{url('setting')}}"><i class="icon-gear"></i> Setting</a>
        <a class="navbar-brand" href="{{url('upload')}}"><i class="icon-upload"></i> Upload</a>
        @endif

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