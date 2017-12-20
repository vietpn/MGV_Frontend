
<div class="col-md-12 ">

<?php $baseurl = $this->config->item('base_url');
$urlSecur = $baseurl . '../register/security_code'; ?>
<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap-datetimepicker.css" />
<script type="text/javascript" src="../js/bootstrap/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $baseurl . 'js/register/register.js' ?>"></script>
<script type="text/javascript">
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
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/11/14
 * Time: 11:36 AM
 * To change this template use File | Settings | File Templates.
 */
echo form_open('register/dangky', array('method' => 'post', 'name' => 'register', 'id' => 'register', 'class' => 'form-horizontal')); ?>
<!--<div class="row ">-->
    <!--        <label style="color: red">--><?php //echo validation_errors(); ?><!--</label>-->
<div class="container" style="margin-top:20px; background: #ffffff; border-radius: 10px 10px 0 0 ">
<div class="col-md-6 col-sm-12 col-xs-12">
        <div><h3>THÔNG TIN TÀI KHOẢN</h3></div>
        <div class="form-group">
            <div>
                <label>Tên đăng nhập: <span style="color: red">*</span></label>
            </div>
            <div class="col-md-10">
                <input type="text" name="username" id="username" class="form-control"/>
                <span style="color: red" id="username_error"> <?php echo form_error('username'); ?></span>

            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Mật khẩu: <span style="color: red">*</span></label>
            </div>
            <div class="col-md-10">
                <input type="password" name="password" class="form-control"/>
                <span style="color: red" id="username_error"> <?php echo form_error('password'); ?></span>
            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Nhập lại mật khẩu: <span style="color: red">*</span></label>
            </div>
            <div class="col-md-10">
                <input type="password" name="re_password" class="form-control"/>
                <span style="color: red" id="username_error"> <?php echo form_error('re_password'); ?></span>

            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Email: <span style="color: red">*</span></label></div>
            <div class="col-md-10">
                <input type="email" name="email" id="email" class="form-control"/>
                <span style="color: red" id="email_error"> <?php echo form_error('email'); ?></span>

            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Điện thoại: <span style="color: red">*</span></label></div>
            <div class="col-md-10">
                <input type="tel" name="fone" id="fone" class="form-control"/>
                <span style="color: red" id="email_error"> <?php echo form_error('fone'); ?></span>

            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Địa chỉ:</label></div>
            <div class="col-md-10">
                <input type="text" name="address" class="form-control"/>
                <span style="color: red" id="email_error"> <?php echo form_error('address'); ?></span>

            </div>
        </div>

    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div><h3>THÔNG TIN CÁ NHÂN</h3></div>
        <div class="form-group">
            <div>
                <label>Số CMT <span style="color: red">*</span></label></div>
            <div class="col-md-10">
                <input type="text" name="idNo" class="form-control"/>
                <span style="color: red" id="email_error"> <?php echo form_error('idNo'); ?></span>

            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Ngày cấp: <span style="color: red">*</span></label></div>
            <div class="col-md-10">
                <div class="input-group date" id="id_date" data-date-format="dd-mm-yyyy" >
                    <input type="text" name="idIssueDate" readonly="readonly"  class="form-control" id="datetimepicker" data-date-format="dd-mm-yyyy"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                   <span style="color: red" id="email_error"> <?php echo form_error('idIssueDate'); ?></span>

                </div>
            </div>
        </div>


        <div class="form-group">
            <div>
                <label>Nơi cấp:</label></div>
            <div class="col-md-10">
                <select  name="add_IssueIdNo" class="form-control">
                    <option value="0">--Chọn nơi cấp--</option>
                    <option value="5">Hà Nội</option>
                    <option value="6">Hồ Chí Minh</option>
                    <option value="7">Hải Phòng</option>
                    <option value="8">Đà Nẵng</option>
                    <option value="9">An Giang</option>
                    <option value="10">Bà Rịa - Vũng Tàu</option>
                    <option value="11">Bắc Cạn</option>
                    <option value="12">Bắc Giang</option>
                    <option value="13">Bạc Liêu</option>
                    <option value="14">Bắc Ninh</option>
                    <option value="15">Bến Tre</option>
                    <option value="16">Bình Dương</option>
                    <option value="17">Bình Phước</option>
                    <option value="18">Bình Thuận</option>
                    <option value="19">Bình Định</option>
                    <option value="20">Buôn Mê Thuột</option>
                    <option value="21">Cà Mau</option>
                    <option value="22">Cần Thơ</option>
                    <option value="23">Cao Bằng</option>
                    <option value="24">Gia Lai</option>
                    <option value="25">Hà Giang</option>
                    <option value="26">Hà Nam</option>
                    <option value="27">Hà Tĩnh</option>
                    <option value="28">Hải Dương</option>
                    <option value="29">Hậu Giang</option>
                    <option value="30">Hoà Bình</option>
                    <option value="31">Hưng Yên</option>
                    <option value="32">Khánh Hòa</option>
                    <option value="33">Kiên Giang</option>
                    <option value="34">Kon Tum</option>
                    <option value="35">Lai Châu</option>
                    <option value="36">Lâm Đồng</option>
                    <option value="37">Lạng Sơn</option>
                    <option value="38">Lào Cai</option>
                    <option value="39">Long An</option>
                    <option value="40">Nam Định</option>
                    <option value="41">Nghệ An</option>
                    <option value="42">Ninh Bình</option>
                    <option value="43">Ninh Thuận</option>
                    <option value="44">Phú Thọ</option>
                    <option value="45">Phú Yên</option>
                    <option value="46">Quảng Bình</option>
                    <option value="47">Quảng Nam</option>
                    <option value="48">Quảng Ngãi</option>
                    <option value="49">Quảng Ninh</option>
                    <option value="50">Quảng Trị</option>
                    <option value="51">Sóc Trăng</option>
                    <option value="52">Sơn La</option>
                    <option value="53">Tây Ninh</option>
                    <option value="54">Thái Bình</option>
                    <option value="55">Thái Nguyên</option>
                    <option value="56">Thanh Hoá</option>
                    <option value="57">Thừa Thiên Huế</option>
                    <option value="58">Tiền Giang</option>
                    <option value="59">Trà Vinh</option>
                    <option value="60">Tuyên Quang</option>
                    <option value="61">Vĩnh Long</option>
                    <option value="62">Vĩnh Phúc</option>
                    <option value="63">Yên Bái</option>
                    <option value="64">Đà Lạt</option>
                    <option value="65">Đắc Lắc</option>
                    <option value="66">Đắc Nông</option>
                    <option value="67">Đồng Nai</option>
                    <option value="68">Đồng Tháp</option>
                </select>
                <span style="color: red" id="email_error"> <?php echo form_error('add_IssueIdNo'); ?></span>

            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Ngày sinh:</label>
            </div>
            <div class="col-md-10">
                <div class='input-group date' id="id_birthday" data-date-format="dd-mm-yyyy" >
                    <input type='text' name="birthday" readonly="readonly"
                           class="form-control"
                           id="datetimepicker" data-date-format="dd-mm-yyyy"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <span style="color: red" id="email_error"> <?php echo form_error('birthday'); ?></span>

                </div>
            </div>

        </div>

        <div class="form-group">
            <div>
                <label>Giới tính:</label></div>
            <div class="col-md-10">
                <input type="radio" name="gen" value="M" checked="true"/>Nam
                <input type="radio" name="gen" value="F"/>Nữ
                <span style="color: red" id="email_error"> <?php echo form_error('gen'); ?></span>

            </div>
        </div>
        <div class="form-group">
            <label>Mã xác nhận:</label>
            <input class="capcha required " type="text" name="security_captcha" value="" class="form-control">
            <a class="recap" href="javascript: void(0)" alt="mã khác" onclick="javascript:regetCaptcha();">
                <img src="../../images/recapcha.jpg" alt="recapcha" style="width: 35px">
            </a>
            <img class="cap" src="<?php echo $urlSecur; ?>" id="captcha_image" style="width:145px;height:45px;"/>

        </div>

    </div>
    <div class='form-group'>
        <div class="col-xs-offset-7 col-xs-3">
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </div>
    </div>
</div>
</form>
</div>