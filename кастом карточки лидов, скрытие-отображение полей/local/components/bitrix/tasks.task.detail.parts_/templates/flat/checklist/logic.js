BX.namespace('Tasks.Component');

(function(){

	// nested object
	var checkListItem = function(itemData, parent)
	{
		this.data = itemData.data;

		if(typeof itemData.can != 'undefined' && typeof itemData.can.ACTION != 'undefined')
		{
			this.can = itemData.can.ACTION;
		}
		else
		{
			this.can = {};
		}

		var checked = this.data.IS_COMPLETE == 'Y';

		if(typeof this.can.REMOVE == 'undefined')
		{
			this.can.REMOVE = true;
		}
		if(typeof this.can.MODIFY == 'undefined')
		{
			this.can.MODIFY = true;
		}

		// we got no templating mechanism yet, so a couple of temporal spikes
		this.data.CHECKED = checked ? 'checked' : '';
		this.data.REMOVE_HIDDEN = this.can.REMOVE ? '' : ' hidden';
		this.data.MODIFY_HIDDEN = this.can.MODIFY ? '' : ' hidden';

		this.scope = parent.getNodeByTemplate('item', this.data)[0];
		this.mode = 'read';

		this.ctrls = {
			btnEdit: parent.control('item-btn-edit', this.scope),
			btnDelete: parent.control('item-btn-delete', this.scope),
			btnApply: parent.control('item-btn-apply', this.scope),
			btnCheck: parent.control('item-btn-check', this.scope),

			titleEdit: parent.control('item-title-edit', this.scope),
			title: parent.control('item-title', this.scope),

			// hidden inputs
			sortIndexFld: parent.control('item-sort-index-fld', this.scope),
			isCompleteFld: parent.control('item-is-complete-fld', this.scope),
		};

		// save item id to data-* for each significant node used
		BX.data(this.scope, 'item-id', this.id());
		for(var k in this.ctrls)
		{
			BX.data(this.ctrls[k], 'item-id', this.id());
		}

		this.parent = parent;
	}
	BX.merge(checkListItem.prototype, {
		isComplete: function(flag)
		{
			if(typeof flag !== 'undefined')
			{
				this.data.IS_COMPLETE = flag ? 'Y' : 'N';
				this.redraw();

				return;
			}

			return this.data.IS_COMPLETE == 'Y';
		},
		sortIndex: function(index)
		{
			if(typeof index !== 'undefined')
			{
				this.data.SORT_INDEX = parseInt(index);
				this.redraw();

				return;
			}

			return parseInt(this.data.SORT_INDEX);
		},
		id: function()
		{
			return this.data.ID;
		},
		title: function(title)
		{
			if(typeof title != 'undefined')
			{
				this.data.TITLE = title;
				this.redraw();

				return;
			}

			return this.data.TITLE;
		},
		destruct: function()
		{
			BX.remove(this.scope);
			this.scope = null;
			this.ctrls = null;
			this.data = null;
		},
		setEditMode: function()
		{
			if(this.mode == 'edit')
			{
				return;
			}

			this.mode = 'edit';
			this.ctrls.titleEdit.value = this.data.TITLE;

			this.redraw();
		},
		setReadMode: function()
		{
			if(this.mode == 'read')
			{
				return;
			}

			this.mode = 'read';
			this.redraw();
		},
		applyChanges: function()
		{
			var newTitle = this.ctrls.titleEdit.value.toString();

			if(newTitle.length > 0)
			{
				this.setReadMode();
				this.title(newTitle);
			}
		},
		handleDelete: function()
		{
			if(this.mode == 'edit') // discard changes
			{
				this.ctrls.titleEdit.value = this.data.TITLE;
				this.setReadMode();
				return false;
			}
			else
			{
				return true;
			}
		},
		redraw: function()
		{
			if(this.mode == 'edit')
			{
				this.parent.switchControl(this.scope, 'on');
			}
			else
			{
				this.parent.switchControl(this.scope, 'off');
			}

			// in try-catch, because some items may be null-ed

			try
			{
				this.ctrls.title.innerHTML = BX.util.htmlspecialchars(this.data.TITLE);
			}
			catch(e)
			{
			}

			try
			{
				this.ctrls.btnCheck.checked = this.isComplete();
			}
			catch(e)
			{
			}

			try
			{
				this.ctrls.isCompleteFld.value = this.isComplete() ? 'Y' : 'N';
			}
			catch(e)
			{
			}

			try
			{
				this.ctrls.sortIndexFld.value = parseInt(this.sortIndex());
			}
			catch(e)
			{
			}
		}
	});

	BX.Tasks.Component.TaskDetailPartsChecklist = BX.Tasks.UI.Widget.extend({
		methods: {
			construct: function()
			{
				this.code('checklist');

				BX.merge(this.vars, {
					items: {},
					newIncrement: 0
				});

				var dd = new BX.Tasks.UI.DragAndDrop({
					createFlying: BX.delegate(function(node){

						var itemId = BX.data(node, 'item-id');
						var item = this.vars.items[itemId];

						return this.getNodeByTemplate('item-flying', {
							'TITLE': item.title(),
							'CHECKED': item.isComplete() ? 'checked' : '',
							'ID': item.id()
						})[0];

					}, this)
				});
				dd.bindDropZone(this.control('items-ongoing'));
				dd.bindDropZone(this.control('items-complete'));

				this.instances = {
					dragNDrop: dd
				};

				this.bindEvents();
			},

			bindEvents: function()
			{
				// for each existing item
				this.bindDelegateControl('click', 'item-btn-edit', this.passCtx(this.setItemEdit));
				this.bindDelegateControl('click', 'item-btn-delete', this.passCtx(this.setItemCancel));
				this.bindDelegateControl('click', 'item-btn-apply', this.passCtx(this.setItemApply));
				this.bindDelegateControl('change', 'item-btn-check', this.passCtx(this.setItemToggle));
				this.bindDelegateControl('keydown', 'item-title-edit', this.passCtx(this.setItemApplyOnKeydown));

				// new item form
				this.bindDelegateControl('click', 'add-item-form-open', this.passCtx(this.newItemOpenForm));
				this.bindDelegateControl('click', 'add-item', this.passCtx(this.newItemAdd));
				this.bindDelegateControl('keydown', 'add-item-title', this.passCtx(this.newItemTitleKeydown));

				// dropdown
				this.instances.dragNDrop.bindEvent('item-relocated', BX.delegate(this.itemRelocated, this));
			},

			itemRelocated: function(node, listNode, nodeScope)
			{
				var itemId = BX.data(node, 'item-id');
				var itemInst = this.vars.items[itemId];

				var toComplete = (listNode == this.control('items-complete'));

				itemInst.isComplete(toComplete);

				// relocate
				var afterItemId = false;
				if(nodeScope.after !== null)
				{
					afterItemId = BX.data(nodeScope.after, 'item-id');
				}
				if(toComplete && afterItemId === false) // try to get the last item of an ongoing part, if you insert to the top of complete part
				{
					afterItemId = this.getOngoingItemByGreatestSortIndex();
				}

				if(afterItemId != itemId)
				{
					this.shiftSortIndexes(itemId, afterItemId);
				}

				this.redraw();
			},

			shiftSortIndexes: function(itemId, afterItemId)
			{
				var index = this.getSortedItemList();
				var newArr = [];

				if(afterItemId === false)
				{
					newArr.push(itemId);
				}

				for(var k = 0; k < index.length; k++)
				{
					if(index[k].id == itemId)
					{
						continue;
					}

					newArr.push(index[k].id);

					if(afterItemId !== false && index[k].id == afterItemId)
					{
						newArr.push(itemId);
					}
				}

				var i = 0;
				for(var k = 0; k < newArr.length; k++)
				{
					this.vars.items[newArr[k]].sortIndex(i);
					i++;
				}
			},

			setItemEdit: function(btn)
			{
				this.switchControl(this.control('add-item-form'), 'off');

				this.doOnItem(btn, function(itemInst){
					itemInst.setEditMode();
				});
			},

			setItemCancel: function(btn)
			{
				this.doOnItem(btn, function(itemInst){
					if(itemInst.handleDelete())
					{
						this.deleteItem(itemInst.id());
					}
				});
			},

			setItemApply: function(btn)
			{
				this.doOnItem(btn, function(itemInst){
					itemInst.applyChanges();
				});
			},

			setItemApplyOnKeydown: function(btn, e)
			{
				if(this.isEnter(e))
				{
					this.setItemApply(btn);

					BX.PreventDefault(e);
				}
			},

			setItemToggle: function(btn)
			{
				this.doOnItem(btn, function(itemInst){
					itemInst.isComplete(btn.checked);
				});

				this.redraw();
			},

			doOnItem: function(node, callback)
			{
				var itemId = BX.data(node, 'item-id');

				if(typeof itemId != 'undefined' && itemId !== null)
				{
					callback.apply(this, [this.vars.items[itemId]]);
				}
			},

			addItem: function(itemData)
			{
				if(itemData.data.TITLE.toString().length == 0)
				{
					return;
				}

				var itemInst = new checkListItem(itemData, this);

				this.vars.items[itemInst.id()] = itemInst;
			},

			deleteItem: function(id)
			{
				if(typeof this.vars.items[id] == 'undefined')
				{
					return;
				}

				var itemInst = this.vars.items[id];
				this.instances.dragNDrop.unBindNode(itemInst.scope);
				itemInst.destruct();

				this.vars.items[id] = null;
				delete(this.vars.items[id]);

				this.redraw();
			},

			getSortedItemList: function()
			{
				var index = [];

				// first, resort items by SORT_INDEX
				for(var k in this.vars.items)
				{
					index.push({
						ix: this.vars.items[k].sortIndex(),
						id: this.vars.items[k].id()
					});
				}

				return index.sort(function(a,b){
					if(a.ix < b.ix)
					{
						return -1;
					}
					else if(a.ix > b.ix)
					{
						return 1;
					}

					return 0;
				});
			},

			redraw: function()
			{
				var complete = 0;
				var total = 0;
				var index = this.getSortedItemList();

				// then reorder and classify nodes without physical delete
				var ongoingPool = BX.create('div');
				var completePool = BX.create('div');

				for(var k = 0; k < index.length; k++)
				{
					total++;
					if(this.vars.items[index[k].id].isComplete())
					{
						complete++;
					}

					var itemInst = this.vars.items[index[k].id];

					BX.append(itemInst.scope, itemInst.isComplete() ? completePool : ongoingPool);

					this.instances.dragNDrop.bindNode(itemInst.scope, {handle: this.control('item-drag', itemInst.scope)});
				}

				this.moveNodePool(completePool, this.control('items-complete'));
				this.moveNodePool(ongoingPool, this.control('items-ongoing'));

				// update counters

				this.showControlIf('complete-block', complete > 0);

				// try-catch, because some elements may be null-ed

				try
				{
					this.control('ongoing-counter').innerHTML = total - complete;
				}
				catch(e)
				{
				}

				try
				{
					this.control('complete-counter').innerHTML = complete;
				}
				catch(e)
				{
				}
			},

			moveNodePool: function(from, to)
			{
				while(from.childNodes.length > 0)
				{
					BX.append(from.childNodes[0], to);
				}
			},

			load: function(data, can)
			{
				if(BX.type.isPlainObject(data))
				{
					for(var k in data)
					{
						var item = {data: BX.clone(data[k]), can: {}};

						if(typeof can[k] != 'undefined')
						{
							item.can = BX.clone(can[k]);
						}

						this.addItem(item);
					}

					this.redraw();
				}
			},

			getOngoingItemByGreatestSortIndex: function()
			{
				var max = 0;
				var maxItemId = false;
				for(var k in this.vars.items)
				{
					var index = this.vars.items[k].sortIndex();

					if(index > max && !this.vars.items[k].isComplete())
					{
						max = index;
						maxItemId = k;
					}
				}

				return maxItemId;
			},

			getGreatestSortIndex: function()
			{
				var max = 0;
				for(var k in this.vars.items)
				{
					var index = this.vars.items[k].sortIndex();

					if(index > max)
					{
						max = index;
					}
				}

				return max;
			},

			// new item form

			newItemOpenForm: function()
			{
				this.switchControl(this.control('add-item-form'), 'on');
				this.control('add-item-title').focus();
			},

			newItemTitleKeydown: function(node, e)
			{
				if(this.isEnter(e))
				{
					this.newItemAdd();

					BX.PreventDefault(e);
				}
			},

			newItemAdd: function()
			{
				if(this.control('add-item-title').value.toString().length < 1)
				{
					return;
				}

				var data = {
					ID: "n"+(this.vars.newIncrement++),
					IS_COMPLETE: "N",
					SORT_INDEX: this.getGreatestSortIndex() + 1,
					TITLE: this.control('add-item-title').value
				};

				this.control('add-item-title').value = '';
				this.control('add-item-title').focus();

				this.addItem({data: data});
				this.redraw();
			},

			// util

			showControlIf: function(id, condition)
			{
				BX[condition ? 'removeClass' : 'addClass'](this.control(id), 'hidden');
			},

			switchControl: function(node, way)
			{
				way = way == 'on';

				if(way)
				{
					BX.addClass(node, 'on');
					BX.removeClass(node, 'off');
				}
				else
				{
					BX.removeClass(node, 'on');
					BX.addClass(node, 'off');
				}
			},

			isEnter: function(e)
			{
				e = e || window.event;

				return e.keyCode == 13;
			}
		}
	});

})();