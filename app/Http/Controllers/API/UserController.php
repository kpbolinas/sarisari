<?php

namespace App\Http\Controllers\API;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ProfileResource;
use App\Jobs\DeleteAccountJob;
use App\Jobs\ForgotPasswordJob;
use App\Jobs\RegistrationJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class UserController extends Controller
{
    /**
     * Register api
     *
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data += [
                'role' => UserRole::Customer->value,
                'activated' => UserStatus::Active->value,
            ];
            $user = User::create($data);

            if ($user) {
                $mailData = [
                    'email' => $user->email,
                    'username' => $user->username,
                    'name' => $user->first_name . ' ' . $user->last_name,
                ];
                RegistrationJob::dispatch($mailData);
                DB::commit();

                return response()->respondSuccess([], 'Registration successful.');
            }
            DB::rollBack();

            return response()->respondBadRequest([], 'Registration failed.');
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Login api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            if (
                Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                    'activated' => UserStatus::Active->value,
                ])
            ) {
                $user = $request->user();
                PersonalAccessToken::where('tokenable_id', $user->id)->delete();
                $data = [
                    'token' => $user->createToken('SariSariStore')->plainTextToken,
                    'role' => $user->role,
                ];

                return response()->respondSuccess($data, 'Login successful.');
            }

            return response()->respondBadRequest([], 'Invalid email or password. Please try again.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Logout api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $accessToken = $request->bearerToken();

            if ($accessToken) {
                $token = PersonalAccessToken::findToken($accessToken);

                if ($token && $token->delete()) {
                    return response()->respondSuccess([], 'Logout successful.');
                }
            }

            return response()->respondBadRequest([], 'Something went wrong.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Forgot password api
     *
     * @param \App\Http\Requests\ForgotPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::where(['email' => $request->email])->first();

            if ($user) {
                $tempPass = Str::random(10);
                $user->password = Hash::make($tempPass);

                if ($user->save()) {
                    $mailData = [
                        'email' => $user->email,
                        'username' => $user->username,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'temporary_password' => $tempPass,
                    ];
                    ForgotPasswordJob::dispatch($mailData);
                    DB::commit();

                    return response()->respondSuccess([], 'Email sent.');
                }
                DB::rollBack();

                return response()->respondBadRequest([], 'Something went wrong. Unable to send email.');
            }
            DB::rollBack();

            return response()->respondBadRequest([], 'Something went wrong. Unable to send email.');
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Profile api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $userProfile = new ProfileResource($request->user());

        return response()->respondSuccess($userProfile, 'Okay.');
    }

    /**
     * Change password api
     *
     * @param \App\Http\Requests\ChangePasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();
            $user->password = Hash::make($data['new_password']);
            $user->save();

            return response()->respondSuccess([], 'Change password successful.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Update info api
     *
     * @param \App\Http\Requests\UpdateInfoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(UpdateInfoRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();
            $user->username = $data['username'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->save();

            return response()->respondSuccess([], 'Update info successful.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Delete api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        DB::beginTransaction();

        try {
            if ($user->role === UserRole::SuperAdmin->value) {
                return response()
                    ->respondBadRequest([], 'Unable to perform request.');
            }
            $user->activated = UserStatus::Inactive->value;

            if ($user->save() && $user->delete()) {
                $accessToken = $request->bearerToken();

                if ($accessToken) {
                    $token = PersonalAccessToken::findToken($accessToken);

                    if ($token && $token->delete()) {
                        $mailData = [
                            'email' => $user->email,
                            'username' => $user->username,
                            'name' => $user->first_name . ' ' . $user->last_name,
                        ];
                        DeleteAccountJob::dispatch($mailData);
                        DB::commit();
    
                        return response()->respondSuccess([], 'Account has been permanently deleted.');
                    }
                }
            }
            DB::rollBack();

            return response()->respondBadRequest([], 'Something went wrong.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->respondInternalServerError([], $th->getMessage());
        }
    }
}
