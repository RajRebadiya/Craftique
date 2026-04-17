<?php

return [
    'enable_tracking' => env('AC_ENABLE_TRACKING', 'true'),
    'notify_admin_on_recovery' => env('AC_NOTIFY_ADMIN_ON_RECOVERY', 'true'),
    'send_recovery_report' => env('AC_SEND_RECOVERY_REPORT', 'true'),
    'cut_of_time_in_minutes' => env('AC_CUT_OF_TIME_IN_MINUTES', '300'),
    'email_from_name' => env('AC_EMAIL_FROM_NAME', 'Active Ecommerce'),
    'email_from_address' => env('AC_EMAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
    'email_reply_to_address' => env('AC_EMAIL_REPLY_TO_ADDRESS', env('MAIL_FROM_ADDRESS')),
    'recovery_report_to_email' => env('AC_RECOVERY_REPORT_TO_EMAIL', env('MAIL_FROM_ADDRESS')),
];
