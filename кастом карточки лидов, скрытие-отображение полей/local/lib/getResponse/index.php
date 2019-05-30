<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$getresponse = new GetResponse('d46f767e7d719298f0bbeda0288fb462');

$email = 'elvvus@yandex.ua';
$messageId = 'PUKZj'; //'PAkRs';//'Pbe9Q';

// $arContacts = (array)$getresponse->getContacts(array(
//     'query' => array(
//         'email' => $email,
//     ),
//     'fields' => 'name,email'
// ));

// $contact = (array)$arContacts[0];

// $messageId = 'PAkRs';//'Pbe9Q';
$contactID = 'qs0RRD';//$contact['contactId']; // 'qs0RRD';//
$param = array(
    "messageId" => $messageId,
    "sendSettings" => array(
        "selectedContacts" => array($contactID)
        )
    );
$result = $getresponse->sendDraftNewsletter($param);
pre($result);



///////////////////////////////////
// $api_key = 'd46f767e7d719298f0bbeda0288fb462';
// $url = 'https://api.getresponse.com/v3/newsletters/?query[type]=draft'; //uWupi'; newsletters/send-draft
// $options = array(
//     CURLOPT_URL => $url,
//     CURLOPT_ENCODING => 'gzip,deflate',
//     CURLOPT_FRESH_CONNECT => 1,
//     CURLOPT_RETURNTRANSFER => 1,
//     CURLOPT_TIMEOUT => 8,
//     CURLOPT_HEADER => false,
//     CURLOPT_USERAGENT => 'PHP GetResponse client 0.0.2',
//     CURLOPT_HTTPHEADER => array('X-Auth-Token: api-key ' . $api_key, 'Content-Type: application/json'),
//     // CURLOPT_POST => true,
//     // CURLOPT_POSTFIELDS => $params
// );
// $curl = curl_init();
// curl_setopt_array($curl, $options);


// $response = json_decode(curl_exec($curl), 1);
// pre($response);
//////////////////////////////////////

        // if ($http_method == 'POST') {
        //     $options[CURLOPT_POST] = 1;
        //     $options[CURLOPT_POSTFIELDS] = $params;
        // } else if ($http_method == 'DELETE') {
        //     $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        // }

// pre($response['content']['html']);

// $result = $getresponse->sendNewsletter(array(
//                 "subject" => 'Test subject',
//                 "fromField" => array('fromFieldId' => 'pQsIl'),
//                 "campaign" => array('campaignId' => 'p0C0Y'),
//                 "content" => array(
//                     'html' => $response['content']['html']
//                 ),
//                 "sendSettings" => array(
//                     "selectedContacts" => array($contactID)
//             )
//     ));
// pre($result);
// die();
// echo "string";

// $result = (array)$getresponse->getContacts(null);
// pre($params);
// echo "string";
// $params = array(
//     // 'subject' => 'test',
//     // 'fromField' => '',
//     // 'fromFieldId' => '',
//     // '' => '',
//     );
// $params = json_encode($params);
// pre($params);

// $result = $getresponse->addContact(array(
//     'name'              => 'Vicktor',
//     'email'             => 'vicktor.siryack@gmail.com',
//     'dayOfCycle'        => 0,
//     'campaign'          => array('campaignId' => 'p0C0Y'),
//     // 'ipAddress'         => '89.206.31.190',
//     // 'customFieldValues' => array(
//     //     array('customFieldId' => 'custom_field_id_obtained_by_API',
//     //         'value' => array(
//     //             'Y'
//     //         )),
//     //      array('customFieldId' => 'custom_field_id_obtained_by_API',
//     //         'value' => array(
//     //             'Y'
//     //         ))
//     // )
// ));
// $getresponse->enterprise_domain = 'holz.kiev.ua';
// $result = $getresponse->ping();
// $result = $getresponse->sendNewsletter(array(
//                 "subject" => 'Test subject',
//                 "fromField" => array('fromFieldId' => 'from_field_id'),
//                 "content" => array(
//                     'html' => 'Test newsletter contetnt.'
//                 ),
//                 "sendSettings" => array(
//                     "selectedContacts" => array('contact_id_obtained_by_API')
//             )
//     ));
// // Connection Testing
// $details = $api->getAccountInfo();
// pre($result);

// $api_key = 'd46f767e7d719298f0bbeda0288fb462';
// $url = 'https://api.getresponse.com/v3/newsletters/'; //uWupi';
// $params = http_build_query(array(
//     'messageId' => '',
//     'sendSettings' => array(
//         'selectedContacts' => array(
//             $contactID
//             )
//         )
//     ));
///////////////
// $api_key = 'd46f767e7d719298f0bbeda0288fb462';
// $url = 'https://api.getresponse.com/v3/newsletters/Pbe9Q'; //uWupi'; newsletters/send-draft
// $options = array(
//     CURLOPT_URL => $url,
//     CURLOPT_ENCODING => 'gzip,deflate',
//     CURLOPT_FRESH_CONNECT => 1,
//     CURLOPT_RETURNTRANSFER => 1,
//     CURLOPT_TIMEOUT => 8,
//     CURLOPT_HEADER => false,
//     CURLOPT_USERAGENT => 'PHP GetResponse client 0.0.2',
//     CURLOPT_HTTPHEADER => array('X-Auth-Token: api-key ' . $api_key, 'Content-Type: application/json'),
//     // CURLOPT_POST => true,
//     // CURLOPT_POSTFIELDS => $params
// );

//         // if ($http_method == 'POST') {
//         //     $options[CURLOPT_POST] = 1;
//         //     $options[CURLOPT_POSTFIELDS] = $params;
//         // } else if ($http_method == 'DELETE') {
//         //     $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
//         // }

// $curl = curl_init();
// curl_setopt_array($curl, $options);

// $response = json_decode(curl_exec($curl));
// pre($response);
/////////////////








//         // $this->http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

//         curl_close($curl);
// $result = $getresponse->sendNewsletter(array(
//                 "subject" => 'Test subject',
//                 // "fromField" => array('fromFieldId' => 'from_field_id'),
//                 "content" => array(
//                     'html' => 'Test newsletter contetnt.'
//                 ),
//                 "sendSettings" => array(
//                     "selectedContacts" => array($contactID)
//             )
//     ));
// pre($result);
