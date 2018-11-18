<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use Validator;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;

    class UserController extends Controller
    {
        public function login(Request $request)
        {
            $credentials = [
                'email' => $request['email'],
                'password' => $request['password']
            ];

            try {
                if (Auth::attempt($credentials)) {
                    $token = Auth::user()->createToken('MyApp')->accessToken;

                    return response()->json([
                        'result' => true,
                        'message' => 'Successfuly logged in.',
                        'token' => $token,
                    ], Response::HTTP_OK);
                }

                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'message' => 'Unable to Log In. Invalid credentials.',
                ], Response::HTTP_UNAUTHORIZED);

            } catch (\Exception $err) {
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'message' => 'Unable to Log In. Please try again later.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:40',
                'email' => 'required|email',
                'password' => 'required|max:40',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'message' => $validator->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $data = $request->all();
            $data['password'] = bcrypt($data['password']);

            try {
                $user = User::create($data);
                $token = $user->createToken('MyApp')->accessToken;
            } catch (\Exception $err) {
                $emailTaken = strpos($err->getMessage(), 'Duplicate entry');
                $message = 'Error while creating new user.';
                if ($emailTaken != false) {
                    $message = 'Duplicate entry. Please use another name or email.';
                } else {
                    $message = 'Consumer not saved. Please try again later.';
                }
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'message' => $message,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $userData['name'] = $data['name'];
            $userData['email'] = $data['email'];
            $userData['password'] = '******';

            return response()->json([
                'result' => true,
                'user' => $userData,
                'message' => 'User \''. $userData['name'] . '\' successfuly created.',
                'token' => $token,
            ], Response::HTTP_OK);
        }

        public function getDetails()
        {
            return response()->json(['success' => Auth::user()]);
        }
    }
