<script type="text/javascript">
//$(document).ready(function(){
	jQuery("#treeview_r1").kendoTreeView({
	checkboxes: {
	checkChildren: true,
	//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
	/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"
	},
	template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
	},*/
	template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text #</span></label>"
	},
	//<label class='custom-control custom-checkbox'><input type='checkbox' class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>
	
	//template: "<label class="custom-control custom-checkbox"><input type="checkbox" #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class="custom-control-label">#= item.add_info #</span></label>"
	check: onCheck,
	dataSource: <?php echo $menu ?>
	});
	
	
//});
// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
		treeView = jQuery("#treeview2").data("kendoTreeView"),
		message;
		jQuery("#result").html(message);
}
</script>