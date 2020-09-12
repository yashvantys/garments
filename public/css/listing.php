<?php include('header.php'); ?>
<style>
#kt_header_menu_wrapper { display: none;}
</style>
   <!--begin::Container-->
   <div class="d-flex flex-row flex-column-fluid  container ">
      <!--begin::Content Wrapper-->
      <div class="main d-flex flex-column flex-row-fluid">
         <!--begin::Subheader-->
         <div class="subheader py-2 py-lg-6 " id="kt_subheader">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2">Link</a>
                </li>
            </ul>
            <?php /*
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel" aria-labelledby="kt_tab_pane_2">Tab content 1</div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel" aria-labelledby="kt_tab_pane_2">Tab content 2</div>
                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel" aria-labelledby="kt_tab_pane_3">Tab content 4</div>
                <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel" aria-labelledby="kt_tab_pane_4">Tab content 5</div>
            </div>
            */ ?>
         </div>
         <!--end::Subheader-->
         <div class="content flex-column-fluid d-lg-flex" id="kt_content" style="background-image: url('assets/media/images/login-bg.jpg')">
            <!-- Login start -->
               <div class="login-container align-items-center w-100">
                  <div class="d-table">
                     <div class="d-table-cell vertical-middle">
                        <form>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">
                                    <i class="icon-xl far fa-envelope"></i>
                                 </span>
                              </div>
                              <input type="text" class="form-control" name="url" placeholder="Email Address">
                           </div>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">
                                    <i class="flaticon-lock"></i>
                                 </span>
                              </div>
                              <input type="password" class="form-control" name="url" placeholder="Password">
                           </div>
                           <div class="forget-password text-right">
                              <a href="#">Forget Password?</a>
                           </div>
                           <input type="submit" value="LOGIN">
                        </form>
                     </div>
                  </div>
               </div>
            <!-- Login END -->
         </div>
         <!--end::Content-->
      </div>
      <!--begin::Content Wrapper-->
   </div>
   <!--end::Container-->
<?php include('footer.php'); ?>
