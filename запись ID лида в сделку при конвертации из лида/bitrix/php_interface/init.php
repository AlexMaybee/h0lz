<?php
// GetResponseAPI3 /////////////////////////
include ($_SERVER['DOCUMENT_ROOT'].'/local/lib/getResponse/GetResponseAPI3.class.php');
function pre($val){
  if(!$val){
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
  }else{
    echo "<pre>";
    print_r($val);
    echo "</pre>";
  }
}
////////////////////////////////////////////

AddEventHandler("crm", "OnBeforeCrmInvoiceUpdate", Array("ActivityInvoice", "OnBeforeCrmInvoiceUpdateHandler"));
class ActivityInvoice{

    function OnBeforeCrmInvoiceUpdateHandler(&$arFields){

        CModule::IncludeModule("crm");
        $CCrmInvoice = new CCrmInvoice();
        $CCrmDeal = new CCrmDeal();
        $CTasks = new CTasks();
        #########################################################################################
        // $filename = $_SERVER['DOCUMENT_ROOT'].'/txt.txt';
        // file_put_contents($filename, "\n\n+++++++ ".date("c", time())." +++++++\n\n", FILE_APPEND);
        // file_put_contents($filename, print_r($arFields,1), FILE_APPEND);
        #########################################################################################

        $invoice = $CCrmInvoice->GetByID($arFields['ID']);
        if($invoice['STATUS_ID'] =='P'){
            $responsibleId = $this->get_deal_user_field($invoice['UF_DEAL_ID'], 'UF_CRM_1461049111');

            $title = "Прозвон компании и получение подтверждения о начале работ";
            $responsibleDesign = 1;
            $descriptionDesign = 'Необходимо прозвонить компанию '.$invoice['COMPANY_ID'].' и получить подтверждение о начале работы над заказом.';
            $arTaskDesignFields = Array (
                "TITLE" => $title,
                "DESCRIPTION" => $descriptionDesign,
                "RESPONSIBLE_ID" => $responsibleId,
                "DEADLINE" => date("d.m.Y H:i:s", time() + 60*60*24),
                "UF_CRM_TASK" => array('D_'.$invoice['UF_DEAL_ID']),
            );
          $taskDesign = $CTasks->Add($arTaskDesignFields);
          #########################################################################################
          // $filename = $_SERVER['DOCUMENT_ROOT'].'/txt.txt';
          // file_put_contents($filename, "\n\n+arTaskDesignFields++++++ ".date("c", time())." +++++++\n\n", FILE_APPEND);
          // file_put_contents($filename, print_r($arTaskDesignFields,1), FILE_APPEND);
          #########################################################################################
        }
    }

    function get_deal_user_field($id, $field){

        $arFilter = array("ID" => $id);
        $arParams = array($field);
        $by =Array('DATE_CREATE' => 'DESC');
        $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
        $res = $arRes->Fetch();
        return isset($res[$field]) ? $res[$field] : FALSE;
    }
}

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

AddEventHandler("crm", "OnBeforeCrmDealUpdate", Array("DealHandler", "SetDeliveryDate"));
AddEventHandler("crm", "OnBeforeCrmDealAdd", Array("DealHandler", "SetDeliveryDate"));


class DealHandler{

    function SetResposiveLogist(&$arFields) {

        $arLogists = array ();
        switch ($arFields['UF_CRM_1458294876']){
            case '33':
                $arLogists = array (0 => '7');
                break;
            case '34':
                $arLogists = array (0 => '8');
                break;
            case '35':
                $arLogists = array (0 => '7',1 => '8');
                break;
        }
        if(count($arLogists)){
            $arLogists = array_merge ($arFields['UF_CRM_1463063042'],$arLogists);
            $arLogists = array_unique($arLogists);
            $arFields['UF_CRM_1463063042'] = $arLogists;
        }
    }

    function SetDeliveryDate(&$arFields) {
       if(isset($arFields['TYPE_ID'])){

            if($arFields['TYPE_ID']=='SERVICES'){
                $saleDeals = $arFields['UF_CRM_1458309441'];
                $ActivityInvoice = new ActivityInvoice;
                foreach($saleDeals as $key => $value){
                    // сделка с покупателем
                    $serviceDeals = $ActivityInvoice->get_deal_user_field($key,'UF_CRM_1458307321');
                    $deliveryDates = array();
                    foreach($serviceDeals as $key2 => $value2){
                        $id = (int)$value2;
                        // дата поступления на склад
                        $deliveryDates[] = $ActivityInvoice->get_deal_user_field($id,'UF_CRM_1458297475');
                    }
                    // дата поступления на склад
                    $maxDate = strtotime($arFields['UF_CRM_1458297475']);
                   foreach($deliveryDates as $key3=>$value3){
                        if($value3 != ''){
                            $timeStamp = strtotime($value3);
                            if($timeStamp !== false and $timeStamp > $maxDate){
                                $maxDate = $timeStamp;
                            }

                        }
                    }
                    // AddMessage2Log($maxDate);
                    if($maxDate != false){
                        CModule::IncludeModule("crm");
                        $dd = date('d.m.Y H:i:s',$maxDate);
                        $CCrmDeal = new CCrmDeal();
                        $arUpdateData = array('UF_CRM_1458297475' => $dd);

                        $res = $CCrmDeal->Update((int)$key, $arUpdateData);
                    }
                }
            }
        }


    }
    function get_deal_user_field($id, $field){

        $arFilter = array("ID" => $id);
        $arParams = array($field);
        $by =Array('DATE_CREATE' => 'DESC');
        $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
        $res = $arRes->Fetch();
        return isset($res[$field]) ? $res[$field] : FALSE;
    }

    // function
}


AddEventHandler("crm", "OnAfterCrmDealUpdate", Array("AfterUpdateDealHandler", "updateStageByProvidersMin"));
class AfterUpdateDealHandler{
    function updateStageByProvidersMin(&$arFields){

        CModule::IncludeModule('crm');
        $dealID = $arFields['ID'];

        $CCrmDeal = new CCrmDeal;

        // выбираем все стадии и приводим к виду [название стадии сделки]=>[числовый идентификатор, отображающий порядковый номер сделки]
        $stages = CCrmStatus::GetStatusList('DEAL_STAGE');
        $i = 0;
        foreach ($stages as $key => $value)
        {
            $stagesForCompare[$key] = $i;
            $i += 1;
        }
        $deal = CCrmDeal::GetList([], ['ID'=>$dealID])->Fetch();
        if (count($deal['UF_CRM_1458309441'] > 0)){ // если сделка с производителем
            $clientDealID = $deal['UF_CRM_1458309441'][0];
            $clientDeal = CCrmDeal::GetList([], ['ID'=>$clientDealID])->Fetch();
            $clienDealStage = $clientDeal['STAGE_ID'];
            $providerDealIDs = $clientDeal['UF_CRM_1458307321'];

            $prviderDealStages = [];

            foreach ($providerDealIDs as $providerDealID) {
                $providerStage[$providerDealID] = CCrmDeal::GetByID($providerDealID)['STAGE_ID'];
            }

            foreach ($providerStage as $key => $value) {
                $providerStagesForCompare[$key] = $stagesForCompare[$value];
            }
            $min = min($providerStagesForCompare);

            $minStageAmongProvideDeals = array_search($min, $stagesForCompare);

            $clienDealStageCount = $stagesForCompare[$clienDealStage];
            $minStageAmongProvideDealsCount = $stagesForCompare[$minStageAmongProvideDeals];
            if ($clienDealStageCount < $minStageAmongProvideDealsCount)
            {
                $arField = ['STAGE_ID'=>$minStageAmongProvideDeals];
                $tmp = $CCrmDeal->Update($clientDealID, $arField);
            }
        }
        if (count($deal['UF_CRM_1458307321']) > 0) { // если сделка с клиентом

          $dealStage  = $deal['STAGE_ID'];
          $number     = $deal['UF_CRM_1458295326']; // номер заказа
          $termin     = substr($deal['UF_CRM_1458297475'], 0, 10); // сроки
          // $deal       = CCrmDeal::GetByID($dealID);
          $contactID  = $deal['CONTACT_ID'];
          $phone      = self::getPhoneByContactID_3($contactID); // телефон
          $arTempletes = array(
              'Добрый день, Ваш заказ №'.$number.' в работе.  Спасибо, что выбрали нашу компанию. салон HOLZ,  ул Новоконстантиновская 2 А с 10.00-21.00 (двери-ламинат-паркет)',
              'Добрый день. Ваш заказ №'.$number.'. Будет готов к отгрузке в оговоренные сроки '.$termin.'. Благодарим за Ваш выбор. салон HOLZ,  ул Новоконстантиновская 2 А с 10.00-21.00 (двери-ламинат-паркет)'
              );

          $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html',['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);

          $auth = [
              'login' => 'servicecdoors',
              'password' => '18072011'
          ];
          if ($dealStage == 3 && $deal['UF_CRM_1469779061'] == 0) {
              $result = $client->Auth($auth);

              $text = iconv('windows-1251', 'utf-8', $arTempletes[0]);

              $sms = [
                  'sender' => 'HOLZ',
                  'destination' => '+'.$phone,//'380509934165',
                  'text' => $arTempletes[0]
              ];

              $result = $client->SendSMS($sms);

              $arFields = ['UF_CRM_1469779061' => 1];
              $tmp = $CCrmDeal-> Update($deal['ID'], $arFields);


              // $filename = $_SERVER['DOCUMENT_ROOT'].'/txt.txt';
              // file_put_contents($filename, "\n\n+arTaskDesignFields++++++ ".date("c", time())." +++++++\n\n", FILE_APPEND);
              // file_put_contents($filename, print_r($arFields,1), FILE_APPEND);
          }
          elseif ($dealStage == 4 && $deal['UF_CRM_1469779076'] == 0) {
              $result = $client->Auth($auth);

              $text = iconv('windows-1251', 'utf-8', $arTempletes[1]);

              $sms = [];
              $sms = [
                  'sender' => 'HOLZ',
                  'destination' => '+'.$phone, //'380509934165',
                  'text' => $arTempletes[1]
              ];
              $result = $client->SendSMS($sms);

              $arFields = ['UF_CRM_1469779076' => 1];
              $tmp = $CCrmDeal-> Update($deal['ID'], $arFields);
              // $arFields['UF_CRM_1469779076'] = 1;
          }

        }

    }

    function getPhoneByContactID_3($id){
        $phoneInfo = CCrmFieldMulti::GetList(array(), array('ENTITY_ID' => 'CONTACT', 'ELEMENT_ID' => $id) );

        while ($ar = $phoneInfo->Fetch()){
            $phoneNumber = $ar['VALUE'];
        }
        return $phoneNumber;
    }
}

//Запись ID лида в сделку в поле типа привязка к элеименту срм
AddEventHandler("crm", "OnBeforeCrmDealAdd", "LogData");
function LogData(&$arFields){

    //$arFields['LEAD_ID'] //при конвертации в массиве будет такое поле
    //Если в массиве существует переменная с ID лида
    if(in_array('LEAD_ID',$arFields)){

        //тогда записываем ее в специально созданное поле
        $arFields['UF_CRM_1554473020'] = $arFields['LEAD_ID']; //Поле с привязкой, в отчет выдает название, а не ID лида
        $arFields['UF_CRM_1554713631'] = $arFields['LEAD_ID']; //Должно отдавать ID
    }

//    $file = $_SERVER['DOCUMENT_ROOT'].'/TestConvertDeal.log';
//    file_put_contents($file, print_r($arFields,true), FILE_APPEND);
}