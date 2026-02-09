<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\VerifyEmail;

class MailSingleton
{
    private static $instance = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new MailSingleton();
        }
        return self::$instance;
    }

    public function sendWelcomeEmail($user)
    {
        Mail::raw("Welcome to the Chat App, {$user->name}!", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to Workspace');
        });
    }
    public function sendVerificationEmail($user, $link)
    {
        if ($user->email) {
            
            Mail::to($user->email)->send(new VerifyEmail($link));
        }
}}