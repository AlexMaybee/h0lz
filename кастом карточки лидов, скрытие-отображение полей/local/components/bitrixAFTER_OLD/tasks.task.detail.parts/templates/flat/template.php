<?
// the while-list filtering is placed inside component.php
$block = $arResult['BLOCKS'][0];  

if((string) $block != '')
{
	$folder = $templateFolder.'/'.ToLower($block).'/';

	if((string) $arParams['TEMPLATE_DATA']['ID'] != '')
	{
		$arResult['TEMPLATE_DATA'] = $arParams['TEMPLATE_DATA'];
	}
	else
	{
		$arResult['TEMPLATE_DATA'] = array(
			'ID' => md5($folder),
		);
	}

	$extensionId = md5('tasks_component_ext_'.$arResult['TEMPLATE_DATA']['ID']);

	CJSCore::RegisterExt(
		$extensionId,
		array(
			'js'  => array(
				$folder.'logic.js'
			),
			'rel' =>  array(
				'tasks_ui_widget',
				'tasks_util_template',
				'tasks_ui_draganddrop'
			)
		)
	);
	CJSCore::Init($extensionId);

	require_once($_SERVER["DOCUMENT_ROOT"].$folder.'template.php');
}