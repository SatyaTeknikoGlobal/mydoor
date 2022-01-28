<?php 
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$url = url()->current();
// echo $url;

$baseurl = url('/');


$roleId = Auth::guard('admin')->user()->role_id; 

?>




<!-- Left Sidebar -->
<div class="left main-sidebar">

    <div class="sidebar-inner leftscroll">

        <div id="sidebar-menu">

            <ul>
                <li class="submenu">
                    <a class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME) echo "active"?>" href="<?php echo e(url('/admin')); ?>">
                        <i class="fas fa-bars" title="Dashboard"></i>
                        <span title="Dashboard"> Dashboard </span>
                    </a>
                </li>

                <?php 
                if(CustomHelper::isAllowedSection('roles' , $roleId , $type='show')){
                    if(CustomHelper::isAllowedSection('permission' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('roles')): ?>
                        <li class="submenu">
                            <a id="tables" href="#">
                                <i class="fas fa-envelope" title="Roles"></i>
                                <span title="Roles"> Roles </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <?php if(CustomHelper::isAllowedSection('roles' , $roleId , $type='show')): ?>
                                <li>
                                    <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.roles.index')); ?>" <?php if($url == $baseurl.'/admin/roles') echo "active"?> >Roles</a>
                                </li>
                                <?php endif; ?>
                                <?php if(CustomHelper::isAllowedSection('permission' , $roleId , $type='show')): ?>
                                <li>
                                    <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.permission.index')); ?>" <?php if($url == $baseurl.'/admin/permission') echo "active"?>>Permissions</a>
                                </li>
                                <?php endif; ?>

                            </ul>
                        </li>


                        <?php endif; ?>

                    <?php }}?>



                    <?php 
                    if(CustomHelper::isAllowedSection('admins' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('admins')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.admins.index')); ?>" class="<?php if($url == $baseurl.'/admin/admins') echo "active"?>">
                                <i class="fas fa-user"></i>
                                <span>All Admin</span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>












                    <?php 
                    if(CustomHelper::isAllowedSection('society' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('society')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.society.index')); ?>" class="<?php if($url == $baseurl.'/admin/society') echo "active"?>">
                                <i class="fas fa-home"></i>
                                <span>Society</span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>


                    <?php 
                    if(CustomHelper::isAllowedSection('blockes' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('blockes')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.blockes.index')); ?>" class="<?php if($url == $baseurl.'/admin/blockes') echo "active"?>">
                                <i class="fas fa-home"></i>
                                <span> Blockes </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>




                    <?php 
                    if(CustomHelper::isAllowedSection('flats' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('flats')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.flats.index')); ?>" class="<?php if($url == $baseurl.'/admin/flats') echo "active"?>">
                                <i class="fas fa-home"></i>
                                <span> Flats </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>





                    <?php 
                    if(CustomHelper::isAllowedSection('flatowners' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('flatowners')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.flatowners.index')); ?>" class="<?php if($url == $baseurl.'/admin/flatowners') echo "active"?>">
                                <i class="fas fa-user"></i>
                                <span> Flat Owners </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>




                    <?php 
                    if(CustomHelper::isAllowedSection('services' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('services')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.services.index')); ?>" class="<?php if($url == $baseurl.'/admin/services') echo "active"?>">
                                <i class="fas fa-wrench"></i>
                                <span> Services </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>


                    <?php 
                    if(CustomHelper::isAllowedSection('servicedetails' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('servicedetails')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.servicedetails.index')); ?>" class="<?php if($url == $baseurl.'/admin/servicedetails') echo "active"?>">
                                <i class="fas fa-wrench"></i>
                                <span> Service Details </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>



                      <?php 
                    if(CustomHelper::isAllowedSection('service_user' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('service_user')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.service_user.index')); ?>" class="<?php if($url == $baseurl.'/admin/service_user') echo "active"?>">
                                <i class="fas fa-user"></i>
                                <span> Service Users </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>




                      <?php 
                    if(CustomHelper::isAllowedSection('complaints' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('complaints')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.complaints.index')); ?>" class="<?php if($url == $baseurl.'/admin/complaints') echo "active"?>">
                                <i class="fas fa-comments"></i>
                                <span> Complaints </span>
                            </a>
                        </li>

                        <?php endif; ?>
                    <?php }?>


                    
                     
                    <?php 
                    if(CustomHelper::isAllowedSection('notifications' , $roleId , $type='show')){
                        ?>
                        <?php if(CustomHelper::isAllowedModule('notifications')): ?>
                        <li class="submenu">
                            <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.notifications.index')); ?>" class="<?php if($url == $baseurl.'/admin/notifications') echo "active"?>">
                               <i class="fa fa-bell" aria-hidden="true"></i>
                                <span> Notifications </span>
                            </a>
                        </li>

                         <?php endif; ?>

                          <?php }?>

                 <?php /*


                           @if(CustomHelper::isAllowedModule('galleries'))
                        <li class="submenu">
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.galleries.index') }}" class="<?php if($url == $baseurl.'/admin/galleries') echo "active"?>">
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
                                    <a href="{{ route($ADMIN_ROUTE_NAME.'.events.index') }}" <?php if($url == $baseurl.'/admin/events') echo "active"?> >Events List</a>
                                </li>
                                <li>
                                    <a href="{{ route($ADMIN_ROUTE_NAME.'.events.questions') }}" <?php if($url == $baseurl.'/admin/events/question') echo "active"?>>Event Questions</a>
                                </li>

                                 <li>
                                    <a href="{{ route($ADMIN_ROUTE_NAME.'.events.subscribed_user') }}" <?php if($url == $baseurl.'/admin/events/subscribed_user') echo "active"?>>Subscribed Users</a>
                                </li>
                            </ul>
                        </li>

                         @endif



                        @if(CustomHelper::isAllowedModule('banners'))
                        <li class="submenu">
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.banners.index') }}" class="<?php if($url == $baseurl.'/admin/banners') echo "active"?>">
                                <!-- <span class="label radius-circle bg-danger float-right">18</span> -->
                                <i class="fas fa-photo-video"></i>
                                <span> Banners </span>
                            </a>
                        </li>

                         @endif



                          @if(CustomHelper::isAllowedModule('users'))
                        <li class="submenu">
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.users.index') }}" class="<?php if($url == $baseurl.'/admin/users') echo "active"?>">
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
                                    <a href="{{ route($ADMIN_ROUTE_NAME.'.subscription.index') }}" <?php if($url == $baseurl.'/admin/subscription') echo "active"?> >Subscription Plan</a>
                                </li>
                               
                            </ul>
                        </li>

                         @endif



                          @if(CustomHelper::isAllowedModule('transactions'))
                        <li class="submenu">
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.transactions.index') }}" class="<?php if($url == $baseurl.'/admin/transactions') echo "active"?>">
                               <i class="fa fa-exchange" aria-hidden="true"></i>
                                <span> Transactions </span>
                            </a>
                        </li>

                         @endif


                          @if(CustomHelper::isAllowedModule('notifications'))
                        <li class="submenu">
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.notifications.index') }}" class="<?php if($url == $baseurl.'/admin/notifications') echo "active"?>">
                               <i class="fa fa-bell" aria-hidden="true"></i>
                                <span> Notifications </span>
                            </a>
                        </li>

                         @endif
                */?>

                <li class="submenu">
                    <a href="<?php echo e(url('admin/logout')); ?>" class="<?php if($url == $baseurl.'/admin/logout') echo "active"?>">
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
        <!-- End Sidebar --><?php /**PATH /home/appmantr/public_html/mydoor/resources/views/admin/common/sidebar.blade.php ENDPATH**/ ?>