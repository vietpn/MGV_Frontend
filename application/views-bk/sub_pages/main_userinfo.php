<div class="row header_au">
    <div class="col-md-10 col-lg-10 col-xs-5 col-sm-5 ">
        <a href="<?php echo base_url() ?>"> <img src="../../images/info/logo.png" alt="logo Epay" class="img_log img-responsive"/></a>
    </div>
    <div class="col-md-2 col-xs-6 col-sm-6 hidden-lg hidden-md">
        <ul class="list-inline">
            <li><strong><span class="font_r_13 text-center">
                        <?php if (empty($this->session_memcached->userdata['info_user']['userID']))
                            echo ''; else
                            echo $this->session_memcached->userdata['info_user']['userID']; ?>
                    </span></strong>
            </li>
            <li>| <a href="../login/logout"> <span class="font_r_13 text-center">Thoát</span></a></li>
        </ul>

    </div>
                <div class=" col-xs-1 col-sm-1 hidden-lg hidden-md" style="padding: 0; margin: 0; padding-top: 2%">
                    <ul class="list-inline">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"> <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Thông tin tài khoản</a></li>
                            <li><a href="../login/resetbyemail">Reset mật khẩu</a></li>
                        </ul>
                    </ul>
                </div>
</div>
<div class="row contain_au">
    <div class="col-md-2  hidden-sm hidden-xs menu_left_au">
        <div class="col-md-12 col-xs-12 " id="l_name_au">
            <div class="col-md-7 col-xs-7 col-xs-sm">
                <strong><span class="font_r_13 text-center">
                        <?php if (empty($this->session_memcached->userdata['info_user']['userID']))
                            echo ''; else {
                            echo $this->session_memcached->userdata['info_user']['userID'];  ?></span></strong>
                <br>
                <a href="../login/logout"> <span class="font_r_13 text-center">Thoát </span></a>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 menu_info" id="l_name_au_2">
            <ul class="list-unstyled">
                <li style="background-color: #e8e8e8; height: 4%">
                </li>
                <?php if (strstr($this->uri->segment(2,0),'edit_info')) { ?>
                    <li  id="txt_userinfo" style="padding: 15px 0">
                        <a href="../login/edit_info"> <span style="color: #ffffff"> Thông tin tài khoản</span></a>
                    </li>

                    <li>
                        <a href="../login/resetbyemail"><span style="color: #1c4596">Reset mật khẩu</span></a>
                    </li>
                <?php } else { ?>
                    <li >
                        <a href="../login/edit_info">  <span style="color: #1c4596"> Thông tin tài khoản</span></a>
                    </li>
                    <li id="txt_userinfo">
                        <a href="../login/resetbyemail"><span style="color: #ffffff">Reset mật khẩu</span></a>
                    </li>
                <?php } ?>

            </ul>
        </div>

    </div>

    <!--                --><?php //$this->load->view('sub_pages/reset_password'); ?>
    <!--    --><?php //$this->load->view('sub_pages/infoUser'); ?>

<!--</div>-->
