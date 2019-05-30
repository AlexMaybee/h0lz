<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$templateId = $arResult['TEMPLATE_DATA']['ID'];
$prefix = htmlspecialcharsbx($arResult['TEMPLATE_DATA']['INPUT_PREFIX']);
?>

<div id="bx-component-scope-<?=htmlspecialcharsbx($templateId)?>">

	<span class="task-options-label-title"><?=Loc::getMessage('TASKS_TTDP_CHECKLIST_IN_PROGRESS')?>: <span data-bx-id="checklist-ongoing-counter">0</span></span>
	
	<div data-bx-id="checklist-items-ongoing">

		<script type="text/html" data-bx-id="checklist-item">

			<div class="task-options-field off">
				<div class="task-options-field-inner">

					<?//any mode?>
					<span 	class="task-options-drg-btn" data-bx-id="checklist-item-drag"></span>
					<input 	class="task-options-checkbox" data-bx-id="checklist-item-btn-check" type="checkbox" {{CHECKED}} id="chl_item_{{ID}}" />
					<input 	data-bx-id="checklist-item-sort-index-fld" type="hidden" name="<?=$prefix?>[{{ID}}][SORT_INDEX]" value="{{SORT_INDEX}}" />
					<input 	data-bx-id="checklist-item-is-complete-fld" type="hidden" name="<?=$prefix?>[{{ID}}][IS_COMPLETE]" value="{{IS_COMPLETE}}" />

					<?//read mode?>
					<label 	class="tasks-block-off task-options-label" data-bx-id="checklist-item-title" for="chl_item_{{ID}}">{{TITLE}}</label>
					<span 	class="tasks-block-off task-options-title-edit{{MODIFY_HIDDEN}}" data-bx-id="checklist-item-btn-edit"></span>
					
					<?//edit mode?>
					<input 	class="tasks-block-on" data-bx-id="checklist-item-title-edit" type="text" value="{{TITLE}}" name="<?=$prefix?>[{{ID}}][TITLE]" placeholder="<?=Loc::getMessage('TASKS_TTDP_CHECKLIST_WHAT_TO_BE_DONE')?>" />
					<span 	class="tasks-block-on task-options-title-ok{{MODIFY_HIDDEN}}" data-bx-id="checklist-item-btn-apply"></span>

					<?//any mode?>
					<span class="task-options-title-del{{REMOVE_HIDDEN}}" data-bx-id="checklist-item-btn-delete"></span>
				</div>
			</div>

		</script>

		<script type="text/html" data-bx-id="checklist-item-flying">

			<div class="task-options-field-inner off">
				<span 	class="task-options-drg-btn-f"></span>
				<input 	class="task-options-checkbox" type="checkbox" {{CHECKED}} id="chl_item_{{ID}}-f" disabled="disabled" />
				<label 	class="tasks-block-off task-options-label" for="chl_item_{{ID}}-f">{{TITLE}}</label>
			</div>

		</script>

	</div>

	<?if($arResult['TEMPLATE_DATA']['CAN_EDIT_LIST']):?>

		<div class="task-options-field off" data-bx-id="checklist-add-item-form" style="margin-left: 54px">

			<div class="tasks-block-on">
				<input type="text" data-bx-id="checklist-add-item-title" placeholder="<?=Loc::getMessage('TASKS_TTDP_CHECKLIST_WHAT_TO_BE_DONE')?>" />
				<span class="task-options-link inline"><a data-bx-id="checklist-add-item" href="javascript:void(0)"><?=Loc::getMessage('TASKS_TTDP_CHECKLIST_ADD')?></a></span>
			</div>

			<div class="tasks-block-off">
				<span class="task-options-link"><a data-bx-id="checklist-add-item-form-open" href="javascript:void(0)"><?=Loc::getMessage('TASKS_TTDP_CHECKLIST_ADD')?></a></span>
			</div>
		</div>

	<?endif?>

	<div data-bx-id="checklist-complete-block" class="hidden">

		<span class="task-options-label-title"><?=Loc::getMessage('TASKS_TTDP_CHECKLIST_COMPLETE')?>: <span data-bx-id="checklist-complete-counter">0</span></span>

		<div data-bx-id="checklist-items-complete">
		</div>

	</div>

	<?/*
	<div class="task-options-field task-options-field-ready">
		<div class="task-options-field-inner">
			<span class="task-options-drg-btn"></span>
			<input class="task-options-checkbox" id="id3" type="checkbox" checked>
			<label for="id3" class="task-options-label">Проанализировать конкуретные решения</label>
			<span class="task-options-title-edit"></span>
			<span class="task-options-title-del"></span>
		</div>	
	</div>
	*/?>

</div>

<?/*
<style>
	.drag, .drag2{
		border: 1px solid black; padding: 5px; margin-bottom: 10px;
	}
	.dnd-gag{
		opacity: 0.5;
	}.
	.dd-handle{
		padding: 5px 0px;
		border: 1px solid green;
		width: 10px;
		height: 5px;
		display: inline-block;
		background-color: yellow !important;
	}
</style>

<div style="border: 1px solid red" class="catch" id="container">

	<div class="drag" id="item_1">
		<div class="dd-handle">H</div><div> 0</div>
	</div>
	<div class="drag" id="item_2">
		<div class="dd-handle">H</div><div> 1</div>
	</div>
	<div class="drag" id="item_3">
		<div class="dd-handle">H</div><div> 2</div>
	</div>

</div>

<br />
<br />
<br />

<script>
	var dd = new BX.Tasks.UI.DragAndDrop();
	dd.bindNode(BX('item_1'));
	dd.bindNode(BX('item_2'));
	dd.bindNode(BX('item_3'));
	dd.bindDropZone(BX('container'));
</script>
*/?>

<script>
	new BX.Tasks.Component.TaskDetailPartsChecklist(<?=CUtil::PhpToJSObject(array(
		'id' => $templateId,
		'registerDispatcher' => true
	), false, false, true)?>);
</script>