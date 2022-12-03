<?php
    namespace App\Http\Controllers;
    use \App\Models\User;     
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rules\Password;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        public function register(Request $request)
        {
            $data = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|string|unique:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers()->symbols()
                ]
            ]);
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'avatar_url' => 'default.jpg',
                'role' => 0
            ]);

            $token = $user->createToken('main')->plainTextToken;
            
            return response([
                'user' => $user,
                'token' => $token
            ]);
        }

        public function login(Request $request)
        {
            $data = $request->validate([
                'username' => 'required|string',
                'password' => 'required',
                'remember' => 'boolean'
            ]);
            echo $data;
            $remember = $data['remember'] ?? false;
            unset($data['remember']);
            if (!Auth::attempt($data,$remember)){
                return response([
                    'error' => 'Thông tin đăng nhập không chính xác'
                ],422);
            }

            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            
            return response([
                'user' => $user,
                'token' => $token
            ]);
        }

        public function logout()
        {
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            
            return response([
                'success' => true
            ]);
        }

        public function changePassword(Request $request)
        {
            
            $data = $request->validate([
                'oldPass' => 'required',
                'newPass' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers()->symbols()
                ]
            ]);
            if (!Hash::check($request->oldPass, Auth::user()->password))
            {
                return response([
                    'error' => 'Mật khẩu cũ không đúng'
                ],422);
            }

            User::whereId(Auth::user()->id)->update(['password' => bcrypt($data['newPass'])]);

            return response([
                'success' => 'Cập nhật thành công'
            ],200);
        }

        public function adminLogin(Request $request)
        {
            $data = $request->validate([
                'email' => 'required|exists:users,email',
                'password' => 'required'
            ]);

            if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 1]))
            {
                return response([
                    'error' => 'Thông tin đăng nhập không chính xác'
                ],422);
            }

            $admin = Auth::user();
            $token = $admin->createToken('main')->plainTextToken;
            
            return response([
                'adminz' => $admin,
                'token' => $token
            ]);
        }
    }

    
?>
