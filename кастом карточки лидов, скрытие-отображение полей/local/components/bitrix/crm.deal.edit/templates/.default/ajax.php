<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule('crm');
// $filename = $_SERVER['DOCUMENT_ROOT'].'/dates.txt';

// file_put_contents($filename, "atata");


// синхронизация поля "Дата начала выполнения заказа"
if (isset($_POST['dateBegin']))
{
	//инициализируем данные
	$deal = new CCrmDeal;
	$currentDealID = $_POST['dealID'];
	$updatedDate = $_POST['dateBegin'];
	//дата для обновления
	$arFields = array('UF_CRM_1458297436' => $updatedDate);
	
	$arFilter = ['ID'=>$currentDealID];
	$currentDealInfo = CCrmDeal::GetList([], $arFilter)->Fetch();
	//ID клиента и производителей (из пользовательских полей)
	$clientID = $currentDealInfo['UF_CRM_1458309441'];
	$providerIDs = $currentDealInfo['UF_CRM_1458307321'];

	// собираем массив со всеми сделками с производителем
	if (!empty($clientID))
	{
		$arFilterClient = ['ID'=>$clientID];
		$clientInfo = CCrmDeal::GetList([], $arFilterClient)->Fetch();
		$dealsForUpdate[] = $clientInfo;
		$providers = $clientInfo['UF_CRM_1458307321'];
		foreach ($providers as $provider) {
			if ($provider == $currentDealInfo['ID'])
			{
				continue;
			}
			$arFilterProvider = ['ID'=>$provider];
			$providersInfo[] = CCrmDeal::GetList([], $arFilterProvider)->Fetch();
		}
	}
	
	//собираем массив с датами
	//даты начала выполнения заказа из сделок с производителем
	foreach ($providersInfo as $providerInfo) {
		$providersDates[] = strtotime($providerInfo['UF_CRM_1458297436']);
	}
	// дата начала выполнения заказа из сделки с клиентом
	$clientDate[] = strtotime($clientInfo['UF_CRM_1458297436']);
	$dates = array_merge($providersDates, $clientDate);

	//синхронизируем, если только две сделки (одна с клиентом и одна с поставщиком)
	if (empty($providersDates[0]) && $updatedDate)
	{
		$deal->Update($clientInfo['ID'], $arFields, true, true, []);
		break;
	}

	// проходимся по всем нашим датам начала выполнения заказа. если та дата, которую только что установили наименьшая - апдейтим поле даты начала выполения заказа в сделке с клиентом.
	foreach ($dates as $key => $date) {
		if (strtotime($updatedDate) < $date)
		{
			$deal->Update($clientInfo['ID'], $arFields);
			break;
		}
	}

	echo json_encode($dates);
	exit();
}
// синхронизация поля "Дата завершения производства"
if (isset($_POST['dateEnd']))
{
	//инициализируем данные
	$deal = new CCrmDeal;
	$currentDealID = $_POST['dealID'];
	$updatedDate = $_POST['dateEnd'];
	//дата для обновления
	$arFields = array('UF_CRM_1458297458' => $updatedDate);
	
	$arFilter = ['ID'=>$currentDealID];
	$currentDealInfo = CCrmDeal::GetList([], $arFilter)->Fetch();

	//ID клиента и производителей (из пользовательских полей)
	$clientID = $currentDealInfo['UF_CRM_1458309441'];
	$providerIDs = $currentDealInfo['UF_CRM_1458307321'];


	if (!empty($clientID))
	{
		$arFilterClient = ['ID'=>$clientID];
		$clientInfo = CCrmDeal::GetList([], $arFilterClient)->Fetch();
		$dealsForUpdate[] = $clientInfo;
		$providers = $clientInfo['UF_CRM_1458307321'];
		foreach ($providers as $provider) {
			if ($provider == $currentDealInfo['ID'])
			{
				continue;
			}
			$arFilterProvider = ['ID'=>$provider];
			$providersInfo[] = CCrmDeal::GetList([], $arFilterProvider)->Fetch();
		}
	}
	

	foreach ($providersInfo as $providerInfo) {
		$providersDates[] = strtotime($providerInfo['UF_CRM_1458297458']);
	}

	$clientDate[] = strtotime($clientInfo['UF_CRM_1458297458']);
	$dates = array_merge($providersDates, $clientDate);
	
	if (empty($providersDates[0]) && $updatedDate)
	{
		$deal->Update($clientInfo['ID'], $arFields, true, true, []);
		break;
	}

	foreach ($dates as $key => $date) {
		if (strtotime($updatedDate) > $date)
		{
			$deal->Update($clientInfo['ID'], $arFields, true, true, []);
			break;
		}
	}

	// echo json_encode($dates);
	exit();
}

