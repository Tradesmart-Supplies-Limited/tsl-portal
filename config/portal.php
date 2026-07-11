<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Complaint notification recipients
    |--------------------------------------------------------------------------
    | Comma-separated list of email addresses that get CC'd on every new
    | complaint submission, in addition to whoever it ends up assigned to.
    | Set in .env: COMPLAINT_NOTIFICATION_EMAILS=ops@company.com,manager@company.com
    */
    'complaint_notification_emails' => array_filter(array_map(
        'trim',
        explode(',', env('COMPLAINT_NOTIFICATION_EMAILS', ''))
    )),

    /*
    |--------------------------------------------------------------------------
    | Report notification recipients
    |--------------------------------------------------------------------------
    | Comma-separated list of email addresses that get notified on every
    | report upload, in addition to whichever users/staff were selected.
    | Set in .env: REPORT_NOTIFICATION_EMAILS=ops@company.com
    */
    'report_notification_emails' => array_filter(array_map(
        'trim',
        explode(',', env('REPORT_NOTIFICATION_EMAILS', ''))
    )),

];
