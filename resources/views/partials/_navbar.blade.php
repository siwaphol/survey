<div class="navbar navbar-inverse bg-teal navbar-component" style="position: relative; z-index: 30;">
    <div class="navbar-header">
        <a class="navbar-brand">แบบสอบถามที่ {{$main_id}}</a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mixed"><i class="icon-menu"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mixed">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="{{url('html-loop-2')}}/1">หน้าแรก</a>
            </li>
            {{--ก--}}
            <li class="dropdown mega-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    หมวด ก <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                            <div class="col-md-12">
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/2">{{\App\Question::$sections[2]}}</a></li>
                                    <li><a href="{{url('html-loop-2')}}/3">{{\App\Question::$sections[3]}}</a></li>
                                    <li><a href="{{url('html-loop-2')}}/4">{{\App\Question::$sections[4]}}</a></li>
                                </ul>
                            </div>
                    </div>
                </div>
            </li>
            {{--ข--}}
            <li class="dropdown mega-menu mega-menu-wide">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    หมวด ข <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[5]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/5/0">ไฟฟ้า</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[6]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/6/0">ไฟฟ้า</a></li>
                                    <li><a href="{{url('html-loop-2')}}/6/1">น้ำมันสำเร็จรูป</a></li>
                                    <li><a href="{{url('html-loop-2')}}/6/2">พลังงานหมุนเวียนดั้งเดิม</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[7]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/7/0">ไฟฟ้า</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[8]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/8/0">ไฟฟ้า</a></li>
                                    <li><a href="{{url('html-loop-2')}}/8/1">น้ำมันสำเร็จรูป</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[9]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/9/2">พลังงานหมุนเวียนดั้งเดิม</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[10]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/10/0">ไฟฟ้า</a></li>
                                    <li><a href="{{url('html-loop-2')}}/10/2">พลังงานหมุนเวียนดั้งเดิม</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[11]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/11/1">น้ำมันสำเร็จรูป</a></li>
                                </ul>
                            </div>
                            <div class="col-md-1">
                                <span class="menu-heading underlined">{{\App\Question::$sections[12]}}</span>
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/12/1">น้ำมันสำเร็จรูป</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            {{--ค--}}
            <li class="dropdown mega-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    หมวด ค <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                            <div class="col-md-12">
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/13">{{\App\Question::$sections[13]}}</a></li>
                                    <li><a href="{{url('html-loop-2')}}/14">{{\App\Question::$sections[14]}}</a></li>
                                </ul>
                            </div>
                    </div>
                </div>
            </li>
            {{--ง--}}
            <li class="dropdown mega-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    หมวด ง <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="col-md-12">
                            <ul class="menu-list">
                                <li><a href="{{url('html-loop-2')}}/16">{{\App\Question::$sections[16]}}</a></li>
                                <li><a href="{{url('html-loop-2')}}/17">{{\App\Question::$sections[17]}}</a></li>
                                <li><a href="{{url('html-loop-2')}}/18">{{\App\Question::$sections[18]}}</a></li>
                                <li><a href="{{url('html-loop-2')}}/19">{{\App\Question::$sections[19]}}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            {{--จ--}}
            <li class="dropdown mega-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    หมวด จ <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                            <div class="col-md-12">
                                <ul class="menu-list">
                                    <li><a href="{{url('html-loop-2')}}/20">{{\App\Question::$sections[20]}}</a></li>
                                    <li><a href="{{url('html-loop-2')}}/21">{{\App\Question::$sections[21]}}</a></li>
                                    <li><a href="{{url('html-loop-2')}}/22">{{\App\Question::$sections[22]}}</a></li>
                                </ul>
                            </div>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ url('/main') }}">เปลี่ยนชุดแบบสอบถาม</a></li>
            <!-- Authentication Links -->
            <li><a href="{{ url('/logout') }}">Logout</a></li>
        </ul>
    </div>
</div>