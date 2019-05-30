<?
if($_GET['OLDDEALID']){
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	CModule::IncludeModule("crm");
	$CCrmDeal = new CCrmDeal();
	$deal = $CCrmDeal->GetByID(intval($_GET['OLDDEALID']));
	$arDealAdd = array(
		'STAGE_ID' => 'NEW',
		'CONTACT_ID' => $deal['CONTACT_ID'],
		'COMPANY_ID' => $deal['COMPANY_ID'],

		'UF_CRM_1458297033' => get_deal_user_field(intval($_GET['OLDDEALID']), 'UF_CRM_1458297033'),
		'UF_CRM_1463063042' => get_deal_user_field(intval($_GET['OLDDEALID']), 'UF_CRM_1463063042'),
		'UF_CRM_1458309033' => get_deal_user_field(intval($_GET['OLDDEALID']), 'UF_CRM_1458309033'),
		'UF_CRM_1467632197' => get_deal_user_field(intval($_GET['OLDDEALID']), 'UF_CRM_1467632197'), // кто теперь будет ответственным логистом?

		'TITLE' => $deal["TITLE"],
		"UF_CRM_1458294764" => 32,
		"TYPE_ID" => 'SERVICES',
		// 'UF_CRM_1458307321' => intval($_GET['OLDDEALID']);// deliverer
		'UF_CRM_1458309441' => intval($_GET['OLDDEALID']),// client
		);
	$newDealID = $CCrmDeal->add($arDealAdd);
    // pre($arDealAdd);
    // pre($CCrmDeal->GetByID($newDealID));
	$arUF = get_deal_user_field(intval($_GET['OLDDEALID']), 'UF_CRM_1458307321');
	$arUF[] = $newDealID;
	$arDealUpdate = array(
		'UF_CRM_1458307321' => $arUF,
		);
	$update = $CCrmDeal->update(intval($_GET['OLDDEALID']),$arDealUpdate);
    print_r($_SERVER['REDIRECT_SCRIPT_URI']);
    $url = split('[/]', $_SERVER['REDIRECT_SCRIPT_URI']);
    $newUrl = $url[0] . "//" . $url[2];
	header('Location: http://holz.kiev.ua/crm/deal/edit/'.$newDealID.'/');

}
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
	function get_deal_user_field($id, $field){

	    $arFilter = array("ID" => $id);
	    $arParams = array($field);
	    $by =Array('DATE_CREATE' => 'DESC');
	    $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
	    $res = $arRes->Fetch();
	    return isset($res[$field]) ? $res[$field] : FALSE;
	}
