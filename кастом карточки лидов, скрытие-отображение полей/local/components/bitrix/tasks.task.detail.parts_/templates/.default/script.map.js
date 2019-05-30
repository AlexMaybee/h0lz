{"version":3,"file":"script.min.js","sources":["script.js"],"names":["tasksDetailPartsNS","tasksListAjaxUrl","BX","message","detailsAjaxUrl","responsiblePopup","accomplicesPopup","auditorsPopup","arAccomplices","arAuditors","timerCallback","insertAfterItemId","checklistCounter","loadedSelectors","DELEGATE","RESPONSIBLE","ACCOMPLICES","AUDITORS","getSerialId","id","arguments","toggleFavorite","taskId","way","tasksDetailsNS","initChecklist","inCreateMode","checkListCompletedShown","toggleCompletedCheckListItems","bind","showCompletedCheckListItems","e","hideCompletedCheckListItems","checklistToggle","isTaskCreateMode","itemId","checkboxDomNode","itemDomNode","hasClass","addClass","oTask","CJSTask","Item","checked","removeClass","value","checklistComplete","callbackOnSuccess","data","callbackOnFailure","recalcChecklist","checklistRenew","checklistAddItem","disabled","title","length","style","display","Date","now","appendChild","this","renderChecklistItem","initChecklistItem","focus","selfObj","reply","rawReply","itemTitle","isComplete","checklistRemoveItem","remove","checklistDelete","checklistRename","newTitle","innerHTML","util","htmlspecialchars","window","jsDD","Enable","checklistMoveAfterItem","selectedItemId","checklistEditItem","labelDomNode","Disable","setCursorPosition","isChecked","parameters","clsName","item","create","props","className","children","type","events","click","cursor","html","toString","event","eTarget","target","srcElement","isA","nodeName","isCB","PreventDefault","maxlength","keypress","checklistEditOnKeyPress","name","inputDomNodeId","checkboxes","findChildren","tagName","attribute","counters","i","total","replace","row","onbxdragstart","bxblank","parentNode","insertBefore","height","getFullHeight","margin","paddingLeft","position","onbxdrag","x","y","tmp","pos","top","onbxdragstop","replaceChild","substr","parseInt","onbxdraghover","dest","nextSibling","previousSibling","registerDest","registerObject","elem","setSelectionRange","createTextRange","range","collapse","moveEnd","moveStart","select","key","keyCode","which","checklistSaveItem","ShowActionMenu","button","menu","PopupMenu","destroy","show","loadUserSelectorViaAjax","selectorCodename","selectedUsersIds","params","groupId","multiple","bindElement","callbackOnReady","callbackOnSelect","loadingParams","requestedObject","anchorId","GROUP_ID_FOR_SITE","arUser","onReady","selectorObject","callbackOnChange","arUsers","Tasks","lwPopup","__initSelectors","ShowDelegatePopup","__ShowDelegatePopup","onDelegateChange","selectorObjName","delegatePopup","PopupWindowManager","offsetTop","autoHide","closeByEsc","content","buttons","PopupWindowButton","text","form","method","delegateUser","document","body","submit","popupWindow","close","PopupWindowButtonLink","showResponsibleChangePopup","selectedUserId","onResponsibleSelect","onDeleteClick","confirm","TasksIFrameInst","isOpened","mode","sessid","ajax","dataType","url","processData","onsuccess","datum","onDeleteClick_onSuccess","onTaskDeleted","getMembersAddChangeFunction","memberType","_auditorsAddChange","onAuditorsChange","_accomplicesAddChange","onAccomplicesChange","O_AUDITORS","arSelected","arUsersIds","renderMembersBlock","post","detailTaksID","path_to_user","path_to_task","path_to_user_tasks_task","auditors","O_ACCOMPLICES","accomplices","stopWatch","renderredUserBlock","removeChild","index","indexOf","splice","isAuditorsInAuditorsBlock","startWatch","addUserToAuditorsBlock","RenderUser","push","block","node","childNodes","blockType","userSelectorPopup","styleSuffix","div","cleanNode","setBindElement","bAvatar","arChildren","href","background","photo","ShowGradePopupDetail","currentValues","TaskGradePopup","onPopupChange","__onGradePopupChangeDetail","listItem","mark","listValue","ShowPriorityPopupDetail","currentPriority","TaskPriorityPopup","__onPriorityChangeDetail","priority","taskData","onTaskChanged","arFilter","columnsOrder","findNextSibling","tag","responsible","tasksListNS","getColumnsOrder","legacyHtmlTaskItem","parseJSON","tasksRenderJSON","responsibleId","ClearDeadline","deleteIcon","field","newsubcont","createElement","deadline","reloadRightSideBar","fireOnChangeEvent","__reloadBlock","reloadButtons","blockName","targetDomNode","MODE","INNER_HTML","BLOCK","IS_IFRAME","PATH_TO_TEMPLATES_TEMPLATE","PATH_TO_TASKS_TASK","PATH_TO_USER_PROFILE","NAME_TEMPLATE","FIRE_ON_CHANGED_EVENT","TASK_ID","doAction","actionName","operation","TasksTimerManager","start","stop","ID","batchOperations","taskOnTimer","reLoadInitTimerDataFromServer","status","returnValue","onDataRecieved","TASKS_TIMER","TASK_ON_TIMER","tasksRenderListItem","renderSecondsToHHMMSS","totalSeconds","bShowSeconds","pad","hours","Math","floor","minutes","seconds","result","substring","initTimer","selfTaskId","ynRunning","removeCustomEvent","state","onTaskTimerChange","addCustomEvent","stateIn","timerSpentTimeBlock1","timerSpentTimeBlock2","timerEstimateTimeBlock","switchStateTo","renderredTimeSpentText","renderredTimeEstimateTextWoSeconds","renderredTimeEstimateTextWithSeconds","action","TASK","TIME_SPENT_IN_LOGS","TIMER","RUN_TIME","TIME_ESTIMATE","timerData","TIMER_STARTED_AT","timerBlock","elmHeight","elmMargin","browser","IsIE","clientHeight","currentStyle","marginTop","marginBottom","defaultView","getComputedStyle","getPropertyValue"],"mappings":"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"}