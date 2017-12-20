<?php

//var_dump($this->session_memcached->userdata['info_user']['idNo_where']); die;


//$patterns = array ('/(19|20)(\d{2})\/(\d{1,2})\/(\d{1,2})/',
//    '/^\s*{(\w+)}\s*=/');
//$replace = array ('\4/\3/\1\2', '$\1 =');
$birthday=  $this->session_memcached->userdata['info_user']['birthday'];
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 9/23/14
 * Time: 2:53 PM
 * To change this template use File | Settings | File Templates.
 */
$arrs = array(
    '5' =>'Hà Nội',
    '6' => 'Hồ Chí Minh',
    '7' =>'Hải Phòng',
    '8' =>'Đà Nẵng',
    '9' =>'An Giang',
    '10' =>'Bà Rịa - Vũng Tàu',
    '11' =>'Bắc Cạn',
    '12' => 'Bắc Giang',
    '13' =>'Bạc Liêu',
    '14' =>'Bắc Ninh',
    '15' => 'Bến Tre',
    '16' =>'Bình Duong',
    '17' =>'Bình Phước',
    '18' =>'Bình Thuận',
    '19' =>'Bình Định',
    '20' =>'Buôn Mê Thuột',
    '21' =>'Cà Mau',
    '22' =>'Cần Thơ',
    '23' =>'Cao Bằng',
    '24' =>'Gia Lai',
    '25' =>'Hà Giang',
    '26' =>'Hà Nam',
    '27' =>'Hà Tĩnh',
    '28' =>'Hải Dương',
    '29' =>'Hậu Giang',
    '30' =>'Hòa Bình',
    '31' =>'Hưng Yên',
    '32' =>'Khánh Hòa',
    '33' =>'Kiên Giang',
    '34' =>'Kon Tum',
    '35' =>'Lai Châu',
    '36' =>'Lâm Đồng',
    '37' =>'Lạng Sơn',
    '38' =>'Lào Cai',
    '39' =>'Long An',
    '40' =>'Nam Định',
    '41' =>'Nghệ An',
    '42' =>'Ninh Bình',
    '43' =>'Ninh Thuận',
    '44' =>'Phú Thọ',
    '45' =>'Phú Yên',
    '46' => 'Quảng Bình',
    '47' =>'Quảng Nam',
    '48' =>'Quảng Ngãi',
    '49' =>'Quảng Ninh',
    '50' => 'Quảng Trị',
    '51' =>'Sóc Trăng',
    '52' =>'Sơn La',
    '53' =>'Tây Ninh',
    '54' =>'Thái Bình',
    '55' =>'Thái Nguyên',
    '56' =>'Thanh Hóa',
    '57' =>'Thừa Thiên Huế',
    '58' =>'Tiền Giang',
    '59' =>'Trà Vinh',
    '60' =>'Tuyên Quang',
    '61' =>'Vĩnh Long',
    '62' =>'Vĩnh Phúc',
    '63' =>'Yên Bái',
    '64' =>'Đà Lạt',
    '65' =>'Đắc Lắc',
    '66' =>'Đắc Nông',
    '67' =>'Đồng Nai',
    '68' =>'Đồng Tháp'
);
?>
<link  rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css" />
<link  rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="../js/bootstrap/bootstrap-datetimepicker.js"></script>
<script>
    $(function () {
        $("#id_date").datetimepicker({
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('#id_birthday').datetimepicker({
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0

        });
    });
</script>

<div class="col-md-10 col-sm-12 col-xs-12 container_right">
    <div class="col-md-12" id="txt_install_1">
        <span class="txt_intall"><strong>CHỈNH SỬA THÔNG TIN CÁ NHÂN</strong></span>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 au_container">
        <ul class="list-unstyled list-inline" style="padding-top: 20px">
            <li>
                <img src="../images/info/key.jpg">
            </li>
            <li>|</li>
            <li>
                Cập nhật tài khoản
            </li>
        </ul>
        <div>
         <?php   echo form_open('login/edit_info_user',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>

<!--            <form role="form" class="col-xs-6">-->
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword"> Họ tên:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="username" class="form-control" name="username"
                               value="<?php if ($this->session_memcached->userdata['info_user']['fullname'] == '') echo ''; else echo $this->session_memcached->userdata['info_user']['fullname'] ?>">
                        <span style="color: red" id="email_error"> <?php echo form_error('username'); ?></span>

                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Giới tính:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="radio" value="M" name="gen" <?php if($this->session_memcached->userdata['info_user']['gender']=='M') echo 'checked'?> />Nam
                        <input type="radio" value="F" name="gen" <?php if($this->session_memcached->userdata['info_user']['gender']=='F') echo 'checked' ?> />Nữ
                        <span style="color: red" id="email_error"> <?php echo form_error('gen'); ?></span>

                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Số CMT:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="idNo"
                            value="<?php if($this->session_memcached->userdata['info_user']['idNo']=='') echo ''; else echo $this->session_memcached->userdata['info_user']['idNo']; ?>">
                        <span style="color: red" id="email_error"> <?php echo form_error('idNo'); ?></span>

                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Ngày cấp:</label>
                    </div>
                    <div class="col-md-9">
                        <div class='input-group date' id="id_date" data-date-format="dd-mm-yyyy" >
                            <input type='text' name="idNo_dateIssue" readonly="readonly"
                                   class="form-control"
                                   value="<?php if(!isset($this->session_memcached->userdata['info_user']['idNo_dateIssue']) || ($this->session_memcached->userdata['info_user']['idNo_dateIssue']=='')) echo '';
                                            else echo date('d-m-Y',strtotime($this->session_memcached->userdata['info_user']['idNo_dateIssue'])); ?>"
                                   id="datetimepicker" data-date-format="dd-mm-yyyy"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>

                            <span style="color: red" id="email_error"> <?php echo form_error('idNo_dateIssue'); ?></span>

                        </div>
                        <!--                        <input type="date" class="form-control" name="idNo_dateIssue"-->
<!--                               value="--><?php //if($this->session_memcached->userdata['info_user']['idNo']=='') echo ''; else echo $this->session_memcached->userdata['info_user']['idNo_dateIssue']; ?><!--">-->
                    </div>

                </div>
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Nơi cấp:</label>
                    </div>
                    <div class="col-md-9">
                        <select class="form-control" name="idNo_address">
                              <option value="<?php if(!isset($this->session_memcached->userdata['info_user']['idNo_where']) || $this->session_memcached->userdata['info_user']['idNo_where']=='') echo '0'; else echo $this->session_memcached->userdata['info_user']['idNo_where']; ?>" selected>
                                  <?php $value=$this->session_memcached->userdata['info_user']['idNo_where'];
                                  if(in_array($value, array(0,1,2,3,4))) echo '--Chọn nơi cấp--'; else
                                  echo $arrs[$this->session_memcached->userdata['info_user']['idNo_where']]; ?>
                              </option>
                            <?php
                            if(isset($this->session_memcached->userdata['info_user']['idNo_where']))
                                unset($this->session_memcached->userdata['info_user']['idNo_where']);
                            foreach($arrs as $key=>$val){ ?>
                                <option value="<?php echo $key ?>"><?php echo $val ?></option>
                            <?php } ?>
                            </select>

                    </div>
                </div>

            <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Ngày Sinh:</label>
                    </div>
                <div class="col-md-9">
                    <div class='input-group date' id="id_birthday" data-date-format="dd-mm-yyyy" >
                        <input type='text' name="birthday" readonly="readonly"
                               class="form-control"
                               value="<?php if(empty($birthday)) echo ''; else echo date('d-m-Y',strtotime($birthday)); ?>"
                               id="datetimepicker" data-date-format="dd-mm-yyyy"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        <span style="color: red" id="email_error"> <?php echo form_error('id_birthday'); ?></span>

                    </div>
                    </div>





<!--                    <div class="col-md-9">-->

<!--                        <input type="text" class="form-control" name="birthday"  value="<?php if($birthday=='') echo ''; else echo $birthday; ?>" id="datetimepicker" >-->
<!--                    </div>-->
                </div>
<!--                <div class="form-group row ">-->
<!--                    <div class="col-md-3">-->
<!--                        <label for="InputPassword">Mật Khẩu:</label>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <input type="password" class="form-control" name="password">-->
<!--                        <span style="color: red" id="email_error"> --><?php //echo form_error('password'); ?><!--</span>-->
<!---->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group row ">
                    <div class="col-md-3">
                        <label for="InputPassword">Email:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="email" class="form-control" name="email"
                               value="<?php if($this->session_memcached->userdata['info_user']['email']=='') echo ''; else echo $this->session_memcached->userdata['info_user']['email']; ?>">
                        <span style="color: red" id="email_error"> <?php echo form_error('email'); ?></span>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="InputPassword">Địa chỉ:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="address"
                               value="<?php if($this->session_memcached->userdata['info_user']['address']=='') echo ''; else echo $this->session_memcached->userdata['info_user']['address']; ?>">
                        <span style="color: red" id="email_error"> <?php echo form_error('address'); ?></span>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="InputPassword">Điện thoại:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="tel" class="form-control" name="fone"
                               value="<?php if($this->session_memcached->userdata['info_user']['mobileNo']=='') echo ''; else echo $this->session_memcached->userdata['info_user']['mobileNo']; ?>">
                        <span style="color: red" id="email_error"> <?php echo form_error('fone'); ?></span>

                    </div>
                </div>
                <button type="submit" class="btn btn-default">Chỉnh sửa</button>
            </form>
        </div>
    </div>


</div>