<?php session_start(); ?>
<?php require ('header.php') ;?> 
<?php 

use \PHPWine\VanillaFlavour\Wine\System\Auth;
use \PHPWine\VanillaFlavour\Wine\System\Validate;
use \PHPWine\VanillaFlavour\Wine\Optimizer\Form;

/**
 * @copyright (c) 2021 PHPWine\VanillaFlavour v1.2.0.9 Cooked by nielsoffice 
 *
 * MIT License
 *
 * PHPWine\VanillaFlavour v1.2.0.9 free software: you can redistribute it and/or modify.
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @category   PHPLibrary PHPWine\VanillaFlavour
 * @package    PHPHtml-Optimizer | CodeDesigner/Enhancer | Advance Form Builder | Handling Form Validation | Form Validation v2 | BASIC-Authentication | HtmlMinifier
 *            
 *            
 * @author    Leinner Zednanref <nielsoffice.wordpress.php@gmail.com>
 * @license   MIT License
 * @link      https://github.com/nielsofficeofficial/PHPWine
 * @link      https://github.com/nielsofficeofficial/PHPWine/blob/PHPWine_Vanilla_Flavour/README.md
 * @link      https://www.facebook.com/nielsofficeofficial
 * @version   v1.2.0.9
 * @since     03.03.2022
 * 
 * Would you like me to treat a cake and coffee ?
 * Become a donor, Because with you! We can build more...
 * Donate:
 * GCash : +639650332900
 * Paypal account: syncdevprojects@gmail.com
 *
 */


 #############################################################################################################
 # THIS IS FOR DEMO DATABASE CONNECTION !!! BUILD YOUR OWN DATABSE CONENCTION BASE ON YOUR CURRENT FRAMEWORK !
 #############################################################################################################
   
 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'auth');

  // Define new instance connection
  $connection = new mysqli( DB_SERVER , DB_USERNAME, DB_PASSWORD, DB_NAME);

 #############################################################################################################
 # THIS IS FOR DEMO DATABASE CONNECTION !!! BUILD YOUR OWN DATABSE CONENCTION BASE ON YOUR CURRENT FRAMEWORK !
 #############################################################################################################
 
if($_SERVER["REQUEST_METHOD"] == "POST")
{  

    $un               =   VALIDATE::$DATAFORM = ["username","Please enter a username."];
    $username         =   VALIDATE::HASCONTAINS( input: $un);  
    $username_err     =   VALIDATE::ERROR( result: $username, require : $un);    

    $ue               =   VALIDATE::$DATAFORM = ["email","Please enter a email."];
    $email            =   VALIDATE::HASCONTAINS( input: $ue);  
    $email_err        =   VALIDATE::ERROR(  result: $email, require: $ue);    

    $um               =   VALIDATE::$DATAFORM = ["mobile","Please enter a Phone."];
    $mobile           =   VALIDATE::HASCONTAINS( input: $um);  
    $mobile_err       =   VALIDATE::ERROR(  result: $mobile, require: $um);    

    $up               =   VALIDATE::$DATAFORM = ["password","Please enter a password..."];
    $userpassword     =   VALIDATE::HASCONTAINS( input : $up);  
    $userpassword_err =   VALIDATE::ERROR(  result: $userpassword , require : $up);  

    $upc              =   VALIDATE::$DATAFORM = ["confirm_password","Please confirm password..."];
    $conpassword      =   VALIDATE::HASCONTAINS( input : $upc);  
    $conpassword_err  =   VALIDATE::ERROR(  result: $conpassword,  require : $upc);  

    $auth_un_bind     =   VALIDATE::BIND( connection : $connection, 

    bind_user_data : [   
    
        'QUERY_STATEMENT'    => VALIDATE::CHECKQUERY('users_log',["id"],["username"])
       ,'INPUT_HASCONTAINS'  => $username  
       ,'INPUT_DATAEXIST'    => "This {$username} was already used."
  
    ]); 

    $auth_ue_bind      =  VALIDATE::BIND($connection, 
    [   
    
        'QUERY_STATEMENT'    => VALIDATE::CHECKQUERY('users_log',["id"],["email"])
       ,'INPUT_HASCONTAINS'  => $email  
       ,'INPUT_DATAEXIST'    => "This {$email} was already used."
  
    ]); 

    $auth_um_bind      =  VALIDATE::BIND($connection, 
    [   
    
        'QUERY_STATEMENT'    => VALIDATE::CHECKQUERY( table : 'users_log', col_id : ["id"], col_name : ["mobile"])
       ,'INPUT_HASCONTAINS'  => $mobile  
       ,'INPUT_DATAEXIST'    => "This {$mobile} was already used."
  
    ]); 

    $catch_um          =  VALIDATE::CATCH(input_result :  $mobile_err , bind_result: $auth_um_bind, valid_type :  $valid_type = [
       
      NUMERICTYPE      => ['mobile' ,'Phone must be numeric ex. 123'],
      MAXLENGTH        => ['mobile' , 11 ,'Mobile number must be maximum 11 Digit!'],
      MINLENGTH        => ['mobile' , 4  ,'Mobile number must greater than 4 Digit!']
  
   ]);
    
    $catch_un          =  VALIDATE::CATCH( $username_err, $auth_un_bind, $valid_type = [
       
     MINLENGTH         => [ 'username', 7, 'MIN of 7 characters!' ]
      
    ]);

    $catch_ue          =  VALIDATE::CATCH($email_err, $auth_ue_bind, $valid_type = [
       
     VALID_EMAIL       => ['email','must be valid email']
 
    ]);

    $catch_up          =  VALIDATE::FORM( input_result: $userpassword_err, valid_type: $valid_type = [
      
      MINLENGTH        => ['password', 8,'Password must have atleast 8 characters.'],
      VALIDPASSWORD    => ['password',   'Requere password has at least 8 characters + one number + one upper case letter + one lower case letter and one special character.' ],
      CONFIRMPASSWORD  => [ $userpassword, $conpassword,  'Not Match!'  ] 
    
    ]);

    # CHECK IF ANYTHING IS TRUE THE  RETURN APPROPRIATE
    $initializeRequestError = ( 

         !empty($catch_un)     && !empty($catch_ue)    
      && !empty($catch_up)     && !empty($catch_um)    

    ) ? false : true ;


        if( isset($initializeRequestError) == true ) :
            
            if($stmt = $connection->prepare( AUTH::BINDQUERY( 'users_log', ['username', 'email', 'mobile', 'password'] , ['?', '?', '?' , '?']) ) ) : 

                $stmt->bind_param("ssss", $param_username, $param_email, $param_mobile, $param_password);

                $param_username =  DOIF(is_null($catch_un)   ,  $username     );
                $param_email    =  DOIF(is_null($catch_ue)   ,  $email        );
                $param_mobile   =  DOIF(is_null($catch_um)   ,  $mobile       );
                $param_password =  DOIF(is_null($catch_up)   ,  password_hash( $userpassword , PASSWORD_DEFAULT) );

                AUTH::BINDEXECUTE( redirect : 'login', report : ERROR_DEVELOPER_CONCERN);        
   
                $stmt->close();

            endif;

        endif;
        
    $connection->close();
   
 }

 /**
  *
  * Defined error message handler reg form !
  * @since 0.06.21
  * @since v1.0
  *
  **/ 
  $eCatch_errors =  _xUL( 'id-eCatch_err',
   
    DOIF( !empty($catch_un)        ,ELEM('li' , (($catch_un)?? '')        ,setElemAttr(['class'],['err_username_msg']))     )
   .DOIF( !empty($catch_ue)        ,ELEM('li' , (($catch_ue)?? '')        ,setElemAttr(['class'],['err_user_email_msg']))   )
   .DOIF( !empty($catch_um )       ,ELEM('li' , (($catch_um)?? '')        ,setElemAttr(['class'],['err_user_mobile_msg']))  )
   .DOIF( !empty($catch_up)        ,ELEM('li' , (($catch_up)?? '')        ,setElemAttr(['class'],['err_con_password_msg'])) )
   .DOIF( !empty($conpassword_err) ,ELEM('li' , (($conpassword_err)?? '') ,setElemAttr(['class'],['err_password_msg']))     )

 , null
 , 'eCatch_error'
 , 'end-of-id-eCatch_err'
 , FUNC_ASSOC

 );
  
 echo (!empty($eCatch_errors)) ? $eCatch_errors : '';

 /**
  *
  * Defined BEGIN the register new user Form !
  * @since 09.18.21
  * @since v1.0
  *
  **/ 
 _FORM( attr : setElemAttr(['action','method'],[ htmlspecialchars($_SERVER["PHP_SELF"]), 'POST']));

  $user_name           = FORM::LABEL('label-id-un' , 'Username'       ) . __BR()
                        .FORM::TEXT('id-username'  , 'class-username' , [['name', 'value'] , ['username', ($catch_un?? ($username?? ''))]]); 

  $user_email          = FORM::LABEL('label-id-e'  , 'Email' ) . __BR()
                        .FORM::TEXT('id-email'     , 'class-email' , [['name', 'value'] , ['email',  ($catch_ue?? ( $email?? ''))]]);

  $user_mobile         = FORM::LABEL('label-id-m'  , 'Mobile' ) . __BR()
                        .FORM::TEXT('id-mobile'    , 'class-mobile' , [['name', 'value'],['mobile', ($catch_um?? ($mobile?? ''))]] );

  $user_password       = FORM::LABEL('label-id-p'   , 'Password' ) . __BR()
                        .FORM::PASSWORD('id-mobile' , 'class-mobile' , [['name'],['password']]);

  $user_con_password   = FORM::LABEL('label-id-confirm_password' , 'Confirm Password'  ) . __BR()
                        .FORM::PASSWORD('id-conPassword'         , 'class-conPassword' , [['name'],['confirm_password']] );

  $user_submit_btn     = FORM::BUTTONS('id-conPassword' , 'class-submit' , [['value'],['Submit']] ) 
                        .FORM::RESET('id-conPassword'   , 'class-submit' , [['value'],['Reset']]  ); 

  $registration_form = _xdiv( FUNC_ASSOC ,
    
      ELEM('div', $user_name)
     .ELEM('div', $user_email)
     .ELEM('div', $user_mobile)
     .ELEM('div', $user_password)
     .ELEM('div', $user_con_password)
     .ELEM('div', $user_submit_btn)

     ._xp('id-heading-Para',"Already have an account?".ELEM('a','Log in now',setElemAttr(['href'],['login.php'])) , FUNC_ASSOC )
  
  );

  echo $registration_form;
  
 xFORM(" END Of the form ");

?>
<?php require ('footer.php') ;?> 
