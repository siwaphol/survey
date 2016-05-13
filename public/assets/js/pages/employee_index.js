$(function () {
	// body...
	employee_index.init();
})
var emp_id;
employee_index = {
	init : function (){
		'use strict';

		employee_index.datatables_employeeTable(); 			//Initialize the employees table

		employee_index.fancytree_positionDivisionTree();
	},
	datatables_employeeTable : function (){
		var employeeList = $('#employeeList');

		// Retrieve data from DB via jquery ajax
		$.ajax({
			url: api_url_base, // api/v1/employee/
			type: 'GET',
			dataType: 'json',
			contentType : 'applicaiton/json',
			success: function (response_data) {
				datatables(response_data); //Calling datatables generator if there is data when callback complete. 
			},
			error: function () {
				alert('Something went wrong or api does not return any data'); //Otherwise, throw an error.
			}
		});

		// Datatables generator definition
		var datatables = function (data){
			employeeList.DataTable({
				"data" : data,
				"processing" : true,
				"columnDefs": [
			    	{ className: "hidden", targets: [ 0 ] }
			    ],
				columns : [	
					{ title: 'id', data: 'id'},				 
					{ title: 'ชื่อ', data : 'first_name_th' },
					{ title: 'รหัสพนักงาน', data: 'code' },
					{ title: 'ตำแหน่งงานหลัก', data: 'nick_name' }
				],
			});

		}

		// Event handler when any row on the table was clicked
		employeeList.on('click','tbody > tr',function  (e){
			e.preventDefault();
			e.stopPropagation();
		
			show_Employee_Preview($(this).find('td').eq(0).text()); //show employee details
				
			$('tr.bg-success').removeClass('bg-success'); // remove bg color from pevious row
			$(this).addClass('bg-success');	//add bg color to row that was cliked			

		}).find('tr').eq(3).click();
	},
	fancytree_positionDivisionTree : function () {
		// Fancy tree initialize
		$(".position-tree").fancytree({
	        // extensions: ["select"],
	        selectMode: 1,
	        source: $.ajax({
	                url: "/api/v1/position/0", // node with no parent
	                dataType: "json",
	                cache: false
	            }),
	        lazyLoad: function(event, data) {
	            var node = data.node;

	            data.result = {
	                url: "/api/v1/position/"+ node.key,
	                cache: false
	            };
	        }
	    });

	    $(".division-tree").fancytree({
	        // extensions: ["select"],
	        selectMode: 1,
	        source: $.ajax({
	                url: "/api/v1/division/0", // node with no parent
	                dataType: "json",
	                cache: false
	            }),
	        lazyLoad: function(event, data) {
	            var node = data.node;

	            data.result = {
	                url: "/api/v1/division/"+ node.key,
	                cache: false
	            };
	        }
	    });
	}
}
function show_Employee_Preview (id) {
	emp_id = id; //Global variable stored current employee_id
	
	//Call api for retrieve the employee details.
	$.ajax({
		url: api_url_base + '/' + emp_id, 		// ~/api/v1/employee/{id}
		type: 'GET',
		dataType : 'json',
		contentType : 'applicaiton/json',
		success : function(response_data){
			show_preview(response_data); 		//if the emp_id is valid then show the details 
		},
		error : function () {
			alert('Something went wrong or api does not return any data'); //Otherwise, throw an error.
		}
	});

	var show_preview = function (data) {
		var item = data[0],
			$employee_info_preview_container = $('#employee_info_preview');

		var animation = $employee_info_preview_container.data('animation'); 

		var $employee_preview_template = $('#employee_info_template'),			//Select template for employee details presentation.
			template = $employee_preview_template.html(),						//Get all template components preparing for compile.
			template_compiled = Handlebars.compile(template); 					//Complied template

		var context = { // Declare the data that going to use in the content
				employee : {
					code: item.code,
					name: item.first_name_th + ' ' + item.last_name_th,
					email: item.email,
					phone: item.mobile,
					address : item.address
				}
		},
		theCompiledHtml = template_compiled(context);							// Dump the data to the complied html
		$employee_info_preview_container.html(theCompiledHtml);					// Dump the content to selected html element

		//Call api for the data for datatables
		var employeeDetailsTable = $('#employeeDetailsList');
		$.ajax({
			url : '/api/v1/div_emp_pos/' + emp_id,								
			type: 'GET',
			dataType : 'json',
			contentType : 'application/json',
			success : function (response_data) {
				detailsTable(response_data);
			},
			error : function () {
				swal("Whoop something went wrong");
			}
		});

		//Write the data returned from api to Datatables.  
		var detailsTable = function (data) {
			employeeDetailsTable.DataTable({
				"data" : data,
				"processing" : true,
				"columnDefs" : [
					{ targets: [0], className: "hidden" }
				],
				columns : [
					{ title: 'id', data: 'id'},
					{ render: function () {	return '<input type="checkbox" class="styled"/>';} },
					{ title: 'ตำแหน่งงานหลัก', data: 'position_name'},
					{ title: 'สาขาหลัก', data: 'division_name'}
				],
				"bFilter" : false,
				"bInfo" : false,
				"paging" : false
			});
			// Initial checkbox and radio button for datatables.
			$('.styled').uniform({
		    	radioClass: 'choice'
			});

		}
		$employee_info_preview_container.velocity( "transition.fadeIn",{ duration: 1000}); //Show the employee details panel.

		///////////////////////////////////////////////// After render is complete //////////////////////////////////////////////////////////////
		
		// Initial checkbox and radio button.
		$('.styled').uniform({
	    	radioClass: 'choice'
		});

		// close all sub menu.
		$('i.icon-gear').click();

		// If the row on the employee details table was clicked.
		employeeDetailsTable.on('click','tbody > tr',function (e) {
			e.preventDefault();
			e.stopPropagation();
			var topMenu = $('.heading-elements ul.dropdown-menu-right a.hidden');

			$('#employeeDetailsList tr.bg-success').removeClass('bg-success'); // remove bg color from pevious row
			$(this).addClass('bg-success');	//add bg color to row that was cliked	
			topMenu.removeClass("hidden")
			
		});


		// If menu "New" was clicked.
		$('#newMenu').on('click',function (e){
			$('#editDataModal').modal();
			// Event handler when the submit button of the form was clicked.
		    $("#editDataModal").on('click','button#submit', function() {

			    var position_selected_node = $(".position-tree").fancytree("getActiveNode"); // Get all selected node on the position tree
			    var division_selected_node = $(".division-tree").fancytree("getActiveNode"); // Get all selected node on the division tree
		      	var $effective_date = moment($('#effective_date').val()).format('YYYY-MM-DD'); // Get an effective date and convert to sqlsrv acceptable format.
		      	// console.log($effective_date);
		      	
		      	if(position_selected_node != null || division_selected_node != null ) { //Postiton and Division must be selected or promt up the error message box.
		      		$.ajax({
			            url: '/api/v1/div_emp_pos',
			            method: "POST",
			            data: { employee_id: emp_id, position_id: position_selected_node.key, division_id: division_selected_node.key, effective_date: $effective_date },
			            success: function () {											// if it succeed to write to the database then promp up the success message box. 
				            swal({
				                title: "การร้องขอสำเร็จ",
				                text: "ข้อความเพิ่มเติม",
				                confirmButtonColor: "#4CAF50",
				                type: "success"
				            });
			            },
			            error : function(){
							// data must contain error message if fails
				            swal({
				                title: "เกิดข้อผิดพลาด",
				                text: "Something went wrong",
				                confirmButtonColor: "#EF5350",
				                type: "error"
				            });
			            }       
				   	});
		      	}else {
		      		// data must contain error message if fails
		            swal({
		                title: "เกิดข้อผิดพลาด",
		                text: "กรุณาเลือกตำแหน่งและสาขา",
		                confirmButtonColor: "#EF5350",
		                type: "error"
		            });
		      	}

		      	// console.log('position_selected_node: ' + position_selected_node.key + ' ' + 'division_selected_node: ' + division_selected_node.key + ' ' + 'emp_id: ' +emp_id );
		    	
			});
		});
		
		// If menu "Edit" was clicked.
		$('#editMenu').on('click',function (e){
			// Do stuffs here


			$('#editDataModal').modal();

			// Event handler when the submit button of the form was clicked.
		    $("#editDataModal").on('click','button#submit', function() {
		    	// Do stuffs here
			});
		});

	}

}
