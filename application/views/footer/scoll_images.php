<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 12/15/14
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php
$imgdir = 'images/footer/'; //Pick your folder
//$allowed_types = array('png', 'jpg', 'jpeg', 'gif'); //Allowed types of files
//$dimg = opendir($imgdir); //Open directory
//while ($imgfile = readdir($dimg)) {
//    if (in_array(strtolower(substr($imgfile, -3)), $allowed_types) OR in_array(strtolower(substr($imgfile, -4)), $allowed_types)) /*If the file is an image add it to the array*/ {
//        $a_img[] = $imgfile;
//    }
//}
//
//echo "<ul id='flexiselDemo3' class='nav nav-pills'>";
//$totimg = count($a_img); //The total count of all the images
//for ($x = 0; $x < $totimg; $x++) {
//    echo "<li><a href='#' > <img src='../" . $imgdir . $a_img[$x] . "' /></a></li>";
//}
//echo "</ul>";
//
?>
<?php
$footer_img = array();
$footer_img = explode(',', FOOTER_IMG);
echo "<ul id='flexiselDemo3' class='nav nav-pills'>";
foreach ($footer_img as $img) {
    $img_info = explode('|', $img);
    $img_name = $img_info['0'];
    $img_link = $img_info['1'];
    ?>
    <li>
        <a href="<?php echo $img_link; ?>" target="_blank" rel="nofollow" title="<?php echo $img_name; ?>">
            <img src="../<?php echo $imgdir . $img_name; ?>" alt="<?php echo $img_name; ?>"/>
        </a>
    </li>
<?php } 
	echo "</ul>"
?>