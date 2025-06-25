<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Multitenancy\Models\Tenant;

/**
 * @group Authentication
 *
 * 管理用戶註冊、登入和登出。
 */
class AuthController extends Controller
{
    /**
     * 用戶註冊和租戶創建
     *
     * 註冊一個新用戶並同時為其創建一個新的租戶。
     *
     * @unauthenticated
     * @bodyParam name string required 用戶名稱. Example: John Doe
     * @bodyParam email string required 用戶電子郵件. 必須是唯一的. Example: user@example.com
     * @bodyParam password string required 用戶密碼. 最小長度為8位, 且需要確認. Example: password
     * @bodyParam password_confirmation string required 密碼確認. 必須與密碼匹配. Example: password
     * @bodyParam tenant_name string required 租戶名稱. 必須是唯一的. Example: MyCompany
     * @bodyParam tenant_domain string required 租戶域名. 必須是唯一的. Example: mycompany.localhost:8000
     *
     * @response 201 {
     * "status": "success",
     * "message": "User registered successfully!",
     * "data": {
     * "user": {
     * "id": 1,
     * "name": "John Doe",
     * "email": "user@example.com",
     * "tenant_id": 1
     * },
     * "token": "YOUR_AUTH_TOKEN_HERE",
     * "tenant": {
     * "id": 1,
     * "name": "MyCompany",
     * "domain": "mycompany.localhost:8000"
     * }
     * }
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "email": ["The email has already been taken."],
     * "password": ["The password confirmation does not match."]
     * }
     * }
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tenant_name' => 'required|string|max:255|unique:tenants,name',
            'tenant_domain' => 'required|string|max:255|unique:tenants,domain',
        ]);

        $tenant = Tenant::create([
            'name' => $request->tenant_name,
            'domain' => $request->tenant_domain,
        ]);

        $user = $tenant->execute(function() use ($request, $tenant) {
            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
            ]);
        });
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => __('auth.registered'),
            'data' => [
                'user' => $user,
                'token' => $token,
                'tenant' => $tenant,
            ],
        ], 201);
    }

    /**
     * 用戶登入
     *
     * 使用電子郵件和密碼登入，並獲取認證 Token。
     *
     * @unauthenticated
     * @bodyParam email string required 用戶電子郵件. Example: tenant.a@example.com
     * @bodyParam password string required 用戶密碼. Example: password
     *
     * @response 200 {
     * "status": "success",
     * "message": "User logged in successfully!",
     * "data": {
     * "user": {
     * "id": 1,
     * "name": "Tenant A User",
     * "email": "tenant.a@example.com",
     * "tenant_id": 1
     * },
     * "token": "YOUR_AUTH_TOKEN_HERE",
     * "tenant": {
     * "id": 1,
     * "name": "Tenant A",
     * "domain": "tenant-a.localhost:8000"
     * }
     * }
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "email": ["The provided credentials do not match our records."]
     * }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }
        
        # Find tenant and set context
        $tenant = Tenant::find($user->tenant_id);
        if (! $tenant) {
            throw ValidationException::withMessages([
                'email' => [__('auth.tenant_not_found')],
            ]);
        }
        $tenant->makeCurrent();


        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => __('auth.loggedin'),
            'data' => [
                'user' => $user,
                'token' => $token,
                'tenant' => $tenant,
            ],
        ]);
    }

    /**
     * 用戶登出
     *
     * 使當前用戶的認證 Token 失效。
     *
     * @authenticated
     *
     * @response 200 {
     * "status": "success",
     * "message": "Logged out successfully!"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('auth.loggedout'),
        ]);
    }

    /**
     * 獲取當前認證用戶
     *
     * 獲取當前已認證用戶的資料。
     *
     * @authenticated
     *
     * @response 200 {
     * "status": "success",
     * "data": {
     * "id": 1,
     * "name": "Tenant A User",
     * "email": "tenant.a@example.com",
     * "tenant_id": 1
     * }
     * }
     */
    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }
}
