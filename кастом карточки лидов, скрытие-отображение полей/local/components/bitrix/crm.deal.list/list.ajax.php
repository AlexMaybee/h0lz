<?
use Itlogic\Multidealtype\NewDealTypeDao;

define('STOP_STATISTICS', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
global $DB, $APPLICATION;


if(!CModule::IncludeModule('itlogic.multidealtype')){

	ShowError("Error.itlogic.multidealtype module not find ");
	return;

}


if(!function_exists('__CrmDealListEndResonse'))
{
	function __CrmDealListEndResonse($result)
	{
		$GLOBALS['APPLICATION']->RestartBuffer();
		Header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);
		if(!empty($result))
		{
			echo CUtil::PhpToJSObject($result);
		}
		require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
		die();
	}
}

if (!CModule::IncludeModule('crm'))
{
	return;
}

$userPerms = CCrmPerms::GetCurrentUserPermissions();
if(!CCrmPerms::IsAuthorized())
{
	return;
}

global $APPLICATION;

$action = isset($_REQUEST['ACTION']) ? $_REQUEST['ACTION'] : '';


#####################################################################################################
/*if ($_POST['TYPE'] == 'DEAL')
{
	$filename = $_SERVER['DOCUMENT_ROOT']."/r.txt";
	file_put_contents($filename, "atata");


	$CCrmDeal = new CCrmDeal;
	$dealID = $_POST['ID'];
	$arFilter = ['ID' => $dealID];
	$dealInfo = CCrmDeal::GetList([], $arFilter)->Fetch();

	$currentStage = $_POST['VALUE'];
	$maxStageOrder = 0;



	// выбираем все стадии и приводим к виду [название стадии сделки]=>[числовый идентификатор, отображающий порядковый номер сделки]
	$stages = CCrmStatus::GetStatusList('DEAL_STAGE');
	$i = 0;
	foreach ($stages as $key => $value)
	{
		$stagesForCompare[$key] = $i;
		$i += 1;
	}
	$currentStageOrder = $stagesForCompare[$currentStage];
	$clientID = $dealInfo['UF_CRM_1458309441'];
	$clientInfo = CCrmDeal::GetList([], ['ID' => $clientID[0]])->Fetch();
	$clientStage[] = $clientInfo['STAGE_ID'];
	$providerIDs = $clientInfo['UF_CRM_1458307321'];

	//пробегаемся по всем сделкам с поставщиками и формируем массив стадий
	if (!empty($providerIDs))
	{
		foreach ($providerIDs as $providerID)
		{
			if ($providerID == $dealID)
				continue;
			$arFilterProvider = ['ID' => $providerID];
			$providerStages[] = CCrmDeal::GetByID($providerID)['STAGE_ID'];
		}
	}
	// выбираем максимальную стадию
	if (isset($providerStages))
	{
		foreach ($providerStages as $key => $value)
		{
			$providerStagesOders[$value] = $stagesForCompare[$value];
			if ($providerStagesOders[$value] > $max)
			{
				$maxStageOrder = $providerStagesOders[$value];
			}
		}

	}
	// если только что поставили стадию, которая выше всех по порядку - апдейтим сделку с клиентом
	if ($currentStageOrder > $maxStageOrder)
	{
		$arFields = ['STAGE_ID'=>$currentStage];
		$tmp = $CCrmDeal->Update($clientID[0], $arFields, true, true);
		####################################
		CModule::IncludeModule("bizproc");
		CModule::IncludeModule("crm");

		function get_deal_user_field_lazzy_roma($id, $field){

		    $arFilter = array("ID" => $id);
		    $arParams = array($field);
		    $by =Array('DATE_CREATE' => 'DESC');
		    $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
		    $res = $arRes->Fetch();
		    return isset($res[$field]) ? $res[$field] : FALSE;
		}
//Эля попросила закомментить 
		// $bp_id = get_deal_user_field_lazzy_roma($clientID[0], 'UF_CRM_1461678599');

		// file_put_contents($filename, "\n++++++++bp_id++ \n\n", FILE_APPEND);
		// file_put_contents($filename, "8888888888\n", FILE_APPEND);
		// file_put_contents($filename, print_r($bp_id, true), FILE_APPEND);
		file_put_contents($filename, $_POST['VALUE']);
		if ($_POST['VALUE'] == 6)
		{
			// if(!empty($bp_id))
			// {

			// 	// file_put_contents($filename, "\n++++++++bp_id++ \n\n", FILE_APPEND);
			// 	// file_put_contents($filename, "8888888888\n", FILE_APPEND);
			// 	// file_put_contents($filename, print_r($bp_id, true), FILE_APPEND);

			// 	CBPDocument::killWorkflow($bp_id);
			
				CBPDocument::StartWorkflow(
				      13,
				      array("crm","CCrmDocumentDeal","DEAL_".$clientID[0]),
				 array(),
				      $arErrors
				);
				CBPDocument::StartWorkflow(
				      14,
				      array("crm","CCrmDocumentDeal","DEAL_".$clientID[0]),
				 array(),
				      $arErrors
				);
			// }
		}

		####################################
	}
}
#####################################################################################################
*/




if (isset($_REQUEST['MODE']) && $_REQUEST['MODE'] === 'SEARCH')
{
	if($userPerms->HavePerm('DEAL', BX_CRM_PERM_NONE, 'READ'))
	{
		return;
	}

	__IncludeLang(dirname(__FILE__).'/lang/'.LANGUAGE_ID.'/'.basename(__FILE__));

	CUtil::JSPostUnescape();
	$APPLICATION->RestartBuffer();

	// Limit count of items to be found
	$nPageTop = 50;		// 50 items by default
	if (isset($_REQUEST['LIMIT_COUNT']) && ($_REQUEST['LIMIT_COUNT'] >= 0))
	{
		$rawNPageTop = (int) $_REQUEST['LIMIT_COUNT'];
		if ($rawNPageTop === 0)
			$nPageTop = false;		// don't limit
		elseif ($rawNPageTop > 0)
			$nPageTop = $rawNPageTop;
	}

	$search = trim($_REQUEST['VALUE']);
	$multi = isset($_REQUEST['MULTI']) && $_REQUEST['MULTI'] == 'Y'? true: false;
	$arFilter = array();
	if (is_numeric($search))
		$arFilter['ID'] = (int) $search;
	else if (preg_match('/(.*)\[(\d+?)\]/i'.BX_UTF_PCRE_MODIFIER, $search, $arMatches))
	{
		$arFilter['ID'] = (int) $arMatches[2];
		$arFilter['%TITLE'] = trim($arMatches[1]);
		$arFilter['LOGIC'] = 'OR';
	}
	else
		$arFilter['%TITLE'] = $search;

	$arDealStageList = CCrmStatus::GetStatusListEx('DEAL_STAGE');
	$arSelect = array('ID', 'TITLE', 'STAGE_ID', 'COMPANY_TITLE', 'CONTACT_FULL_NAME');
	$arOrder = array('TITLE' => 'ASC');
	$arData = array();
	$obRes = CCrmDeal::GetList($arOrder, $arFilter, $arSelect, $nPageTop);
	$arFiles = array();

	while ($arRes = $obRes->Fetch())
	{
		$clientTitle = (!empty($arRes['COMPANY_TITLE'])) ? $arRes['COMPANY_TITLE'] : '';
		$clientTitle .= (($clientTitle !== '' && !empty($arRes['CONTACT_FULL_NAME'])) ? ', ' : '').$arRes['CONTACT_FULL_NAME'];

		$arData[] =
			array(
				'id' => $multi? 'D_'.$arRes['ID']: $arRes['ID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_deal_show'),
					array(
						'deal_id' => $arRes['ID']
					)
				),
				'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
				'desc' => $clientTitle,
				'type' => 'deal'
			)
		;
	}

	Header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);
	echo CUtil::PhpToJsObject($arData);
	die();
}

elseif ($action === 'SAVE_PROGRESS')
{

	$ID = isset($_REQUEST['ID']) ? intval($_REQUEST['ID']) : 0;
	$typeName = isset($_REQUEST['TYPE']) ? $_REQUEST['TYPE'] : '';
	$stageID = isset($_REQUEST['VALUE']) ? $_REQUEST['VALUE'] : '';

	$targetTypeName = CCrmOwnerType::ResolveName(CCrmOwnerType::Deal);
	if($stageID === '' || $ID <= 0  || $typeName !== $targetTypeName)
	{
		$APPLICATION->RestartBuffer();
		echo CUtil::PhpToJSObject(
			array('ERROR' => 'Invalid data!')
		);
		die();
	}

	$entityAttrs = $userPerms->GetEntityAttr($targetTypeName, array($ID));
	if (!$userPerms->CheckEnityAccess($targetTypeName, 'WRITE', $entityAttrs[$ID]))
	{
		$APPLICATION->RestartBuffer();
		echo CUtil::PhpToJSObject(
			array('ERROR' => 'Access denied!')
		);
		die();
	}

	$arFields = CCrmDeal::GetByID($ID, false);
	$stage = CCrmStatus::GetList(['ID'=>'ASC'],['ENTITY_ID'=>'DEAL_TYPE']);
	$check_id = 0;
	while($r = $stage->Fetch()){
		if($r['STATUS_ID'] == $arFields['TYPE_ID']){
			$check_id = $r['ID'];
		}
	}
	$flag = false;
	$newDealTypeDao = new NewDealTypeDao();

	if($check_id != 0){
		$check_id = $newDealTypeDao->getDeliveryId($check_id);
	}

	if($check_id != 0){
		$flag = true;
	}

	if($arFields['STAGE_ID'] == 'WON'){

		__CrmDealListEndResonse(array('TYPE' => $targetTypeName, 'ID' => $ID, 'VALUE' => $stageID));


	}else if($arFields['STAGE_ID'] != 'WON' && $stageID == 'WON'){

  //   	$dao = new Itlogic\Warehouse\WarehouseDao();
		// $dao->finishDeal($ID,'deal',$flag);

	}

	if(!is_array($arFields))
	{
		$APPLICATION->RestartBuffer();
		echo CUtil::PhpToJSObject(
			array('ERROR' => 'Not found!')
		);
		die();
	}

	if(isset($arFields['CREATED_BY_ID']))
	{
		unset($arFields['CREATED_BY_ID']);
	}

	if(isset($arFields['DATE_CREATE']))
	{
		unset($arFields['DATE_CREATE']);
	}

	if(isset($arFields['MODIFY_BY_ID']))
	{
		unset($arFields['MODIFY_BY_ID']);
	}

	if(isset($arFields['DATE_MODIFY']))
	{
		unset($arFields['DATE_MODIFY']);
	}

	$arFields['STAGE_ID'] = $stageID;
	$CCrmDeal = new CCrmDeal(false);
	if($CCrmDeal->Update($ID, $arFields, true, true, array('DISABLE_USER_FIELD_CHECK' => true, 'REGISTER_SONET_EVENT' => true)))
	{
		$arErrors = array();
		CCrmBizProcHelper::AutoStartWorkflows(
			CCrmOwnerType::Deal,
			$ID,
			CCrmBizProcEventType::Edit,
			$arErrors
		);
	}

	__CrmDealListEndResonse(array('TYPE' => $targetTypeName, 'ID' => $ID, 'VALUE' => $stageID));
}
elseif ($action === 'REBUILD_STATISTICS')
{
	__IncludeLang(dirname(__FILE__).'/lang/'.LANGUAGE_ID.'/'.basename(__FILE__));

	if(!CCrmDeal::CheckUpdatePermission(0))
	{
		__CrmDealListEndResonse(array('ERROR' => 'Access denied.'));
	}

	if(COption::GetOptionString('crm', '~CRM_REBUILD_DEAL_STATISTICS', 'N') !== 'Y')
	{
		__CrmDealListEndResonse(
			array(
				'STATUS' => 'NOT_REQUIRED',
				'SUMMARY' => GetMessage('CRM_DEAL_LIST_REBUILD_STATISTICS_NOT_REQUIRED_SUMMARY')
			)
		);
	}

	$progressData = COption::GetOptionString('crm', '~CRM_REBUILD_DEAL_STATISTICS_PROGRESS',  '');
	$progressData = $progressData !== '' ? unserialize($progressData) : array();
	$lastItemID = isset($progressData['LAST_ITEM_ID']) ? intval($progressData['LAST_ITEM_ID']) : 0;
	$processedItemQty = isset($progressData['PROCESSED_ITEMS']) ? intval($progressData['PROCESSED_ITEMS']) : 0;
	$totalItemQty = isset($progressData['TOTAL_ITEMS']) ? intval($progressData['TOTAL_ITEMS']) : 0;
	if($totalItemQty <= 0)
	{
		$totalItemQty = CCrmDeal::GetListEx(array(), array('CHECK_PERMISSIONS' => 'N'), array(), false);
	}

	$filter = array('CHECK_PERMISSIONS' => 'N');
	if($lastItemID > 0)
	{
		$filter['>ID'] = $lastItemID;
	}

	$dbResult = CCrmDeal::GetListEx(
		array('ID' => 'ASC'),
		$filter,
		false,
		array('nTopCount' => 20),
		array('ID')
	);

	$itemIDs = array();
	$itemQty = 0;
	if(is_object($dbResult))
	{
		while($fields = $dbResult->Fetch())
		{
			$itemIDs[] = (int)$fields['ID'];
			$itemQty++;
		}
	}

	if($itemQty > 0)
	{
		CCrmDeal::RebuildStatistics($itemIDs);

		$progressData['TOTAL_ITEMS'] = $totalItemQty;
		$processedItemQty += $itemQty;
		$progressData['PROCESSED_ITEMS'] = $processedItemQty;
		$progressData['LAST_ITEM_ID'] = $itemIDs[$itemQty - 1];

		COption::SetOptionString('crm', '~CRM_REBUILD_DEAL_STATISTICS_PROGRESS', serialize($progressData));
		__CrmDealListEndResonse(
			array(
				'STATUS' => 'PROGRESS',
				'PROCESSED_ITEMS' => $processedItemQty,
				'TOTAL_ITEMS' => $totalItemQty,
				'SUMMARY' => GetMessage(
					'CRM_DEAL_LIST_REBUILD_STATISTICS_PROGRESS_SUMMARY',
					array(
						'#PROCESSED_ITEMS#' => $processedItemQty,
						'#TOTAL_ITEMS#' => $totalItemQty
					)
				)
			)
		);
	}
	else
	{
		COption::RemoveOption('crm', '~CRM_REBUILD_DEAL_STATISTICS');
		COption::RemoveOption('crm', '~CRM_REBUILD_DEAL_STATISTICS_PROGRESS');
		__CrmDealListEndResonse(
			array(
				'STATUS' => 'COMPLETED',
				'PROCESSED_ITEMS' => $processedItemQty,
				'TOTAL_ITEMS' => $totalItemQty,
				'SUMMARY' => GetMessage(
					'CRM_DEAL_LIST_REBUILD_STATISTICS_COMPLETED_SUMMARY',
					array('#PROCESSED_ITEMS#' => $processedItemQty)
				)
			)
		);
	}
}
?>
