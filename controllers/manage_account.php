<?php
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_account extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('manage_account_model');
    }

    function index(){
        $result = $this->manage_account_model->get_accounts();

        $this->load->view('manage_account_view', array("result" => $result));
    }

    public function manipulate_account(){
        //construct mailer
        $email_config = Array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'ics.elib.admistrator@gmail.com',
            'smtp_pass' => 'icselibadmin'
        );
        
        $user_email = $_POST["email"];
        
        if(isset($_POST["approve"])){
            $this->load->library('email', $email_config);
			$this->email->set_newline("\r\n");

			$this->email->from('ics.elib.administrator@gmail.com', 'ICS e-lib Admistrator');
			$this->email->to($user_email);
			$this->email->subject('Request for an ICS e-Lib Account approved!');
			$this->email->message("
			Greetings from ICS e-Lib!

			Your request has been approved. Check what's new in our e-Lib!
			Thank You!
					 
			Yours Truly,
			ICS e-Lib DevTeam
			");

				if( $this->email->send()){
					$this->manage_account_model->approve_account();
				}
				else{
					show_error($this->email->print_debugger());
				}
        }
        
        else if(isset($_POST["deactivate"])){
            $this->manage_account_model->deactivate_account();
        }
        else if(isset($_POST["delete"])){
            $this->manage_account_model->delete_account();
        }
        else if(isset($_POST["activate"])){
            $this->manage_account_model->activate_account();
        }
    }
}
