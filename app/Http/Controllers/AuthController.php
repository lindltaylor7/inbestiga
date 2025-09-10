<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and return an access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->with(['roles', 'roles.permissions', 'permissions', 'images'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' =>  $user->createToken($request->device_name)->plainTextToken,
            'area' => $user->subarea->area
        ]);
    }
    /**
     * Handle customer login and return an access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->with(['quotations', 'quotations.order', 'quotations.contract', 'quotations.order.projects', 'quotations.contract.projects'])->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        return response()->json([
            'user' => $customer,
            'token' => $customer->createToken($request->device_name)->plainTextToken
        ]);
    }
    /**
     * Handle user logout and revoke the current access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'msg' => 'Logout successfull'
        ]);
    }
    /**
     * Handle customer logout and revoke the current access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logoutCustomer(Request $request)
    {
        return $request;
        $request->customer()->currentAccessToken()->delete();
        return response()->json([
            'msg' => 'Logout successfull'
        ]);
    }
}
