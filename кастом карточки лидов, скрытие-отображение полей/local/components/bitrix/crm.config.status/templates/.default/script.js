var max_sort = {};

function addNewTableRow(tableID, regexp, rindex)
{
	var tbl = document.getElementById('tblLIST'+tableID);
	var tblS = document.getElementById('tblSAMPLE'+tableID);
	var cnt = tbl.rows.length;
	var oRow = tbl.insertRow(cnt);
	var col_count = tbl.rows[cnt-1].cells.length;
	
	if(typeof(max_sort[tableID])=='undefined' || max_sort[tableID] === null)
	{
		var inpSort = BX.findChild(tbl.rows[cnt-1], {'tag':'input','class':'sort-input'}, true);
		if(inpSort)
			max_sort[tableID] = parseInt(inpSort.value) + 10;
	}

	for(var i=0;i<col_count;i++)
	{
		var oCell = oRow.insertCell(i);
		oCell.className = tbl.rows[0].cells[i].className;

		var html = tblS.rows[0].cells[i].innerHTML;
		oCell.innerHTML = html.replace(regexp,
			function(html)
			{
				return html.replace('[n'+arguments[rindex]+']', '[n'+(cnt+1)+']');
			}
		);
	}

	if (true == BX.browser.IsSafari())
	{
		// Safari hack for apply className for cells added row
		tbl.tBodies[0].insertBefore(oRow, tbl.rows[cnt-1]);
		tbl.tBodies[0].insertBefore(tbl.rows[tbl.rows.length-1],tbl.rows[tbl.rows.length-2]);
	}

	var newSort = BX.findChild(tbl.rows[cnt], {'tag':'input','class':'sort-input'}, true);
	if(newSort)
	{
		newSort.value = max_sort[tableID];
		max_sort[tableID] += 10;
	}
}

function sort_up(button)
{
	var tableRow = BX.findParent(button, {'tag':'tr'});
	if(tableRow)
	{
		var upperRow = BX.findPreviousSibling(tableRow);
		if(upperRow && !BX.hasClass(upperRow, 'head'))
		{
			var table = BX.findParent(upperRow, {'tag':'table'});
			if(table)
			{
				var hiddens = update_hiddens(tableRow, upperRow);
				if(hiddens)
					table.tBodies[0].insertBefore(tableRow, upperRow);
			}
		}
	}
}

function sort_down(button)
{
	var tableRow = BX.findParent(button, {'tag':'tr'});
	if(tableRow)
	{
		var lowerRow = BX.findNextSibling(tableRow);
		if(lowerRow && !BX.hasClass(lowerRow, 'footer'))
		{
			var table = BX.findParent(lowerRow, {'tag':'table'});
			if(table)
			{
				var hiddens = update_hiddens(tableRow, lowerRow);
				if(hiddens)
					table.tBodies[0].insertBefore(lowerRow, tableRow);
			}
		}
	}
}

function update_hiddens(tableRow1, tableRow2)
{
	var hidden1 = BX.findChild(tableRow1, {'tag':'input','class':'sort-input'}, true);
	var hidden2 = BX.findChild(tableRow2, {'tag':'input','class':'sort-input'}, true);

	if(hidden1 && hidden2)
	{
		var sort1 = hidden1.value;
		var sort2 = hidden2.value;

		hidden1.value = sort2;
		hidden2.value = sort1;

		return new Array(hidden1, hidden2);
	}
	else
	{
		return false;
	}
}

function delete_item(button)
{
	var tableRow = BX.findParent(button, {'tag':'tr'});
	var tableRowCount = BX.findChildren(tableRow.parentNode, {'tag':'tr'}, true);

	if(tableRow && tableRowCount.length > 1)
	{
		var hidden = BX.findChild(tableRow, {'tag':'input','class':'sort-input'}, true);
		if(hidden)
		{
			var table = tableRow.parentNode;
			table.parentNode.appendChild(hidden);
			table.removeChild(tableRow);
		}
	}
}

function changeTab(el)
{

	if(($(el).attr('href')) != '#DEAL_STAGE'){
		$('#stages_list').addClass('status_tab_body');
	}

	if (!BX.hasClass(el, 'status_tab_active'))
	{
		var arTab = BX.findChild(BX('status_box'), {'tag':'a','class':'status_tab_active'}, true);
		if (arTab)
		{
			BX.removeClass(arTab, 'status_tab_active');
			BX(arTab.id+'_body').style.display = "none";
		}
		BX.addClass(el, 'status_tab_active');
		BX(el.id+'_body').style.display = "block";
		BX('ACTIVE_TAB').value = el.id;
	}		
}

function statusReset()
{
	BX('ACTION').value = 'reset';
	document.forms["crmStatusForm"].submit();
}

function recovery_name(id, name)
{
	console.log('id = '+id);
	console.log('name = '+name);

	BX(id).value = name;
}

if(typeof(BX.CrmStatusManager) == "undefined")
{
	BX.CrmStatusManager = function()
	{
		this._id = "";
		this._settings = {};
		this._requestIsRunning = false;
	};
	BX.CrmStatusManager.prototype =
	{
		initialize: function(id, settings)
		{
			this._id = BX.type.isNotEmptyString(id) ? id : "crm_status_mgr_" + Math.random().toString().substring(2);
			this._settings = settings ? settings : {};

			this._serviceUrl = this.getSetting("serviceUrl", "");
		},
		getId: function()
		{
			return this._id;
		},
		getSetting: function (name, defaultval)
		{
			return this._settings.hasOwnProperty(name) ? this._settings[name] : defaultval;
		},
		fixStatuses: function()
		{
			if(this._requestIsRunning)
			{
				return;
			}
			this._requestIsRunning = true;

			if(this._serviceUrl === "")
			{
				throw "Error: Service URL is not defined.";
			}

			BX.ajax(
				{
					url: this._serviceUrl,
					method: "POST",
					dataType: "json",
					data:
					{
						"ACTION" : "FIX_STATUSES"
					},
					onsuccess: BX.delegate(this._onRequestSuccsess, this),
					onfailure: BX.delegate(this._onRequestFailure, this)
				}
			);
		},
		_onRequestSuccsess: function(result)
		{
			this._requestIsRunning = false;
			BX.onCustomEvent(this, "ON_STATUS_FIXING_COMPLETE", [this]);
		},
		_onRequestFailure: function(result)
		{
			this._requestIsRunning = false;
		}
	};
	BX.CrmStatusManager.create = function(id, settings)
	{
		var self = new BX.CrmStatusManager();
		self.initialize(id, settings);
		return self;
	};
}

BX.ready(function(){

	var ok = false;

	$('#submit_buton_event').on('click',function(e){
		if(!ok){
			e.preventDefault();
			$('#stages_list').remove();
			ok = true;
			$('#submit_buton_event').click();
		}else {
			return;
		}
	});

	var el = $('#SELECTED_DEAL_TYPE_ID');
	var ids = [];

	BX.add_to_stages = function(id){

		if(ids.indexOf(id) == -1){

			ids.push(id);
			var table = $('#tblLISTDEAL_STAGE');
			var input = $('#selected_stages_'+id).html();
			var template =
				'<tr>'+
					'<td class="sort-arrow"><div class="sort-up" onclick="sort_up(this);" title="Переместить вверх"></div></td>'+
					'<td class="sort-arrow"><div class="sort-down" onclick="sort_down(this);" title="Переместить вниз"></div></td>'+
						'<td>' +
						'<input type="hidden" name="LIST[<?=$headerId?>][n0][SORT]" value="<?=($maxSort+10)?>" class="sort-input">'+
						   input +
						'</td>'+
					'<td>' +
					'<div class="delete-action" onclick="delete_item(this);" title=""></div></td>'+
				'</tr>';
			$(table).append(template);
		}

	};


	$(el).change(function(e){

		$('#stages_list').removeClass('status_tab_body');

		var id = $(e.target).find(":selected").attr('value');
		if(id !='-'){

			$('#add_rows_DEAL_STAGE').hide();

		}else{

			$('#stages_list').addClass('status_tab_body');
			$('#add_rows_DEAL_STAGE').show();

		}
		$.ajax(
			{
				url: '/local/components/bitrix/crm.config.status/ajax.php',
				method: "POST",
				dataType: "json",
				data:
				{
					"ACTION"  : "SELECTED_STATUS",
					"TYPE_ID" : id
				},
				success: function(data){
					ids = [];
					if(data.length > 0){
						var tr = $('#tblLISTDEAL_STAGE tr');
						$(tr).each(function(k,v){
							if(k > 0){
								$(v).remove();
							}
						});
						var table = $('#tblLISTDEAL_STAGE');
						for(var i in data){
							var obj = data[i];
							var s = '';

							if(obj.SYSTEM == 'N'){
								s = '<td><div class="delete-action" onclick="delete_item(this);" title="Удалить"></div></td>'
							}else{

								var stage = 'field-DEAL_STAGE-'+obj.STATUS_ID;
								s = '<td><div class="recovery-action" onclick="recovery_name(\''+stage+'\',\''+obj.NAME_INIT+'\');" title="Вернуть название"></div></td>';
							}
							var template =
							'<tr>'+
							'<td class="sort-arrow"><div class="sort-up" onclick="sort_up(this);" title="Переместить вверх"></div></td>'+
							'<td class="sort-arrow"><div class="sort-down" onclick="sort_down(this);" title="Переместить вниз"></div></td>'+
							'<td>'+
								'<input type="hidden" name="LIST[DEAL_STAGE]['+ obj.ID +'][SORT]" value="'+obj.SORT+'" class="sort-input">'+
								'<input type="text" size="35" name="LIST[DEAL_STAGE]['+obj.ID+'][VALUE]" id="field-DEAL_STAGE-'+obj.STATUS_ID+'" ' +
							'value="'+obj.NAME+'" class="value-input">'+
							'</td>'+s+
							'</tr>';
							ids.push(obj.ID);
							$(table).append(template);
						}
					}
				}
			});
		});
});

