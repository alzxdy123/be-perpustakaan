<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index() {
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }

    public function register(UserRequest $request) {
        $userRole = Role::where('name', 'user')->first();

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password_confirmation,
            'role_id' => $userRole->id
        ]);

        return response()->json(['data' => $user], 201);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if(!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Kredensial yang anda masukkan salah'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $role = Role::find($user->role_id);

        $customClaims = [
            'username' => $user->username,
            'email' => $user->email,
            'role' => $role->name,
        ];
    
        $token = JWTAuth::claims($customClaims)->fromUser($user);

        return response()->json(['token' => $token], 200);
    }

    public function me() {
        $authUser = auth()->user();

        $user = User::with('role:id,name', 'profile')->find($authUser->id);

        return response()->json(['data' => $user], 200);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'Logout berhasil'], 200);
    }

    public function profile(ProfileRequest $request) {
        $authUser = auth()->user();
    
        if (!$authUser) {
            return response()->json(['message' => 'Pengguna tidak terautentikasi'], 401);
        }
    
        $profile = Profile::where('user_id', $authUser->id)->first();
        $avatarData = $profile->avatar ?? null;
    
        if ($request->hasFile('avatar')) {
            if ($avatarData) {
                $oldAvatarPath = 'avatars/' . basename($avatarData);
                if (Storage::disk('public')->exists($oldAvatarPath)) {
                    Storage::disk('public')->delete($oldAvatarPath);
                }
            }
    
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->extension();
            $avatar->storeAs('avatars', $avatarName, 'public');
            $avatarData = Storage::url('avatars/' . $avatarName);
        } elseif ($request->get('avatar') === null && $avatarData) {
            $oldAvatarPath = 'avatars/' . basename($avatarData);
            if (Storage::disk('public')->exists($oldAvatarPath)) {
                Storage::disk('public')->delete($oldAvatarPath);
            }
            $avatarData = null;
        }
    
        $profileData = $request->only(['age', 'address', 'bio']);
        $profileData['avatar'] = $avatarData;
    
        $profile = Profile::updateOrCreate(
            ['user_id' => $authUser->id],
            $profileData
        );
    
        return response()->json(['data' => $profile], 200);
    }
    
    public function users() {
        $users = User::all();

        return response()->json(['data' => $users]);
    }
    
}
