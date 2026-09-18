<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait MailService
{
    /* Don't change this - Start section */
    public function sendRawEmail(string $to, string $subject, string $message): void
    {
        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)->subject($subject);
        });
    }

    public function sendHtmlEmail(string $to, string $subject, string $html): void
    {
        Mail::send([], [], function ($message) use ($to, $subject, $html) {
            $message->to($to)->subject($subject)->html($html);
        });
    }

    /* Don't change this - End section */

    public function sendOtpEmail(string $to, string $otp): void
    {
        $subject = 'Your Registration OTP';
        $message = "Your Registration OTP is: {$otp}";
        $this->sendRawEmail($to, $subject, $message);
    }

    public function sendHtmlEmailOtp($user, string $otp, int $expiryMinutes = 3): void
    {
        $subject = 'Your OTP Code is';
        $html = "
            <p>Dear {$user->name},</p>
            <p>Your OTP is <strong>{$otp}</strong>.</p>
            <p>This code will expire in {$expiryMinutes} minutes.</p>
            <p>If you did not request this, please ignore this email.</p>
        ";
        $this->sendHtmlEmail($user->email, $subject, $html);
    }

    public function sendLoginOtpEmail(string $to, string $otp): void
    {
        $subject = 'Your Login OTP';
        $message = "Your Login OTP is: {$otp}";
        $this->sendRawEmail($to, $subject, $message);
    }

    public function sendMailableEMail(string $to, $mailable): void
    {
        Mail::to($to)->send($mailable);
    }

    public function accountActivationEmail($user): void
    {
        $subject = 'Your Account is Now Active';
        $html = "
            <p>Dear {$user->name},</p>
            <p>We’re happy to let you know that your account has been <strong>activated</strong> successfully.</p>
            <p>You can now log in and start using all features of our matrimony platform.</p>
            <p>Thank you for being with us!</p>
        ";
        $this->sendHtmlEmail($user->email, $subject, $html);
    }


}
