<?php

namespace App\Http\Controllers;

use App\Services\SevimaService;
use Illuminate\Http\Request;

class SevimaController extends Controller
{
    public function __construct(
        protected SevimaService $sevima
    ) {
    }

    public function login(Request $request)
    {
        $response = $this->sevima->post(
            'siakadcloud/v1/user/login',
            [
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        return response()->json($response);
    }

    public function mahasiswa(Request $request)
    {
        $response = $this->sevima->get(
            'siakadcloud/v1/mahasiswa',
            $request->query()
        );

        return response()->json($response);
    }
}
