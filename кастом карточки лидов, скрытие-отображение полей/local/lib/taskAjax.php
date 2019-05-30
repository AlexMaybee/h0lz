<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
CModule::IncludeModule("tasks");
CModule::IncludeModule("main");
CModule::IncludeModule("bizproc");


$taskID = $_POST['id'];
// $taskID = 138;

$CTasks = new CTasks();

$task = $CTasks->GetByID($taskID)->Fetch();

// pre($task['UF_CRM_TASK']);

$dealID = intval(substr($task['UF_CRM_TASK'][0], 2));
$arRes = CCrmDeal::GetList($by,$arFilter)->Fetch();
$bp_id = $arRes['UF_CRM_1461678599'];
// $filename = $_SERVER['DOCUMENT_ROOT'].'/killWorkflow.log';
// file_put_contents($filename, "\n start \n\n", FILE_APPEND);
// file_put_contents($filename, "\n arRes \n\n", FILE_APPEND);
// file_put_contents($filename, print_r($arRes, 1), FILE_APPEND);
// file_put_contents($filename, "\n bp_id \n\n", FILE_APPEND);
// file_put_contents($filename, print_r($bp_id, 1), FILE_APPEND);
// file_put_contents($filename, "\n end \n\n", FILE_APPEND);
// pre($bp_id);

CBPDocument::killWorkflow($bp_id);
// pre('ЭЛЯ ПРИВЕТ!');
//UF_CRM_1461678599

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

function get_deal_user_field($id, $field){

    $arFilter = array("ID" => $id);
    $arParams = array($field);
    $by =Array('DATE_CREATE' => 'DESC');
    $arRes = CCrmDeal::GetList($by,$arFilter,$arParams);
    $res = $arRes->Fetch();
    return isset($res[$field]) ? $res[$field] : FALSE;
}