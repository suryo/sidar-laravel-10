<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login - Authenticate employee and return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if employee is active
        if ($employee->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is not active.'],
            ]);
        }

        // Create token
        $token = $employee->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'employee' => new EmployeeResource($employee),
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Logout - Revoke current token
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ], 200);
    }

    /**
     * Me - Get authenticated employee profile
     */
    public function me(Request $request)
    {
        $employee = $request->user();
        
        // Load relationships
        $employee->load([
            'department',
            'division',
            'location',
            'supervisor',
            'manager',
            'director',
            'owner',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => new EmployeeResource($employee),
        ], 200);
    }
}
