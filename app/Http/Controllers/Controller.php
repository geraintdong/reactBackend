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
            // initialise the curl request
            $request = curl_init($url);

            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

            // Send the request
            $response = curl_exec($request);

            $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

            if ($responseCode != Response::HTTP_OK) {
                abort($responseCode);
            }

            return $response;
        }

        abort(500);
    }
}
