<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class APIController extends Controller
{
    public function route_sms_send(Request $request){
        $this->send_with_guzzle($request);
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
        dd($responseData);
        // ... (handle the $responseData as needed)
    
        return response()->json($responseData);
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
            dd($responseData);
            // ... (handle the $responseData as needed)
            return response()->json($responseData);
        } catch (RequestException $e) {
            dd($e);
            // Handle Guzzle request exception
            echo 'Guzzle error: ' . $e->getMessage();
        }
    }
}