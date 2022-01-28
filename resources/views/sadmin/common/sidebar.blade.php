<?php 
$SADMIN_ROUTE_NAME = CustomHelper::getSadminRouteName();

$url = url()->current();
// echo $url;

$baseurl = url('/');
?>




<!-- Left Sidebar -->
<div class="left main-sidebar">

    <div class="sidebar-inner leftscroll">

        <div id="sidebar-menu">

            <ul>
                <li class="submenu">
                    <a class="<?php if($url == $baseurl.'/'.$SADMIN_ROUTE_NAME) echo "active"?>" href="{{url('/sadmin')}}">
                        <i class="fas fa-bars"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @if(CustomHelper::isAllowedSocietyModule('blockes'))
                <li class="submenu">
                    <a href="{{ route($SADMIN_ROUTE_NAME.'.blockes.index') }}" class="<?php if($url == $baseurl.'/sadmin/blockes') echo "active"?>">
                        <i class="fas fa-user"></i>
                        <span> Blockes </span>
                    </a>
                </li>

                @endif


                 @if(CustomHelper::isAllowedSocietyModule('flats'))
                <li class="submenu">
                    <a href="{{ route($SADMIN_ROUTE_NAME.'.flats.index') }}" class="<?php if($url == $baseurl.'/sadmin/flats') echo "active"?>">
                        <i class="fas fa-user"></i>
                        <span>Flats</span>
                    </a>
                </li>

                @endif



                <?php /*
                           @if(CustomHelper::isAllowedModule('galleries'))
                        <li class="submenu">
                            <a href="{{ route($SADMIN_ROUTE_NAME.'.galleries.index') }}" class="<?php if($url == $baseurl.'/admin/galleries') echo "active"?>">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>

                                <span> Gallery </span>
                            </a>
                        </li>
                         @endif

                         @if(CustomHelper::isAllowedModule('events'))

                        <li class="submenu">
                            <a id="tables" href="#">
                                <i class="fas fa-envelope"></i>
                                <span> Events </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route($SADMIN_ROUTE_NAME.'.events.index') }}" <?php if($url == $baseurl.'/admin/events') echo "active"?> >Events List</a>
                                </li>
                                <li>
                                    <a href="{{ route($SADMIN_ROUTE_NAME.'.events.questions') }}" <?php if($url == $baseurl.'/admin/events/question') echo "active"?>>Event Questions</a>
                                </li>

                                 <li>
                                    <a href="{{ route($SADMIN_ROUTE_NAME.'.events.subscribed_user') }}" <?php if($url == $baseurl.'/admin/events/subscribed_user') echo "active"?>>Subscribed Users</a>
                                </li>
                            </ul>
                        </li>

                         @endif



                        @if(CustomHelper::isAllowedModule('banners'))
                        <li class="submenu">
                            <a href="{{ route($SADMIN_ROUTE_NAME.'.banners.index') }}" class="<?php if($url == $baseurl.'/admin/banners') echo "active"?>">
                                <!-- <span class="label radius-circle bg-danger float-right">18</span> -->
                                <i class="fas fa-photo-video"></i>
                                <span> Banners </span>
                            </a>
                        </li>

                         @endif



                          @if(CustomHelper::isAllowedModule('users'))
                        <li class="submenu">
                            <a href="{{ route($SADMIN_ROUTE_NAME.'.users.index') }}" class="<?php if($url == $baseurl.'/admin/users') echo "active"?>">
                                <i class="fas fa-user"></i>
                                <span> Users </span>
                            </a>
                        </li>

                         @endif




                          @if(CustomHelper::isAllowedModule('subscription'))

                        <li class="submenu">
                            <a id="tables" href="#">
                               <i class="fa fa-shopping-cart" aria-hidden="true"></i>

                                <span> Subscription </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route($SADMIN_ROUTE_NAME.'.subscription.index') }}" <?php if($url == $baseurl.'/admin/subscription') echo "active"?> >Subscription Plan</a>
                                </li>
                               
                            </ul>
                        </li>

                         @endif



                          @if(CustomHelper::isAllowedModule('transactions'))
                        <li class="submenu">
                            <a href="{{ route($SADMIN_ROUTE_NAME.'.transactions.index') }}" class="<?php if($url == $baseurl.'/admin/transactions') echo "active"?>">
                               <i class="fa fa-exchange" aria-hidden="true"></i>
                                <span> Transactions </span>
                            </a>
                        </li>

                         @endif


                          @if(CustomHelper::isAllowedModule('notifications'))
                        <li class="submenu">
                            <a href="{{ route($SADMIN_ROUTE_NAME.'.notifications.index') }}" class="<?php if($url == $baseurl.'/admin/notifications') echo "active"?>">
                               <i class="fa fa-bell" aria-hidden="true"></i>
                                <span> Notifications </span>
                            </a>
                        </li>

                         @endif

           */ ?>
           <li class="submenu">
            <a href="{{url('sadmin/logout')}}" class="<?php if($url == $baseurl.'/sadmin/logout') echo "active"?>">
              <i class="fa fa-sign-out" aria-hidden="true"></i>
              <span>Logout</span>
          </a>
      </li>

                        <!-- <li class="submenu">
                            <a href="slider.html">
                                <i class="fas fa-photo-video"></i>
                                <span> Slider </span>
                            </a>
                        </li>

                        <li class="submenu">
                            <a href="charts.html">
                                <i class="fas fa-chart-line"></i>
                                <span> Charts </span>
                            </a>
                        </li>

                        <li class="submenu">
                            <a id="tables" href="#">
                                <i class="fas fa-table"></i>
                                <span> Tables </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="tables-basic.html">Basic Tables</a>
                                </li>
                                <li>
                                    <a href="tables-datatable.html">Data Tables</a>
                                </li>
                            </ul>
                        </li> -->

                       <!--  <li class="submenu">
                            <a href="#">
                                <i class="fas fa-laptop"></i>
                                <span> User Interface </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="ui-alerts.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="ui-buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="ui-cards.html">Cards</a>
                                </li>
                                <li>
                                    <a href="ui-carousel.html">Carousel</a>
                                </li>
                                <li>
                                    <a href="ui-collapse.html">Collapse</a>
                                </li>
                                <li>
                                    <a href="ui-icons.html">Icons</a>
                                </li>
                                <li>
                                    <a href="ui-modals.html">Modals</a>
                                </li>
                                <li>
                                    <a href="ui-tooltips.html">Tooltips and Popovers</a>
                                </li>
                            </ul>
                        </li> -->

                       <!--  <li class="submenu">
                            <a href="#">
                                <i class="fab fa-wpforms"></i>
                                <span> Forms </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="forms-general.html">General Elements</a>
                                </li>
                                <li>
                                    <a href="forms-select2.html">Select2</a>
                                </li>
                                <li>
                                    <a href="forms-validation.html">Form Validation</a>
                                </li>
                                <li>
                                    <a href="forms-text-editor.html">Text Editors</a>
                                </li>
                                <li>
                                    <a href="forms-upload.html">Multiple File Upload</a>
                                </li>
                                <li>
                                    <a href="forms-datetime-picker.html">Date and Time Picker</a>
                                </li>
                                <li>
                                    <a href="forms-color-picker.html">Color Picker</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-file-image"></i>
                                <span> Multimedia </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="media-fancybox.html">
                                        <span class="label radius-circle bg-danger float-right">cool</span> Fancybox </a>
                                </li>
                                <li>
                                    <a href="media-masonry.html">Masonry</a>
                                </li>
                                <li>
                                    <a href="media-lightbox.html">Lightbox</a>
                                </li>
                                <li>
                                    <a href="media-owl-carousel.html">Owl Carousel</a>
                                </li>
                                <li>
                                    <a href="media-image-magnifier.html">Image Magnifier</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-star"></i>
                                <span> Plugins </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="star-rating.html">Star Rating</a>
                                </li>
                                <li>
                                    <a href="range-sliders.html">Range Sliders</a>
                                </li>
                                <li>
                                    <a href="tree-view.html">Tree View</a>
                                </li>
                                <li>
                                    <a href="sweetalert.html">SweetAlert</a>
                                </li>
                                <li>
                                    <a href="calendar.html">Calendar</a>
                                </li>
                                <li>
                                    <a href="counter-up.html">Counter-Up</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <i class="far fa-copy"></i>
                                <span> Example Pages </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="page-pricing-tables.html">Pricing Tables</a>
                                </li>
                                <li>
                                    <a href="page-timeline.html">Timeline</a>
                                </li>
                                <li>
                                    <a href="page-invoice.html">Invoice</a>
                                </li>
                                <li>
                                    <a href="page-blank.html">Blank Page</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <span class="label radius-circle bg-primary float-right">9</span>
                                <i class="fas fa-indent"></i>
                                <span> Menu Levels </span>
                            </a>
                            <ul>
                                <li>
                                    <a href="#">
                                        <span>Second Level</span>
                                    </a>
                                </li>
                                <li class="submenu">
                                    <a href="#">
                                        <span>Third Level</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <span>Third Level Item</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span>Third Level Item</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            <li class="submenu">
                                <a class="pro" href="pro.html">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span> PRO Version </span>
                                </a>
                            </li>

                            <li class="submenu">
                                <a target="_blank" href="https://nura24.com/">
                                    <i class="fas fa-th"></i>
                                    <span> Nura24 Free Suite </span>
                                </a>
                            </li>

                        </li> -->

                    </ul>

                    <div class="clearfix"></div>

                </div>

                <div class="clearfix"></div>

            </div>

        </div>
        <!-- End Sidebar -->