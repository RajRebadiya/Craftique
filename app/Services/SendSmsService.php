<?php

namespace App\Services;

use App\Models\OtpConfiguration;

class SendSmsService
{
    public function sendSMS($to, $from, $text, $template_id)
    {
        $otpConfig = OtpConfiguration::where('value', 1)->first();
        if ($otpConfig == null) {
            return false;
        }

        $otp = $otpConfig->type;
        $otp_class = __NAMESPACE__ . '\\OTP\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $otp)));

        if (class_exists($otp_class)) {
            return (new $otp_class)->send($to, $from, $text, $template_id);
        } else {
            return false;
        }
    }
}
