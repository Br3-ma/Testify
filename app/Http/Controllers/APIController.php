<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class APIController extends Controller
{

    public function route_sms_send(Request $request){
        $message = $request->input('message');
        $username = 'gtzm-mightyfin';
        $password = 'Mighty@2';
        
        $type = '5';
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
        dd($responseData);
        // ... (handle the $responseData as needed)
    
        return response()->json($responseData);
    }
    
}
