<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\ApiController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends ApiController
{
    use SendsPasswordResetEmails;

    /** Return a reset token to given user */
    public function getResetToken(Request $request)
    {
        $this->validateEmail($request);

        $user = $this->broker()->getUser($request->only('email'));
        if (!$user) {
            return response()->json(['message' => trans('passwords.user')], 400);
        } else {
            $token = $this->broker()->createToken($user);
            return response()->json(['token' => $token]);
        }
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return response()->json(['message' => trans($response)]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Rest Link Request Failed',
            'errors' => [
                'email' => trans($response)
            ]
        ], 422);
    }
}