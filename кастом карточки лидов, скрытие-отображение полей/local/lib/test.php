<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
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

CModule::IncludeModule('crm');


function get_deal_user_field_ELVIRA_DEAL_2($id, $field){

    $arFilter = array("ID" => $id);
    $arParams = array($field);
    $by =Array('DATE_CREATE' => 'DESC');
    $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
    $res = $arRes->Fetch();
    return isset($res[$field]) ? $res[$field] : FALSE;
}


$CCrmCompany = new CCrmCompany();
$c = $CCrmCompany->GetByID(get_deal_user_field_ELVIRA_DEAL_2(94, 'COMPANY_ID'));

$providerDealIDs = get_deal_user_field_ELVIRA_DEAL_2(93, 'UF_CRM_1458307321');

$actual_link = "http://$_SERVER[SERVER_NAME]/crm/deal/show/94/";

pre($actual_link);