<?php

class Aspsms_demo extends CI_Controller
{
    /**
     * CodeCompletion helper
     * @var Aspsms
     */
    var $aspsms;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->helper(array('url','form'));
        
        $this->load->library('aspsms');
//        $this->load->language('aspsms','english');
        
//        $userkey = $this->input->request('userkey');
//        $password = $this->input->request('password');
//        if ( ! empty($userkey) and ! empty($password))
//        {
//            $this->aspsms->setAuth($userkey, $password);
//        }
//        
//        $affiliateId = $this->input->request('affiliateId');
//        if ( ! empty($affiliateId))
//        {
//            $this->aspsms->setAffiliateId($affiliateId);
//        }
//        
//        $originator = $this->input->request('originator');
//        if ( ! empty($originator))
//        {
//            $this->aspsms->setOriginator($originator);
//        }
    }
    
    
    public function index()
    {   
        $this->load->view('aspsms_demo/main');
    }
    
    
    public function check_balance()
    {
        try {
            $balance = $this->aspsms->getCreditBalance();
        }
        catch(\Aspsms\AspsmsException $e) {
            show_error('ASPSMS send error: '. $e->getMessage());
        }
        
        if ( !is_float($balance))
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 0,
            'messages' => array("You have {$balance} credits left.")
        ));
    }
            
    
    public function send_text()
    {
        $recipient = $this->input->post('recipient');
        $text   = $this->input->post('text');
        
        $originator = $this->input->post('originator');
        if ( ! empty($originator))
        {
            $this->aspsms->setOriginator($originator);
        }
        
        try {
            $success = $this->aspsms->sendText($recipient, $text);
        }
        catch(\Aspsms\AspsmsException $e) {
            show_error('ASPSMS send error: '. $e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("Message to recipient(s) {$recipient} submitted to ASPSMS server.")
        ));
    }
    
    
    public function send_two_trackables()
    {
        $trackingnr = $this->input->post('trackingnr');
        $recipient = $this->input->post('recipient');
        $text   = $this->input->post('text');
        
        try {
            $success = $this->aspsms->sendText(array_combine($trackingnr, $recipient), $text);
        }
        catch(\Aspsms\AspsmsException $e) {
            show_error('ASPSMS send error: '. $e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("Messages with tracking numbers " . implode(',',$trackingnr) . " submitted to ASPSMS server.")
        ));
    }
    
    
    public function track()
    {
        $this->lang->load('aspsms','english');
        $this->load->helper('language');
        
        $trackingnr = $this->input->get('trackingnr');
        
        try {
            $status = $this->aspsms->getDeliveryStatus($trackingnr);
        }
        catch(\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if (!is_array($status))
        {
            show_error('Unexpected server response');
        }
        
//        var_dump($status);
        $vars['status'] = $status;
        $vars['trackingnr'] = $trackingnr;
        
        $this->load->view('aspsms_demo/track',$vars);
    }
    
    
    public function check_originator()
    {
        $originator = $this->input->post('originator');
        
        try {
            $success = $this->aspsms->checkOriginator($originator);
        }
        catch(\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if ($success === NULL)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $message = $success ? "Originator `{$originator}` is valid for use." : "Originator `{$originator}` is NOT valid for use.";
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 5,
            'messages' => array($message)
        ));
    }
    
    
    public function request_originator_unlock()
    {
        $originator = $this->input->post('originator');
        
        try {
            $success = $this->aspsms->requestOriginatorUnlockCode($originator);
        }
        catch(\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if ( ! $success and $this->aspsms->getLastStatusCode() == 31)
        {
            $this->load->view('aspsms_demo/success',array(
                'redirect' => 3,
                'messages' => array("Originator unlock request for `{$originator}`failed, as originator is already authorized.")
            ));
        }
        elseif ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Originator not valid or could not be verified due to '.$statusCode. ': '.$statusDescription);
        }
        else
        {
            $this->load->view('aspsms_demo/success',array(
                'redirect' => 3,
                'messages' => array("Originator unlock request sent for `{$originator}`. {$originator} should shortly receive an unlock code.")
            ));
        }
    }
    
    
    public function unlock_originator()
    {
        $code = $this->input->post('code');
        $originator = $this->input->post('originator');
        
        try {
            $success = $this->aspsms->unlockOriginator($code,$originator);
        }
        catch(\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Originator could not be unlocked or some other error occured: '.$statusCode. ' '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("Originator `{$originator}` is unlocked and ready for use.")
        ));
    }
    
    
    public function send_token()
    {
        $phoneNr = $this->input->post('recipient');
        $reference = $this->input->post('reference');
        $message = $this->input->post('message');
        $mask = $this->input->post('mask');
        $minutes = $this->input->post('minutes');
        $case_sensitive = (bool)$this->input->post('case_sensitive');
        
        try {
            $success = $this->aspsms->sendGeneratedToken($phoneNr, $reference, $message, $mask, $minutes, $case_sensitive);
        }
        catch (\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("Token presumably sent to `{$phoneNr}`. Owner should shortly receive a token code.")
        ));
    }
    
    public function validate_token()
    {
        $phoneNr = $this->input->post('recipient');
        $reference = $this->input->post('reference');
        $token = $this->input->post('token');
        
        try {
            $valid = $this->aspsms->validateToken($phoneNr, $reference, $token);
        }
        catch(\Aspsms\AspsmsException $e){
            show_error('ASPSMS query failed: '.$e->getMessage());
        }
        
        if ( ! $valid)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Token invalid or unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("Token received by `{$phoneNr}` is valid.")
        ));
    }
    
    
    public function send_wap()
    {
        $recipient = $this->input->post('recipient');
        $url  = $this->input->post('url');
        $description   = $this->input->post('description');
        
        try {
            $success = $this->aspsms->sendWapPush($recipient, $url, $description);
        }
        catch(\Aspsms\AspsmsException $e) {
            show_error('ASPSMS send error: '. $e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("WAP for recipient {$recipient} submitted to ASPSMS server.")
        ));
    }
    
    
    public function send_vcard()
    {
        $recipient = $this->input->post('recipient');
        $name   = $this->input->post('vcard_name');
        $phone   = $this->input->post('vcard_phone');
        
        try {
            $success = $this->aspsms->sendVCard($recipient, $name, $phone);
        }
        catch(\Aspsms\AspsmsException $e) {
            show_error('ASPSMS send error: '. $e->getMessage());
        }
        
        if ( ! $success)
        {
            $statusCode = $this->aspsms->getLastStatusCode();
            $statusDescription = $this->aspsms->getStatusDescription($statusCode);
            show_error('Unexpected server response status '.$statusCode. ': '.$statusDescription);
        }
        
        $this->load->view('aspsms_demo/success',array(
            'redirect' => 3,
            'messages' => array("VCard for recipient {$recipient} submitted to ASPSMS server.")
        ));
    }
    
}