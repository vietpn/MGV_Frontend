<?php defined('BASEPATH') OR exit('No direct script access allowed');


class id_email
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->helper('url');
        
    }

	//[27/7/2015]luanbv repleace sendemail function
	public function sendmail($rec_email,$email_content,$subject)
	{
	/*
		$this->ci->load->library('email');
		$this->ci->email->clear();
		$config['wordwrap'] = TRUE;
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.vnptepay.com.vn';
		$config['smtp_user'] = SMTP_USER;
		$config['smtp_pass'] = SMTP_PASSWORD;
		$config['charset'] = 'UTF-8';
		$config['mailtype'] = "html";
		$config['starttls'] = TRUE;
		$this->ci->email->initialize($config);
		
		$this->ci->email->from(SMTP_USER, 'MegaID');
		$this->ci->email->to($rec_email); 

		$this->ci->email->subject($subject);
		$this->ci->email->message($email_content);	
		$ret = $this->ci->email->send();
		return $ret;
		*/
		log_message('error','Truoc luc gui mail');
		 $client = new SoapClient(WS_SENDMAIL, array('encoding' => 'UTF-8'));
        $result = $client->sendEmail(SMTP_USER, SMTP_PASSWORD, $subject, $email_content, $rec_email, 0);
		log_message('error','ket qua gui mail: '.$result);
        if ($result != '0') {
            return false
        } else {
            return true;
        }
	}

}