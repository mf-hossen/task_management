<?php
namespace App;

class Utility
{
    public static function test()
    {
        return true;
    }

    public static function postToSlack($text, $username = null, $options = [])
    {
        $data = [
            'api_key' => "jsfi3734lkas29-sdjf28lasdkj2s-2348asaksdjr2",
            'service_id' => "webdesigner-task-management-tools-01",
            'payload' => [
                'method' => 'post',
                'endpoint' => 'https://hooks.slack.com/services/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',
                'content' => [
                    'text' => $text
                ],
                'content_type' => 'application/json'
            ]
        ];

        if ($username) {
            $data['payload']['content']['channel'] = '@' . $username;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-core-dot-previewtechs-gc-us-central-1a.appspot.com/v1/webhook_data/queue",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}