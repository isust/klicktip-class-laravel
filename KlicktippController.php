<?php namespace App\Http\Controllers\Klicktipp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KlicktippController extends Controller {

  /**
   * @param $email KlicktippController - Email who would be validate
   * @return bool true or false
   */
  public function validateEmail($email)
  {
    $validator = \Validator::make($email, ['email' => 'required|email'], ['email.email' => 'Das ist kein Email Adresse']);

    if ($validator->passes()) { return true; }
    return false;
  }

  /**
   * @param $email KlicktippController - Email who would be validate
   * @param string $record validates MX Record
   * @return bool
   */
  public function validateMX($email, $record = 'MX')
  {
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
  }


  public function sendEmail($email,$subject,$emailklicktipp)
  {
    if($this->validateEmail(array('email' => $email))){
      if($this->validateMX($email)){
        Mail::raw('', function ($message) use($email, $subject, $emailklicktipp){
          $message->to($emailklicktipp);
          $message->subject($subject);
          $message->from($email);
        });
        return true;
      }
    }
    return false;
  }

}
