<?php
    /**
     * Config pagination all module
     */
    $config['pagination']['per_page'] = NUMBER_RECORDS_PER_PAGE;
    //$config['pagination']["per_page"] = 100;
	$config['pagination']["use_page_numbers"] = TRUE;
	$config['pagination']["num_links"]= 6;
	$config['pagination']["first_link"]= false;
	$config['pagination']["last_link"]= false;
	$config['pagination']["full_tag_open"]= '<ul class="pagination pull-left">';
	$config['pagination']["full_tag_close"]= '</ul>';
	$config['pagination']["num_tag_open"]= '<li>';
	$config['pagination']["num_tag_close"]=
	$config['pagination']["next_tag_open"] =
	$config['pagination']["prev_tag_open"] = '</li>';
	$config['pagination']['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
	$config['pagination']['cur_tag_close'] = '</a></li>';
	$config['pagination']['next_tag_open'] = '<li class="next">';
	$config['pagination']['prev_tag_open'] = '<li class="prev">';
	$config['pagination']['next_link'] = '<i class="fa fa-angle-right"></i>'; //'Next → ';
	$config['pagination']['prev_link'] = '<i class="fa fa-angle-left"></i>'; //'← Previous';
?>