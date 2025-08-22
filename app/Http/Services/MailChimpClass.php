<?php

namespace App\http\Services;

use MailchimpMarketing;

class MailChimpClass
{

    public function create_client()
    {
        $client = new MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_SERVER_PREFIX'),
        ]);

        return $client;
    }

    public function add_or_update_list_member($email_address)
    {

        $list_id = env('MAILCHIMP_AUDIENCE_ID');

        $client = $this->create_client();
        $response = $client->lists->setListMember($list_id, $email_address, [
            "email_address" => $email_address,
            "status_if_new" => "subscribed",
        ]);
    }
}
