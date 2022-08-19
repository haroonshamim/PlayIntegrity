<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\PlayIntegrity;
use Google\Service\PlayIntegrity\DecodeIntegrityTokenRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
define('NONCE_SECRET', 'CEIUHET745T$^&%&%^gFGBF$^');
class ApiController extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


private function generateSalt($length = 10){
        //set up random characters
        $chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        //get the length of the random characters
        $char_len = strlen($chars)-1;
        //store output
        $output = '';
        //iterate over $chars
        while (strlen($output) < $length) {
            /* get random characters and append to output till the length of the output 
             is greater than the length provided */
            $output .= $chars[ rand(0, $char_len) ];
        }
        //return the result
        return $output;
    }
public function generateNonce($length = 10, $form_id, $expiry_time){
        //our secret
        $secret = NONCE_SECRET;

        //secret must be valid. You can add your regExp here
        if (is_string($secret) == false || strlen($secret) < 10) {
            throw new InvalidArgumentException("A valid Nonce Secret is required");
        }
        //generate our salt
        $salt = self::generateSalt($length);
        //convert the time to seconds
        $time = time() + (60 * intval($expiry_time));
        //concatenate tokens to hash
        $toHash = $secret.$salt.$time;
        //send this to the user with the hashed tokens
    //    $nonce = $salt .':'.$form_id.':'.$time.':'.hash('sha256', $toHash);
      
	  
	    // CURRENTLY I AM NOT ADDING EXTRA SECURITIES
	  
		$nonce = self::generateSalt($length);
		$nonce=base64_encode($nonce);
		
		//$nonce = $nonce.'=';
		
	  
	  //store Nonce
        self::storeNonce($form_id, $nonce);
        //return nonce
        return $nonce;
    }
private function storeNonce($form_id, $nonce){
        //Argument must be a string
        if (is_string($form_id) == false) {
            throw new InvalidArgumentException("A valid Form ID is required");
        }
     
       // $_Encryptednonce=md5($nonce);
		  $_Encryptednonce=($nonce);
		session([$form_id =>  $_Encryptednonce]);
		
        return true;
    }
public function verifyNonce($nonce,$form_id){
    //our secret
   // $secret = NONCE_SECRET;
    //split the nonce using our delimeter : and check if the count equals 4
    
	//$split = explode(':', $nonce);
   // if(count($split) !== 4){
   //     return false;
   // }


    //reassign variables
//    $salt = $split[0];
//    $form_id = "haroon";
//    $time = intval($split[2]);
//    $oldHash = $split[3];
   

   //check if the time has expired
   

//   if(time() > $time){
//        return false;
//    }

    /* Nonce is proving to be valid, continue ... */


//dump($form_id);
//dump(md5($nonce));
//dump(session()->get($form_id));  


		$RecievedNonce=strval(base64_decode($nonce));
     	$SavedNonce=strval(base64_decode(session()->get($form_id)));
     
	
		
	 
        if(strval($SavedNonce) != strval($RecievedNonce))
		{
            return false;
		}
		else
		{
			return true;
		}


    //check if the nonce is valid by rehashing and matching it with the $oldHash
  //  $toHash = $secret.$salt.$time;
 //   $reHashed = hash('sha256', $toHash);
    //match with the token
  //  if($reHashed !== $oldHash){
  //      return false;
  //  }
    /* Wonderful, Nonce has proven to be valid*/
    //return true;
}




public function GetNonce($MobileId)
{
		$nonce = self::generateNonce(23, $MobileId, 100);
		echo $nonce;
		//$result=self::verifyNonce($nonce);
		//$result=session()->get('$form_id');
		//dump($result);  
	}	

public function performCheck($BundlePackage,$token,$MobileId) 
{
		
	
        $client = new Client();
        $client->setAuthConfig(__DIR__ . "/firebasekey.json");					
		$client->addScope(PlayIntegrity::PLAYINTEGRITY);
        $service = new PlayIntegrity($client);
        $tokenRequest = new DecodeIntegrityTokenRequest();
		$tokenRequest->setIntegrityToken($token);
		//$tokenRequest->setIntegrityToken("eyJhbGciOiJBMjU2S1ciLCJlbmMiOiJBMjU2R0NNIn0.lhjh6jkhwXAIZUaCtVV2_5f4AVjGxEUcb9DK8ymsXELyXqKv3oaVtg.usPHDbqvEdvMehyV.U9LSWBAeWmA29y3LHk1rf68Z_0c8pXIGGKKbLkQftezTxAgrPDv9MHlUsw73RU8CQcX_2yttivKzNPlMfCLa05nhAHD9DtSv2nOyTctNjtC2xTJ4ngPUqyEIhLEKNcWczvudlhJTLgKU6Syt1CQm9gb87RIoNBIsYbRyEHSV0YbPzcPBvyziEXCu-KndeBEOUziZmdI3ZRSC0SqEib-J9dBgh5qAu0_UPMTc8QvnzX6obc9RuDgFzBZnHPpxbm7Z9ewkCDbfqQv5A6gv-okdMBsQNzHY34hZZ90fMmNUJUK0EcYE1NoRNgTwXFZs57SgRYwSV-OjrDkI8FB5Zcmt6Z_c_7EhvIsCDe0gI-QDJlcDmiEihGF10R3jMRsl0QtCOZXIVk32HjrVmvWmWZJ0JUnheLU-cLvwVJAusg2GG1tSD3WEp8C2rFhQeNSFJFRAWyXASwqMt-N8AavNLh6JN9jrr-9g6T_viA7DDd4Qog2UB1VPsRZn2UkvXMoxZyu6DEd9UQFpH145vRWwzuVIsSpYpZ6CjCyOMo2yIqZBWPysDwrIzTLV5KZRP-2D-jSUDVgElBrqekBFF19hx9tHHZBKAAONKrjdG-ERNOUZcdWEwQhnhtZZRVH7RdEqQwi3p30rNx36hXYzrw9oSSP78LrWaq0b51uPjFY-jyhAGmgHUiEkAcU_KOG_MeQbzUJFRjEJTmHULh2wpRtHmojIFLTG4BYvHtFEFyGdsxu9z8iTkRk5w_r5xuY_RkTM9B9jxFbrecJYGi_oT27SHYoYJyd93qVYkhBvR7ThL7p5tiNpvegqhcgTWskiVs3VdYEEbuCx3N_wh0njcvbxnmHTyQg8PdaXAsp37cUBGdjPu295pFLISmWA81qnpUgs7ZsnLY3ZIQAYpxiVmSwnn46Zf22D4FL8yOv8WMEqF3QBLA8FSVPJMjK2FkC4EqgOlj1T5EApMHP2YYQ4vvSEN-FrY2kOUT1BsXfIiU019eqwSyCBC5mebXYrKX5Ni09CwXX0oMouzNMHRRcG42sRXMsQ6vRUdxMNYMYbVwoxjECBE9U-CkOdcS1ruirY7yRaLvIJMJ9hpZsof9y4ULpxRrNR7fGKOioPCWI_uoTQ3_Mr6M7noLlMWahvRGrh14PKcGJpD-wxw_WTCRJWvmmR608WUFE--_4KrZR59YI_U83fz47i7ws36FmRHaWi9XwSovwC.u47JUoQ7gBZgkw1aHRRreA");
    

	try 
	{
		
	
			
		$result = $service->v1->decodeIntegrityToken($BundlePackage, $tokenRequest);
	
			
			
	
		$jsonData = json_encode($result);

		echo $jsonData;
		
		echo  "::";
		
		$nonce=(json_decode($jsonData, true)['tokenPayloadExternal']['requestDetails']['nonce']);
		$appVerdict=(json_decode($jsonData, true)['tokenPayloadExternal']['appIntegrity']['appRecognitionVerdict']);
		$accountVerdict=(json_decode($jsonData, true)['tokenPayloadExternal']['accountDetails']['appLicensingVerdict']);
		$deviceVerdict=(json_decode($jsonData, true)['tokenPayloadExternal']['deviceIntegrity']['deviceRecognitionVerdict'][0]);



		$result=self::verifyNonce($nonce,$MobileId);
		if($result==false)
		{		
			echo("NONCE VERIFICATION FAILED");
			//exit(1);
		}
		else
		{
			echo("NONCE VERIFICATION PASSED");
			
		}
		
		
		echo(",");
		
	    if ($appVerdict === 'UNRECOGNIZED_VERSION') 
		{
            echo "Game not recognized";
        }
		else if ($appVerdict === 'PLAY_RECOGNIZED') {
            echo "Game is recognized";
        }
		else
		{
			echo "UNEVALUATED";
		}
			
		echo(",");
			
			
		if ($accountVerdict === 'UNLICENSED') 
		{
           echo "User is not licensed to use app";  
        }
		else if ($accountVerdict === 'LICENSED') 
		{
           echo "User is licensed to use app";  
        }
		else
		{
			 echo "UNEVALUATED";  
		}
		
		echo(",");
			
           //Possible values of $deviceVerdict[0] : MEETS_BASIC_INTEGRITY, MEETS_DEVICE_INTEGRITY, MEETS_STRONG_INTEGRITY
        if (!isset($deviceVerdict) || $deviceVerdict !== 'MEETS_DEVICE_INTEGRITY') 
		{
			  echo "device doesn't meet requirement";
        }
		else
		{
			 echo "device  meet requirement";
		}
		
	}
	catch(\Exception $e) 
	{
		echo("ERROR FOUND");
		    exit(1);
		//abort(500, 'Could not create office or assign it to administrator');
	}
		
    }
}