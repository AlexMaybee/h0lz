<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule('crm'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED'));
	return;
}

if (!CModule::IncludeModule('itlogic.warehouse'))
{
	ShowError('Not find module warehouse');
	return;
}

$type_dao = new Itlogic\Warehouse\NewDealTypeDao();
$CrmPerms = new CCrmPerms($USER->GetID());

if (!$CrmPerms->HavePerm('CONFIG', BX_CRM_PERM_CONFIG, 'WRITE'))
{
	ShowError(GetMessage('CRM_PERMISSION_DENIED'));
	return;
}

if (!CCrmQuote::LocalComponentCausedUpdater())
	return;

$arResult['ACTIVE_TAB'] = 'status_tab_STATUS';

if($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid() &&
	isset($_POST['ACTION']) && $_POST['ACTION'] == 'save')
{
	// TODO in this place must be saved
	// =========================================

	$selected_id = $_POST['selected'];

	$type_dao->addDeliveryId($selected_id);

	if(isset($_POST['SELECTED_DEAL_TYPE_ID']) && $_POST['SELECTED_DEAL_TYPE_ID'] != '-' ){

		$deal_type_id = $_POST['SELECTED_DEAL_TYPE_ID'];
		$status_ids = [];

		foreach($_POST['LIST']['DEAL_STAGE'] as $k => $arFields)
		{
			if(isset($arFields['VALUE'])&& $arFields['VALUE'] != ''){
				if(is_int($k)){
					$status_ids[] = $k;
				}
			}
		}

		$type_dao->store($deal_type_id, '"'. implode(',',$status_ids) .'"');

	}else{

		$arAdd = array();
		$arUpdate = array();
		$arDelete = array();

		foreach($_POST['LIST'] as $entityId => $arFields)
		{
			$iPrevSort = 0;
			$CCrmStatus = false;
			$events = GetModuleEvents("crm", "OnBeforeCrmStatusCreate");

			while($arEvent = $events->Fetch())
			{
				$CCrmStatus = ExecuteModuleEventEx($arEvent, array($entityId));

				if($CCrmStatus)
					break;
			}

			if(!$CCrmStatus)
				$CCrmStatus = new CCrmStatus($entityId);

			foreach($arFields as $id => $arField)
			{
				$arField['SORT'] = (int)$arField['SORT'];
				if ($arField['SORT'] <= $iPrevSort)
					$arField['SORT'] = $iPrevSort + 10;
				$iPrevSort = $arField['SORT'];

				if (substr($id, 0, 1) == 'n')
				{
					if (trim($arField['VALUE']) == "")
						continue;

					$arAdd['NAME'] = trim($arField['VALUE']);
					$arAdd['SORT'] = $arField['SORT'];

					$CCrmStatus->Add($arAdd);
				}
				else
				{
					if (!isset($arField['VALUE']) || trim($arField['VALUE']) == "")
					{
						$arCurrentData = $CCrmStatus->GetStatusById($id);
						if ($arCurrentData['SYSTEM'] == 'N')
							$CCrmStatus->Delete($id);
						else
						{
							$arUpdate['NAME'] = trim($arCurrentData['NAME_INIT']);
							$CCrmStatus->Update($id, $arUpdate);
						}
					}
					else
					{
						$arCurrentData = $CCrmStatus->GetStatusById($id);
						if (trim($arField['VALUE']) != $arCurrentData['NAME'] || intval($arField['SORT']) != $arCurrentData['SORT'])
						{
							$arUpdate['NAME'] = trim($arField['VALUE']);
							$arUpdate['SORT'] = $arField['SORT'];
							$CCrmStatus->Update($id, $arUpdate);
						}
					}
				}
			}
		}
		$arResult['ACTIVE_TAB'] = $_POST['ACTIVE_TAB'];

	}

}

$ar = CCrmStatus::GetEntityTypes();
foreach($ar as $entityId => $arEntityType)
{
	$arResult['HEADERS'][$entityId] = $arEntityType['NAME'];
	$arResult['ROWS'][$entityId] = Array();
}

$res = CCrmStatus::GetList(array('SORT' => 'ASC'));
while($ar = $res->Fetch())
{
	$arResult['ROWS'][$ar['ENTITY_ID']][$ar['ID']] = $ar;
}

$events = GetModuleEvents("crm", "OnCrmStatusGetList");

while($arEvent = $events->Fetch())
{
	$arStatuses = ExecuteModuleEventEx($arEvent);
	foreach ($arStatuses as $key => $arStatus)
		$arResult['ROWS'][$arStatus['ENTITY_ID']][$arStatus['ID']] = $arStatus;
}

$arResult['NEED_FOR_FIX_STATUSES'] = false;
if(CCrmPerms::IsAdmin() && COption::GetOptionString('crm', '~CRM_FIX_STATUSES', 'N') === 'Y')
{
	$arResult['NEED_FOR_FIX_STATUSES'] = true;
}

CUtil::InitJSCore();
$arResult['ENABLE_CONTROL_PANEL'] = isset($arParams['ENABLE_CONTROL_PANEL']) ? $arParams['ENABLE_CONTROL_PANEL'] : true;



foreach($arResult['ROWS']['DEAL_TYPE'] as $key=>&$val){

     $delivery_id = $type_dao->getDeliveryId($val['ID']);

	 if($val['ID'] == $delivery_id){
		 $val['DELIVERY'] = 'checked';
	 }else{
		 $val['DELIVERY'] = '';
	 }
//	echo "ID = ";
//	var_dump($val['ID']);
//	echo "deliv = ";
//	var_dump($delivery_id);

}

$this->IncludeComponentTemplate();
$APPLICATION->AddChainItem(GetMessage('CRM_FIELDS_ENTITY_LIST'), $arResult['~ENTITY_LIST_URL']);

?>