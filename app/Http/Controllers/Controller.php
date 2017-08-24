<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function parseUrl(Request $request)
    {
        $url = $request->get('url');

        if ($url) {
            // $headers = ["Content-Type:multipart/form-data"];

            // initialise the curl request
            $request = curl_init($url);

            // prepare the request
            // curl_setopt($request, CURLOPT_POST, true);
            /*
            curl_setopt(
                $request,
                CURLOPT_POSTFIELDS,
                [
                    'uploaded_file' => (new \CURLFile($file_path)),
                    'account' => config('services.textkernel.account'),
                    'username' => config('services.textkernel.username'),
                    'password' => config('services.textkernel.password'),
                ]
            );
            */
            // curl_setopt($request, CURLOPT_HEADER, $headers);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

            // Send the request
            $response = curl_exec($request);

            return $response;

            $header_size = curl_getinfo($request, CURLINFO_HEADER_SIZE);
            $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

            // output the response
            $header = substr($response, 0, $header_size);
            $result = substr($response, $header_size);
            dd($response);
            if ($responseCode != Response::HTTP_OK) {
                throw new \Exception(strip_tags($result));
            }

            // destroy resources
            curl_close($request);

            return (json_encode(simplexml_load_string($result)));
        }

        abort(500);
    }
}
