<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use App\Notifications\VerifyEmailCustom;

class MailController extends Controller
{
    public function notice()
    {
        return view('mail.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // ハッシュが正しいか確認
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification link.');
        }

        // まだ認証していない場合は認証済みにする
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Auth::login($user);

        return to_route('index', ['tab' => 'mylist'])->with('status', 'メールアドレス認証が完了しました。');
    }

    // 未ログイン時の再送信
    public function sendForGuest(Request $request)
    {
        // ログイン時に一時保存したメールアドレスを取り出す
        $email = $request->session()->get('unverified_email');

        if (!$email) {
            return back()->with('status', 'セッションが切れています。再度ログインしてください。');
        }

        $user = User::where('email', $email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return back()->with('status', 'このメールアドレスはすでに認証済みです。');
        }

        $user->notify(new VerifyEmailCustom());

        return back()->with('status', '認証メールを再送しました。');
    }

    // ログイン時の再送信
    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', '認証メールを再送しました。');
    }
}
