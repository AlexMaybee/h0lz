<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");





$dealID = $_GET['DEAL_ID'];
CModule::IncludeModule("crm");

$CCrmDeal = new CCrmDeal();
$stages = CCrmStatus::GetStatusList('DEAL_STAGE');
$types = CCrmStatus::GetStatusList('DEAL_TYPE');


$arDealFilter = array(
    'ID' => $dealID,
    );
$arDealList = $CCrmDeal->GetList(array(), $arDealFilter);
$deal = $arDealList->Fetch();
$rusTitles = [];
$rus['ID'] = 'Идентификатор';
$rus['COMMENTS'] = 'Комментарий';
$rus['ADDITIONAL_INFO'] = 'Дополнительная информация';
$rus['TITLE'] = 'Название';
$rus['LEAD_ID'] = 'Лид';
if ($deal['LEAD_ID']){
	$rusTitles['LEAD_ID'] = CCrmLead::GetByID($deal['LEAD_ID'])['TITLE'];
}
$rus['TITLE'] = 'Название';
$rus['COMPANY_TITLE'] = 'Компания';
$rus['CONTACT_FULL_NAME'] = 'Контакт';
$rus['STAGE_ID'] = 'Стадия сделки';
if ($deal['STAGE_ID']){
	$rusTitles['STAGE_ID'] = $stages[$deal['STAGE_ID']];
}
$rus['CLOSED'] = 'Закрыта?';
$deal['CLOSED'] == 'Y'? $rusTitles['CLOSED'] = 'Да' : $rusTitles['CLOSED'] = 'Нет';
$rus['TYPE_ID'] = 'Тип сделки';
if ($deal['TYPE_ID']){
	$rusTitles['TYPE_ID'] = $types[$deal['TYPE_ID']];
}
$rus['PROBABILITY'] = 'Вероятность';
$rus['OPPORTUNITY'] = 'Возможная сумма сделки';
$rus['CURRENCY_ID'] = 'Валюта';
$rus['BEGINDATE'] = 'Время начала сделки';
$rus['ASSIGNED_BY_ID'] = 'Ответственный';
if ($deal['ASSIGNED_BY_ID']){
	$rusTitles['ASSIGNED_BY_ID'] = CUser::GetFullName($deal['ASSIGNED_BY_ID']);
}
$rus['CREATED_BY'] = 'Создал';
if ($deal['CREATED_BY']){
	$rusTitles['CREATED_BY'] = CUser::GetFullName($deal['CREATED_BY']);
}
$rus['MODIFY_BY'] = 'Изменил';
if ($deal['MODIFY_BY']){
	$rusTitles['MODIFY_BY'] = CUser::GetFullName($deal['MODIFY_BY']);
}
$rus['DATE_CREATE'] = 'Дата создания';
$rus['DATE_MODIFY'] = 'Дата изменения';
$rus['UF_CRM_1458294764'] = 'Вид сделки';
switch ($deal['UF_CRM_1458294764']) {
	case "31":
		$rusTitles['UF_CRM_1458294764'] = 'Сделка с клиентом';
		break;	
	case "32":
		$rusTitles['UF_CRM_1458294764'] = 'Сделка с поставщиком';
		break;
	default:
		$rusTitles['UF_CRM_1458294764'] = 'Нет';
		break;
};
$rus['UF_CRM_1458294876'] = 'Вид товара в сделке';
switch ($deal['UF_CRM_1458294876']) {
	case '33':
		$rusTitles['UF_CRM_1458294876'] = 'Сделка пополам';
		break;
	case '34':
		$rusTitles['UF_CRM_1458294876'] = 'Сделка по дверям';
		break;
	case '35':
		$rusTitles['UF_CRM_1458294876'] = 'Общая сделка';
		break;
	default:
		$rusTitles['UF_CRM_1458294876'] = 'Нет';
		break;
}
$rus['UF_CRM_1458295326'] = 'Номер заказа';
$rus['UF_CRM_1458297033'] = 'Ответственный исполнитель';
if ($deal['UF_CRM_1458297033']){
	$rusTitles['UF_CRM_1458297033'] = CUser::GetFullName($deal['UF_CRM_1458297033']);
}
$rus['UF_CRM_1458297436'] = 'Дата начала выполнения заказа';
$rus['UF_CRM_1458297436'] = 'Дата завершения производства';
$rus['UF_CRM_1458297436'] = 'Дата поступления на склад';
$rus['UF_CRM_1458297436'] = 'Дата поступления на склад';
$rus['UF_CRM_1458297436'] = 'Дата и время доставки (с)';
$rus['UF_CRM_1458297436'] = 'Дата и время доставки (по)';
$rus['UF_CRM_1458297436'] = 'Дата и время монтажа (с)';
$rus['UF_CRM_1458297436'] = 'Дата и время монтажа (по)';
$rus['UF_CRM_1458307321'] = 'Сделка с производителем';
if (!empty($deal['UF_CRM_1458307321']))
{
	foreach ($deal['UF_CRM_1458307321'] as $key => $value) {
		$tmpDeal = CCrmDeal::GetByID($value);
		$text = "Сделка №".$tmpDeal['ID']." - ". $tmpDeal['TITLE'] ."; ";
		$rusTitles['UF_CRM_1458307321'] .= $text;
	}
}
$rus['UF_CRM_1458309033'] = 'Посредник';
if ($deal['UF_CRM_1458309033']){
	$rusTitles['UF_CRM_1458309033'] = CUser::GetFullName($deal['UF_CRM_1458309033']);
}

$rus['UF_CRM_1458309441'] = 'Сделка с клиентом';
if (!empty($deal['UF_CRM_1458307321']))
{
	foreach ($deal['UF_CRM_1458309441'] as $key => $value) {
		$tmpDeal = CCrmDeal::GetByID($value);
		$text = "Сделка №".$tmpDeal['ID']." - ". $tmpDeal['TITLE'] ."; ";
		$rusTitles['UF_CRM_1458309441'] .= $text;
	}
}

////////////// адрес
$rus['UF_CRM_1458741353'] = 'Улица, дом, корпус, строение';
$rus['UF_CRM_1458741382'] = 'Квартира / офис';
$rus['UF_CRM_1458741393'] = 'Город';
$rus['UF_CRM_1458741405'] = 'Район';
$rus['UF_CRM_1458741420'] = 'Область';
$rus['UF_CRM_1458741437'] = 'Почтовый индекс';
$rus['UF_CRM_1458741456'] = 'Страна';
//////////////
$rus['UF_CRM_1458811248'] = 'Поставщик';


$products = CCrmDeal::LoadProductRows($deal['ID']);
foreach ($products as $product) {
	$productsName .= $product['PRODUCT_NAME']. "; ";
}

// pre($deal);
$rus['PRODUCTS'] = 'Товары';
$rusTitles['PRODUCTS'] = $productsName;

foreach ($deal as $key => $value) {
	if ($rus[$key] && $rusTitles[$key])
		$test[] = array($rus[$key], $rusTitles[$key]);
	elseif ($rus[$key])
		$test[] = array($rus[$key], $value);
	else
		/*$test[] = array($key, $value)*/continue;
}
$test[] = array($rus['PRODUCTS'], $rusTitles['PRODUCTS']);






header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
echo "\xEF\xBB\xBF";
$out = fopen('php://output', 'w');

foreach($test as $item)
{	
	fputcsv($out, $item, ";");
}	

fclose($out);








############################################################################
// $CCrmDeal = new CCrmDeal();
// CModule::IncludeModule("crm");
// $rootActivity = $this->GetRootActivity();
// $dealID= $rootActivity->GetVariable("dealID");
// pre(get_deal_user_field_Elvira_2($dealID, 'UF_CRM_1458297458'));
// $w1 = strtotime(get_deal_user_field_Elvira_2($dealID, 'UF_CRM_1458297458'));
// $w2 = $w1 - (60*60*24*3);

// $w3 = strtotime(date('d.m.Y 10:30:00', $w2));
// $w4 = ($w3 - time())/(60*60);
// $rootActivity->SetVariable("pauseTime", $w4);



############################################################################
// $CCrmDeal = new CCrmDeal();

// $dealID = 77;

// CModule::IncludeModule("crm");
// $rootActivity = $this->GetRootActivity();
// $dealID= $rootActivity->GetVariable("dealID");

// $q1 = strtotime(get_deal_user_field_Elvira($dealID, 'UF_CRM_1458297436'));
// $q2 = strtotime(get_deal_user_field_Elvira($dealID, 'UF_CRM_1458297458'));
// $q3 = ($q1 + (($q2 - $q1)/2));
// $q4 = date('d.m.Y 10:30:00', $q3);

// $rootActivity->SetVariable("finaldate", $q4);

function get_deal_user_field_Elvira_2($id, $field){

    $arFilter = array("ID" => $id);
    $arParams = array($field);
    $by =Array('DATE_CREATE' => 'DESC');
    $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
    $res = $arRes->Fetch();
    return isset($res[$field]) ? $res[$field] : FALSE;
}
############################################################################
















// $arDealAdd = array(
// 	'STAGE_ID' => 'NEW',
// 	'CONTACT_ID' => 22,
// 	'TITLE' => "dsfdsfdsf",
// 	);
// $newDeal = $CCrmDeal->add($arDealAdd);

// pre($arDealAdd);
// pre($newDeal);

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

exit;