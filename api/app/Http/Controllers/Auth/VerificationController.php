<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use App\Repositories\Contracts\IUser;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
//use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUser $users)
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->users = $users;
    }

    public function verify(Request $request, User $user)
    {
        if(! URL::hasValidSignature($request)){
            return response()->json(["errors" => [
                "message" => "Invalid verification link"
            ]], 422);
        }

        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "Email address already verified"
            ]], 422);   
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = $this->users->findWhereFirst('email', $request->email);
        
        if (!$user) {
            return response()->json(["errors" => [
                "email" => "No user could be found with this email address"
            ]], 422);
        }

        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "Email address already verified"
            ]], 422);   
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => "verification link resent"]);
    }
}
