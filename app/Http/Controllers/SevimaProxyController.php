<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SevimaService;

class SevimaProxyController extends Controller
{
    public function handle($path, Request $request, SevimaService $sevima)
    {
        $method = $request->method();

        // build query string
        $query = $request->getQueryString();
        $endpoint = $path . ($query ? '?' . $query : '');

        if ($method === 'POST') {
            $response = $sevima->post($endpoint, $request->all());
        } else {
            $response = $sevima->get($endpoint);
        }

        return response()->json($response);
    }
}