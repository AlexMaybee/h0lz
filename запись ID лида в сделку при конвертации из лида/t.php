<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*$getresponse = new GetResponse('d46f767e7d719298f0bbeda0288fb462');
$getresponse->enterprise_domain = 'holz.kiev.ua';
// $result = $getresponse->ping();
// // Connection Testing
// $details = $api->getAccountInfo();
pre($getresponse);
echo "string";*/
//echo 1111;
 CModule::IncludeModule("crm");

// // $rootActivity = $this->GetRootActivity();

// // $leadID = $rootActivity->GetVariable("leadID");
// $leadID = 14;
// $lead = CCrmLead::GetByID($leadID);
// if(intval(substr($lead['DATE_CREATE'], 11, 2)) >= 17){
//     $ech = 1;

// }else{
//     $ech = 0;
// }



// $clientDealID = get_deal_user_field(259, 'UF_CRM_1458309441')[0];

// pre($clientDealID);
// $ar1 = array(270);
// $ar2 = array(270, 261, 268);

// pre(array_diff($ar2, $ar1));

// $devDealPool = get_deal_user_field($clientDealID, 'UF_CRM_1458307321');
// $redyDevDealPool = get_deal_user_field($clientDealID, 'UF_CRM_1463476129');



// pre($devDealPool);
// pre($redyDevDealPool);

##################################################################################################################

// $rootActivity = $this->GetRootActivity();

// $dealID = $rootActivity->GetVariable("dealID");

// $clientDealID       = get_deal_user_field($dealID, 'UF_CRM_1458309441')[0];     // идшник сделки с клиентом
// $redyDevDealPool    = get_deal_user_field($clientDealID, 'UF_CRM_1463476129');  // пул готовых сделок с поставщиком

//     if(!in_array($dealID, $redyDevDealPool)){

//         $redyDevDealPool[] = intval($dealID);

//         $arDealUpdateFields = array();
//         $arDealUpdateFields = array(
//             'UF_CRM_1463476129' => $redyDevDealPool,
//             );
//         $dealUpdate = $CCrmDeal->Update($clientDealID, $arDealUpdateFields);

//     }

//     $devDealPool        = get_deal_user_field($clientDealID, 'UF_CRM_1458307321');  // пул сделок с поставщиком
//     $redyDevDealPool    = array();
//     $redyDevDealPool    = get_deal_user_field($clientDealID, 'UF_CRM_1463476129');  // пул готовых сделок с поставщиком

//     if(count(array_diff($devDealPool, $redyDevDealPool)) == 0){             // если все сделки имеют правельную стадию

//         CModule::IncludeModule("bizproc");
//         $arDU = array(
//             'STAGE_ID' => 6,
//             );

//         CBPDocument::StartWorkflow(
//              13,
//              array("crm","CCrmDocumentDeal","DEAL_".$clientDealID),
//           array(),
//              $arErrors
//          );
//         file_put_contents($_SERVER["DOCUMENT_ROOT"]."/A.txt", "\n\n arErrors1 \n\n", FILE_APPEND);
//         file_put_contents($_SERVER["DOCUMENT_ROOT"]."/A.txt", print_r($arErrors, 1), FILE_APPEND);
//         CBPDocument::StartWorkflow(
//              14,
//              array("crm","CCrmDocumentDeal","DEAL_".$clientDealID),
//           array(),
//              $arErrors
//          );
//         file_put_contents($_SERVER["DOCUMENT_ROOT"]."/A.txt", "\n\n arErrors2 \n\n", FILE_APPEND);
//         file_put_contents($_SERVER["DOCUMENT_ROOT"]."/A.txt", print_r($arErrors, 1), FILE_APPEND);
//         $CCrmDeal->Update($clientDealID, $arDU);
//     }


// function get_deal_user_field($id, $field){

//     $arFilter = array("ID" => $id);
//     $arParams = array($field);
//     $by =Array('DATE_CREATE' => 'DESC');
//     $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
//     $res = $arRes->Fetch();
//     return isset($res[$field]) ? $res[$field] : FALSE;
// }
##################################################################################################################




#########################################################################################################

// pre('eswfswef');
// $rootActivity = $this->GetRootActivity();

// $dealID     = $rootActivity->GetVariable("dealID");
// $number     = get_deal_user_field_HOMA_3($dealID, 'UF_CRM_1458295326'); // номер заказа
// $termin     = get_deal_user_field_HOMA_3($dealID, 'UF_CRM_1458297475'); // сроки
// $delivery   = get_deal_user_field_HOMA_3($dealID, 'UF_CRM_1458297887'); // доставка
// $montage    = get_deal_user_field_HOMA_3($dealID, 'UF_CRM_1458298249'); // монтаж
// $template   = $rootActivity->GetVariable("tip_shablona"); // номер шаблона (где 0 = 1)
// $deal       = CCrmDeal::GetByID($dealID);
// $contactID  = $deal['CONTACT_ID'];
// $phone      = getPhoneByContactID_3($contactID); //'380665504276'; //$rootActivity->GetVariable("phone"); // телефон
// $arTempletes = array(
//     '«Добрый день, Ваш заказ № '.$number.' в работе.  Спасибо, что выбрали нашу компанию. С ув. Хольц) ул Новоконстантиновская 2 А с 10.00-21.00(двери-ламинат-паркет)',
//     '«Добрый день. Ваш заказ №'.$number.'. Будет готов к отгрузке в оговоренные сроки '.$termin.'. Благодарим за Ваш выбор. С ув. Хольц  ул Новоконстантиновская 2 А с 10.00-21.00 (двери-ламинат-паркет)',
//     '«Добрый день. Вам будут предоставлены сервисные услуги Доставка '.$delivery.'. Монтаж '.$montage.'.  С ув. Хольц  ул Новоконстантиновская 2 А с 10.00-21.00(двери-ламинат-паркет)'
//     );


// $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

// $auth = [
//     'login' => 'servicecdoors',
//     'password' => '18072011'
// ];

// $result = $client->Auth($auth);

// $text = iconv('windows-1251', 'utf-8', $arTempletes[$template]);

// $sms = [
//     'sender' => 'HOLZ',
//     'destination' => '+'.$phone,//'380665504276',
//     'text' => $arTempletes[$template]
// ];
// $result = $client->SendSMS($sms);

// function get_deal_user_field_HOMA_3($id, $field){

//     $arFilter = array("ID" => $id);
//     $arParams = array($field);
//     $by =Array('DATE_CREATE' => 'DESC');
//     $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
//     $res = $arRes->Fetch();
//     return isset($res[$field]) ? $res[$field] : FALSE;
// }

// function getPhoneByContactID_3($id){
//     $phoneInfo = CCrmFieldMulti::GetList(array(), array('ENTITY_ID' => 'CONTACT', 'ELEMENT_ID' => $id) );

//     while ($ar = $phoneInfo->Fetch()){
//         $phoneNumber = $ar['VALUE'];
//     }
//     return $phoneNumber;
// }

#######################################################################################
// $rootActivity = $this->GetRootActivity();

// $dealID = $rootActivity->GetVariable("dealID");
// $number = get_deal_user_field_HOMA($dealID, ''); // номер заказа
// $termin = get_deal_user_field_HOMA($dealID, ''); // сроки
// $delivery = get_deal_user_field_HOMA($dealID, ''); // доставка
// $montage = get_deal_user_field_HOMA($dealID, ''); // монтаж
// $phone = $rootActivity->GetVariable("phone"); // телефон
// $template = $rootActivity->GetVariable("tip_shablona"); // номер шаблона (где 0 = 1)

// $arTempletes = array(
//     '«Добрый день, Ваш заказ № '.$number.' в работе.  Спасибо, что выбрали нашу компанию. С ув. Хольц) ул Новоконстантиновская 2 А с 10.00-21.00(двери-ламинат-паркет)',
//     '«Добрый день. Ваш заказ №'.$number.'. Будет готов к отгрузке в оговоренные сроки '.$termin.'. Благодарим за Ваш выбор. С ув. Хольц  ул Новоконстантиновская 2 А с 10.00-21.00 (двери-ламинат-паркет)',
//     '«Добрый день. Вам будут предоставлены сервисные услуги Доставка '.$delivery.'. Монтаж '.$montage.'.  С ув. Хольц  ул Новоконстантиновская 2 А с 10.00-21.00(двери-ламинат-паркет)'
//     );


// $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

// $auth = [
//     'login' => 'servicecdoors',
//     'password' => '18072011'
// ];

// $result = $client->Auth($auth);

// $text = iconv('windows-1251', 'utf-8', $arTempletes[$template]);
// //$text = 'test';//iconv('windows-1251', 'utf-8', 'Тест');

// $sms = [
//     'sender' => 'HOLZ',
//     'destination' => '+'.$phone,//'380665504276',
//     'text' => $arTempletes[$template]
// ];
// $result = $client->SendSMS($sms);

// function get_deal_user_field_HOMA($id, $field){

//     $arFilter = array("ID" => $id);
//     $arParams = array($field);
//     $by =Array('DATE_CREATE' => 'DESC');
//     $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
//     $res = $arRes->Fetch();
//     return isset($res[$field]) ? $res[$field] : FALSE;
// }

#######################################################################################
// function pre($val){
// 	if(!$val){
// 		echo "<pre>";
// 		var_dump($val);
// 		echo "</pre>";
// 	}else{
// 		echo "<pre>";
// 		print_r($val);
// 		echo "</pre>";
// 	}
// }
// $input  = strtotime('28.04.2016 10:20');

// $inputTime  = date('H:i', $input);

// $inputTimeArray  = explode(":", $inputTime);

// if ($inputTimeArray[0] < 10)
// 	pre("еще до 10 утра");
// elseif ($inputTimeArray[0] == 10 && $inputTimeArray[1] < 30)
// 	pre("сейчас от 10:00 до 10:30 утра");
// else
// 	pre("задачу не ставим");

$arFields = [
    'TITLE' => 'ТЕСТОВЫЙ ЛИД К',
    'OPPORTUNITY' => 0.00,
    'CURRENCY_ID' => 'UAH',
    'COMMENTS' => '',
    'OPENED' => 'Y',
    'ASSIGNED_BY_ID' => 1,
    'LEAD_ID' => 33344556,
];
if(in_array('LEAD_ID',$arFields)) echo 'Lead est!';

$deals_filter = [
    'ID' => 10461,
];
$deals_select = Array('ID','TITLE','UF_CRM_1554473020','ASSIGNED_BY_ID','LEAD_ID');
$dealsRes = getDealDataByFilter($deals_filter,$deals_select);

echo '<pre>';
//print_r($arFields);
print_r($dealsRes);
echo '</pre>';


function getDealDataByFilter($arFilter,$arSelect){
    $deals = [];
    $db_list = CCrmDeal::GetListEx(Array("ID" => "ASC"), $arFilter, false, false, $arSelect, array()); //получение пользовательских полей сделки по ID
    while($ar_result = $db_list->GetNext()){
        //$ar_result['HREF'] = '/crm/deal/details/'.$ar_result['ID'].'/'; //формируем ссылку для открытия во фрейме сделки
        $deals[] = $ar_result;
    }
    return $deals;
}