<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class APIController extends Controller
{
    public function route_sms_send(Request $request){
        $data = $this->send_with_server($request);
        return response()->json($data);
    }

    public function send_with_curl($request){
        $message = $request->input('message');
        $username = 'gtzm-mightyfin';
        $password = 'Mighty@2';
        
        $type = '0';
        $dlr = '1';
        $destination = $request->input('phone');
        $source = 'Mighty Finance Solution';
    
        $url = "https://api.rmlconnect.net/bulksms/bulksms?username=$username&password=$password&type=$type&dlr=$dlr&destination=$destination&source=$source&message=$message";

        // Initialize cURL session
        $ch = curl_init();
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // Set additional options
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Follow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout in seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // Connection timeout in seconds
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (not recommended in production)
        
        // // Set custom headers if needed
        // $headers = [
        //     'Content-Type: application/json',
        //     'Authorization: Bearer ' . $yourAccessToken,
        // ];
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);    
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            // Handle cURL error
            echo 'Curl error: ' . curl_error($ch);
        }
        // Close cURL session
        curl_close($ch);
    
        // Handle the response, you might want to check for success or log the result
        $responseData = json_decode($response, true);
        return $responseData;
    }

    public function send_with_guzzle($request){
        $message = $request->input('message');
        $username = 'gtzm-mightyfin';
        $password = 'Mighty@2';
        $type = '0';
        $dlr = '1';
        $destination = $request->input('phone');
        $source = 'Mighty Finance Solution';
        $url = "https://api.rmlconnect.net/bulksms/bulksms";
        $client = new Client();
    
        try {
            // Make the HTTP request using Guzzle
            $response = $client->get($url, [
                'query' => [
                    'username' => $username,
                    'password' => $password,
                    'type' => $type,
                    'dlr' => $dlr,
                    'destination' => $destination,
                    'source' => $source,
                    'message' => $message,
                ],
                'timeout' => 30, // Timeout in seconds
                'connect_timeout' => 20, // Connection timeout in seconds
                // 'verify' => false, // Disable SSL verification (not recommended in production)
            ]);
            // Handle the response, you might want to check for success or log the result
            $responseData = json_decode($response->getBody(), true);
            // dd($response);
            // ... (handle the $responseData as needed)
            return $responseData;
        } catch (RequestException $e) {
            dd($e);
            // Handle Guzzle request exception
            echo 'Guzzle error: ' . $e->getMessage();
        }
    }

    public function send_with_server($request) {
        $message = $request->input('message');
        $username = 'gtzm-mightyfin';
        $password = 'Mighty@2';
    
        $type = '0';
        $dlr = '1';
        $destination = $request->input('phone');
        $source = 'Mightyfin';
    
        // API endpoint
        $apiEndpoint = "http://rslr.connectbind.com:8080/bulksms/bulksms";
    
        // Build the query parameters
        $queryParams = http_build_query([
            'username' => $username,
            'password' => $password,
            'type' => $type,
            'dlr' => $dlr,
            'destination' => $destination,
            'source' => $source,
            'message' => $message,
        ]);
    
        // Full API URL with query parameters
        $apiUrl = "{$apiEndpoint}?{$queryParams}";
    
        // Initialize cURL session
        $ch = curl_init();
    
        // Set cURL options for GET request
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            // Handle cURL error
            echo 'Curl error: ' . curl_error($ch);
        }
    
        // Close cURL session
        curl_close($ch);
    
        // Return the API response
        return $response;
    }
    


    // public function bulkSMS(){

    //     $host = '';
    //     $port = '';
    //     /*
    //     * Username that is to be used for submission
    //     */
    //     $strUserName = '';
    //     /*
    //     * password that is to be used along with username
    //     */
    //     $strPassword = '';
    //     /*
    //     * Sender Id to be used for submitting the message
    //     */
    //     $strSender=1;
    //     /*
    //     * Message content that is to be transmitted
    //     */
    //     $strMessage = $request->input('message');
    //     /*
    //     * Mobile No is to be transmitted.
    //     */
    //     $strMobile;
    //     /*
    //     * What type of the message that is to be sent
    //     * <ul>
    //     * <li>0:means plain text</li>
    //     * <li>1:means flash</li>
    //     * <li>2:means Unicode (Message content should be in Hex)</li>
    //     * <li>6:means Unicode Flash (Message content should be in Hex)</li>
    //     * </ul>
    //     */
    //     $strMessageType;
    //     /*
    //     * Require DLR or not *
    //     <ul>
    //     * <li>0:means DLR is not Required</li>
    //     * <li>1:means DLR is Required</li>
    //     * </ul>
    //     */
    //     $strDlr;

        
        

    //     if($strMessageType=="2" || $strMessageType=="6") {
    //         //Call The Function Of String To HEX.
    //         $strMessage = $this->sms__unicode($strMessage);
    //         try{
    //         //Smpp http Url to send sms.
    //         $live_url="http://".$host.":".$port."/bulksms/bulksms?username=".$strUserName."&password=".$strPassword."&type=".$strMessageType."&dlr=".$strDlr."&destination=".$strMobile."&source=".$strSender."&message=".$strMessage.""; $parse_url=file($live_url);
    //         echo $parse_url[0];
    //         }catch(\Throwable $e){
    //         echo 'Message:' .$e->getMessage();
    //         }
    //     }else{
    //         $strMessage=urlencode($this->strMessage);
    //         try{
    //             // http Url to send sms.
    //             $live_url="http://".$host.":".$port."/bulksms/bulksms?username=".$strUserName."&password=".$strPassword."&type=".$strMessageType."&dlr=".$strDlr."&destination=".$strMobile."&source=".$strSender."&message=".$strMessage."";
    //             $parse_url=file($live_url);
    //             echo $parse_url[0];
    //         }catch(\Throwable $e){
    //             echo 'Message:' .$e->getMessage();
    //         }
    //     }
    // }

    // private function sms__unicode($message){
    //     $hex1='';
    //     if (function_exists('iconv')) {
    //         $latin = @iconv('UTF-8', 'ISO-8859-1', $message);
    //         if (strcmp($latin, $message)) {
    //             $arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE',
    //             $message));
    //             $hex1 = strtoupper($arr['hex']);
    //         }
    //         if($hex1 ==''){
    //             $hex2='';
    //             $hex='';
    //             for ($i=0; $i < strlen($message); $i++){
    //                 $hex = dechex(ord($message[$i]));
    //                 $len =strlen($hex);
    //                 $add = 4 - $len;
    //                 if($len < 4)
    //                 {
    //                     for($j=0;$j<$add;$j++)
    //                     { 
    //                         $hex="0".$hex;
    //                     }
    //                 }
    //                 $hex2.=$hex;
    //             }
    //             return $hex2;
    //         }else{
    //             return $hex1;
    //         }
    //     }else{
    //         print 'iconv Function Not Exists !';
    //     }
    // }
}