<div class="navbar navbar-inverse bg-teal navbar-component" style="position: relative; z-index: 30;">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mixed"><i class="icon-menu"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mixed">
        <ul class="nav navbar-nav">
            <li class="dropdown mega-menu mega-menu-wide">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    List <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-2">
                                <span class="menu-heading underlined">Form components</span>
                                <ul class="menu-list">
                                    <li><a href="form_components.html">General components</a></li>
                                    <li><a href="form_components_advanced.html">Advanced components</a></li>
                                    <li><a href="form_select2.html">Selects</a></li>
                                    <li><a href="form_dual_listboxes.html">Dual Listboxes</a></li>
                                    <li><a href="form_editable.html">Editable forms</a></li>
                                    <li><a href="form_validation.html">Validation</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <span class="menu-heading underlined">UI components</span>
                                <ul class="menu-list">
                                    <li><a href="components_modals.html">Modals</a></li>
                                    <li><a href="components_dropdowns.html">Dropdown menus <span class="badge badge-default">30+</span></a></li>
                                    <li><a href="components_popups.html">Tooltips and popovers</a></li>
                                    <li><a href="components_tabs.html">Tabs component</a></li>
                                    <li><a href="components_navs.html">Accordion and navs</a></li>
                                    <li><a href="components_notifications.html">Notifications <span class="badge badge-danger">3</span></a></li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <span class="menu-heading underlined">Sidebars</span>
                                <ul class="menu-list">
                                    <li><a href="sidebar_default_collapse.html">Default sidebar</a></li>
                                    <li><a href="sidebar_mini_collapse.html">Mini sidebar</a></li>
                                    <li><a href="sidebar_dual.html">Dual sidebar</a></li>
                                    <li><a href="sidebar_double_collapse.html">Double sidebar</a></li>
                                    <li><a href="sidebar_light.html">Light sidebar</a></li>
                                    <li><a href="sidebar_components.html">Sidebar components</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <span class="menu-heading underlined">Navigation</span>
                                <ul class="menu-list">
                                    <li><a href="navigation_horizontal_click.html">Submenu on click</a></li>
                                    <li><a href="navigation_horizontal_hover.html">Submenu on hover</a></li>
                                    <li><a href="navigation_horizontal_elements.html">With custom elements</a></li>
                                    <li><a href="navigation_horizontal_tabs.html">Tabbed navigation</a></li>
                                    <li><a href="navigation_horizontal_disabled.html">Disabled navigation links</a></li>
                                    <li class="active"><a href="navigation_horizontal_mega.html">Horizontal mega menu</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <span class="menu-heading underlined">Navbars</span>
                                <ul class="menu-list">
                                    <li><a href="navbar_single.html">Single navbar</a></li>
                                    <li><a href="navbar_multiple_navbar_navbar.html">Multiple navbars</a></li>
                                    <li><a href="navbar_colors.html">Color options</a></li>
                                    <li><a href="navbar_sizes.html">Sizing options <span class="badge badge-info">14</span></a></li>
                                    <li><a href="navbar_hideable.html">Hide on scroll</a></li>
                                    <li><a href="navbar_components.html">Navbar components</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <span class="menu-heading underlined">Extensions</span>
                                <ul class="menu-list">
                                    <li><a href="extension_velocity_basic.html">Velocity animations <span class="label label-primary">HOT</span></a></li>
                                    <li><a href="extension_blockui.html">Block UI</a></li>
                                    <li><a href="uploader_plupload.html">File uploaders</a></li>
                                    <li><a href="extension_image_cropper.html">Image cropper</a></li>
                                    <li><a href="internationalization_switch_direct.html">Translation <span class="label label-success">New</span></a></li>
                                    <li><a href="extra_fullcalendar_views.html">Calendars</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Tabs <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-content width-350">
                    <div class="dropdown-content-heading">
                        Tabs example
                        <ul class="icons-list">
                            <li><a href="#"><i class="icon-gear"></i></a></li>
                        </ul>
                    </div>

                    <ul class="nav nav-lg nav-tabs nav-justified no-margin-bottom">
                        <li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-compose position-left"></i> Form example</a></li>
                        <li class=""><a href="#tab2" data-toggle="tab"><i class="icon-list3 position-left"></i> List example</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane no-padding active" id="tab1">
                            <form class="dropdown-content-body" action="#">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="Your email">
                                    <div class="form-control-feedback text-muted">
                                        <i class="icon-mention"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <select data-placeholder="Subscription plan" class="select-full">
                                        <option></option>
                                        <option value="updates">Website updates</option>
                                        <option value="discounts">Discount offers</option>
                                        <option value="catalog">Catalog</option>
                                        <option value="prints">Prints</option>
                                        <option value="promo">Promotions</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <textarea class="form-control" cols="3" rows="3" placeholder="Your message"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="styled" checked="checked">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 text-right">
                                        <button type="submit" class="btn btn-danger btn-labeled btn-labeled-right"><b><i class="icon-paperplane"></i></b> Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane no-padding" id="tab2">
                            <ul class="media-list dropdown-content-body">
                                <li class="media">
                                    <a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                    <div class="media-body">
                                        <a href="#" class="media-heading text-semibold">James Alexander</a>
                                        <span class="text-size-mini text-muted display-block">Santa Ana, CA.</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-success"></span>
                                    </div>
                                </li>

                                <li class="media">
                                    <a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                    <div class="media-body">
                                        <a href="#" class="media-heading text-semibold">Jeremy Victorino</a>
                                        <span class="text-size-mini text-muted display-block">Dowagiac, MI.</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-danger"></span>
                                    </div>
                                </li>

                                <li class="media">
                                    <a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                    <div class="media-body">
                                        <a href="#" class="media-heading text-semibold">Margo Baker</a>
                                        <span class="text-size-mini text-muted display-block">Kasaan, AK.</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-success"></span>
                                    </div>
                                </li>

                                <li class="media">
                                    <a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                    <div class="media-body">
                                        <a href="#" class="media-heading text-semibold">Beatrix Diaz</a>
                                        <span class="text-size-mini text-muted display-block">Neenah, WI.</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-warning"></span>
                                    </div>
                                </li>

                                <li class="media">
                                    <a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                    <div class="media-body">
                                        <a href="#" class="media-heading text-semibold">Richard Vango</a>
                                        <span class="text-size-mini text-muted display-block">Grapevine, TX.</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-grey-400"></span>
                                    </div>
                                </li>
                            </ul>

                            <div class="dropdown-content-footer">
                                <a href="#" data-toggle="tooltip" title="All users"><i class="icon-three-bars display-block"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Classic <span class="caret"></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Second level</a></li>
                    <li class="dropdown-submenu">
                        <a href="#">Second level with child</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Third level</a></li>
                            <li class="dropdown-submenu">
                                <a href="#">Third level with child</a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Fourth level</a></li>
                                    <li><a href="#">Fourth level</a></li>
                                    <li><a href="#">Fourth level</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Third level</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Second level</a></li>
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="assets/images/placeholder.jpg" alt="">
                    <span>Victoria</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                    <li><a href="#"><i class="icon-coins"></i> My balance</a></li>
                    <li><a href="#"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-warning pull-right">94</span></a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                    <li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>