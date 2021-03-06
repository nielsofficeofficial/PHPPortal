<?php session_start(); ?>
<?php require ('header.php') ;?> 
<?php 

use \PHPWine\VanillaFlavour\Wine\System\Auth;
use \PHPWine\VanillaFlavour\Wine\System\Request;
use \PHPWine\VanillaFlavour\Wine\System\Validate;
use \PHPWine\VanillaFlavour\Wine\Optimizer\Form;

/**
 * @copyright (c) 2021 PHPWine\VanillaFlavour v1.2.0.4 Cooked by nielsoffice 
 *
 * MIT License
 *
 * PHPWine\VanillaFlavour v1.2.0.4 free software: you can redistribute it and/or modify.
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
 * @version   v1.2.0.5
 * @since     02.13.2022
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


/**
 *
 * Defined If the session is true or active then redirect to certain page!
 * @since 04.05.21
 * @since v1.0
 * 
 **/ 
AUTH::USERAUTH('dashboard', true); 

/**
 *
 * Defined Make request for data formlogin when submit it !
 * @since 04.06.21
 * @since v1.0
 *
 **/ 
if($_SERVER["REQUEST_METHOD"] == "POST") : 
 
   /**
    *
    * Defined Check if username has contains
    * @since 04.06.21
    * @since v1.0
    *
    **/ 
    $un           = VALIDATE::$DATAFORM = ["username","Enter username or email or mobile"];
    $username     = VALIDATE::HASCONTAINS($un);  
    $err_username = VALIDATE::ERROR($username, $un);

   /**
    *
    * Defined Check if password has contains
    * @since 04.06.21
    * @since v1.0
    *
    **/ 
    $pw           = VALIDATE::$DATAFORM = ["password","Please enter valid associated password."];
    $password     = VALIDATE::HASCONTAINS($pw);  
    $err_password = VALIDATE::ERROR($password, $pw);

   /**
    *
    * Defined Default|CaseSensitive = ["username","email","mobile","password"...etc...]
    * @since 04.06.21
    * @since v1.0
    *
    **/ 
    $auth_err    = AUTH::BIND( $connection, 
    [   
       
        'QUERY_STATEMENT'         => AUTH::CHECKQUERY('users_log',['username','email','mobile','password','id','created_at'])
       ,'USERNAME_HASCONTAINS'    => $username 
       ,'USERNAME_ERROR'          => $err_username
       ,'PASSWORD_HASCONTAINS'    => $password
       ,'PASSWORD_ERROR'          => $err_password
       ,'NOTEXIST_CREDENTIAL'     => "USERNAME OR PASSWORD IS NOT ACCOSIATED WITH OUR SYSTEM" // email and password not exist to system
       ,'NOTASSOCIATED_CREDENTIAL'=> "INVALID CREDENTIALS ! NOT YET ACCOSIATED WITH OUR SYSTEM!" // either password/mobile/email/username is not assciated to system
       ,'USER_REDIRECT'           => "dashboard"

    ], REQUEST::SESSION_PORTAL_REQUEST ); 

  endif;  

 /**
  *
  * Defined error message handler reg form !
  * @since 0.06.21
  * @since v1.2
  *
  **/ 
  $sets_eCatch = _xUL( 'id-eCatch_err' ,
   
   DOIF( !empty($err_username)   ,ELEM('li' , (($err_username)?? '' ) ,setElemAttr(['class'],['err_username_msg']))  )
  .DOIF( !empty($err_password)   ,ELEM('li' , (($err_password)?? '')  ,setElemAttr(['class'],['err_password_msg']))  )
  .DOIF( !empty($auth_err)       ,ELEM('li' , (($auth_err)?? '')      ,setElemAttr(['class'],['err_password_msg']))  )
 
 , attr  : [ ]
 , class : 'eCatch_error'
 , label : 'end-of-id-eCatch_err'
 , assoc : FUNC_ASSOC
 
);

echo (!empty($sets_eCatch)) ? $sets_eCatch : ''; 

 /**
  * Defined BEGIN the Login Form !
  * @since 04.06.21
  * @since v1.0
  **/ 
_FORM(setElemAttr(['action','method'],[ htmlspecialchars($_SERVER["PHP_SELF"]), 'POST']));
 
 $user_login           =  FORM::LABEL('label-id-un','Username/Email/Mobile') . __BR()
                         .FORM::TEXT('id-username','class-username',[['name','value'],['username',(($_COOKIE['username'])?? (( $username )?? '')) ]] );

 $user_password        =  FORM::LABEL('label-id-un','Password') . __BR()
                         .FORM::PASSWORD('id-username','class-username',[['name','value'],['password',(($_COOKIE['password'])?? (( $password )?? '')) ]] );

 $user_remember        =  FORM::CHECKBOX('checkbox','class-checkbox',[['name',''],['remember',(isset($_COOKIE["username"])) ? 'checked' : false ]] ) 
                         .FORM::LABEL('label-id-un','Remember me');
                  
 $user_submit_btn      = ELEM( 'div' , FORM::BUTTONS( 'id-conPassword','class-submit', [['value'],['Submit']] ) 
                  
                         ,[['id','class'],['id-submit','submit_from_group']] 
                        );

  $login_form = _xdiv( FUNC_ASSOC ,
    
      ELEM('div', $user_login)
     .ELEM('div', $user_password)
     .ELEM('div', $user_remember)
     .ELEM('div', $user_submit_btn)

     ._xp( 'singup-id', ELEM('p','Don\'t have an account?'.ELEM('a','Sign up now',[['href'],['register.php']]) ) , FUNC_ASSOC )
  
  );

  echo $login_form;
  
 xFORM(" END Of the form ");


?>
<?php require ('footer.php') ;?> 
