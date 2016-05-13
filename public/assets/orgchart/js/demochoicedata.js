var division = [{"id": 1 , "name":"สำนักงานใหญ่", "parent": 0, "type":"สำนักงาน" }
	,{"id": 2 , "name":"ปฏิบัติการ", "parent": 1, "type":"ด้าน" }
	,{"id": 3 , "name":"สนับสนุน", "parent": 1, "type":"ด้าน" }
	,{"id": 4 , "name":"บัญชี", "parent": 1, "type":"ด้าน" }
	,{"id": 5 , "name":"กลยุทธ์", "parent": 1, "type":"ฝ่าย" }
	,{"id": 6 , "name":"ปฏิบัติการ 1", "parent": 2, "type":"ฝ่าย" }
	,{"id": 7 , "name":"ปฏิบัติการ 2", "parent": 2, "type":"ฝ่าย" }
	,{"id": 8 , "name":"ปฏิบัติการ 3", "parent": 2, "type":"ฝ่าย" }
	,{"id": 9 , "name":"ปฏิบัติการ 4", "parent": 2, "type":"ฝ่าย" }
	,{"id": 10 , "name":"ข้อมูลปฏิบัติการและ IT", "parent": 2, "type":"ฝ่าย" }
	,{"id": 11 , "name":"ปฏิบัติการเภสัชกร", "parent": 2, "type":"ฝ่าย" }
	,{"id": 12 , "name":"ธุรการ", "parent": 3, "type":"ฝ่าย" }
	,{"id": 13 , "name":"จัดซื้อ", "parent": 3, "type":"ฝ่าย" }
	,{"id": 14 , "name":"พัฒนาธุรกิจ", "parent": 3, "type":"ฝ่าย" }
	,{"id": 15 , "name":"แฟรนไชน์", "parent": 3, "type":"ฝ่าย" }
	,{"id": 16 , "name":"วิศวกรรมซ่อมบำรุง", "parent": 3, "type":"ฝ่าย" }
	,{"id": 17 , "name":"พัฒนาบุคคล", "parent": 3, "type":"ฝ่าย" }
	,{"id": 18 , "name":"บุคคล", "parent": 1, "type":"ฝ่าย" }
	,{"id": 19 , "name":"การเงิน", "parent": 1, "type":"ฝ่าย" }
	,{"id": 20 , "name":"บัญชี", "parent": 4, "type":"ฝ่าย" }
	 ];

var position = [{"id": 1 , "name":"ประธานกรรมการ", "parent": 0 }
	,{"id": 2 , "name":"รองประธานกรรมการ", "parent": 1 }
	,{"id": 3 , "name":"ผู้ช่วยรองประธานกรรมการ", "parent": 2 }
	,{"id": 4 , "name":"กรรมการผู้จัดการ", "parent": 2 }

	,{"id": 5 , "name":"หัวหน้าฝ่ายบุคคล", "parent": 2 }
	,{"id": 6 , "name":"หัวหน้าฝ่ายการเงิน", "parent": 2 }
	,{"id": 7 , "name":"หัวหน้าด้านบัญชี", "parent": 2 }
	,{"id": 8 , "name":"หัวหน้าฝ่ายบัญชี", "parent": 7 }
	,{"id": 9 , "name":"หัวหน้าด้านสนับสนุน", "parent": 4 }
	,{"id": 10 , "name":"หัวหน้าด้านปฏิบัติการ", "parent": 4 }
	,{"id": 11 , "name":"หัวหน้าฝ่ายกลยุทธ์", "parent": 4 }
	,{"id": 12 , "name":"หัวหน้าฝ่ายปฏิบัติการ 1", "parent": 10}
	,{"id": 13 , "name":"หัวหน้าฝ่ายปฏิบัติการ 2", "parent": 10}
	,{"id": 14 , "name":"หัวหน้าฝ่ายปฏิบัติการ 3", "parent": 10}
	,{"id": 15 , "name":"หัวหน้าฝ่ายปฏิบัติการ 4", "parent": 10}
	,{"id": 16 , "name":"หัวหน้าฝ่ายข้อมูลปฏิบัติการและ IT", "parent": 10}
	,{"id": 17 , "name":"หัวหน้าฝ่ายปฏิบัติการเภสัชกร", "parent": 10}
	,{"id": 18 , "name":"หัวหน้าฝ่ายธุรการ", "parent": 9}
	,{"id": 19 , "name":"หัวหน้าฝ่ายจัดซื้อ", "parent": 9}
	,{"id": 20 , "name":"หัวหน้าฝ่ายพัฒนาธุรกิจ", "parent": 9}
	,{"id": 21 , "name":"หัวหน้าฝ่ายแฟรนไชน์", "parent": 9}
	,{"id": 22 , "name":"หัวหน้าฝ่ายวิศวกรรมซ่อมบำรุง", "parent": 9}
	,{"id": 23 , "name":"หัวหน้าฝ่ายพัฒนาบุคคล", "parent": 9}
	 ];

var real_data = [{"id": 1 ,"name":"วรกร ตันตรานนท์", "position_id": 1, "division_id": 1}
	,{"id": 2 ,"name":"ฤทัยรัตน วิสิทธิ์", "position_id": 2, "division_id": 1}
	,{"id": 3 ,"name":"ศะมะนี ตันตรานนท์", "position_id": 3, "division_id": 1}
	,{"id": 4 ,"name":"วรวัชร ตันตรานนท์", "position_id": 2, "division_id": 1}
	,{"id": 5 ,"name":"ปราณี กีฬาแปง", "position_id": 5, "division_id": 18}
	,{"id": 6 ,"name":"วันดี ศรีสุวรรณ", "position_id": 5, "division_id": 19}
	,{"id": 7 ,"name":"วันทนา รัตนาคาร", "position_id": 7, "division_id": 4}
	,{"id": 8 ,"name":"โชติกา สมประดี", "position_id": 10, "division_id": 20}

	,{"id": 9 ,"name":"วธัญญู ตันตรานนท์", "position_id": 6, "division_id": 5}
	,{"id": 10 ,"name":"บวรเวทย์ ตันตรานนท์", "position_id": 8, "division_id": 2}
	,{"id": 11 ,"name":"วธัญญู ตันตรานนท์", "position_id": 8, "division_id": 3}

	,{"id": 12 ,"name":"ศิริรัตน์ จงก้าวหน้า", "position_id": 9, "division_id": 6}
	,{"id": 13 ,"name":"บัญชา บ้านเรือน", "position_id": 9, "division_id": 7}
	,{"id": 14 ,"name":"อดุลย์ เสียงสนั่น", "position_id": 9, "division_id": 8}
	,{"id": 15 ,"name":"ประมวล วิรัตน์เกษม", "position_id": 9, "division_id": 9}
	,{"id": 16 ,"name":"อภิสิทธ์ กอนแสง", "position_id": 9, "division_id": 10}
	,{"id": 17 ,"name":"วธัญญู ตันตรานนท์", "position_id": 9, "division_id": 11}

	,{"id": 18 ,"name":"วรรณา ถินยม", "position_id": 9, "division_id": 12}
	,{"id": 19 ,"name":"วรรณา ถินยม", "position_id": 9, "division_id": 13}
	,{"id": 20 ,"name":"เกศแก้ว สุภา", "position_id": 9, "division_id": 14}
	,{"id": 21 ,"name":"เกศแก้ว สุภา", "position_id": 9, "division_id": 15}
	,{"id": 22 ,"name":"วธัญญู ตันตรานนท์", "position_id": 9, "division_id": 16}
	,{"id": 23 ,"name":"มนชาต เชื้อดวงผุย", "position_id": 9, "division_id": 17}
	];

var items = [];

var view2_items = {};
var view3_items = {};

var top_user = {};
top_user.division_id = 1;
top_user.position_id = 1;

var view2 = {};
view2.division_id = 2; //this is for test only
view2.position_id = 2; //this is for test only
var view3 = {};
view3.division_id = 3; //this is for test only
view3.position_id = 3; //this is for test only

var current_division_id = 1;
var current_position_id = 1;

var position_array = [];
var division_array = [];

//maybe remove an item from old array after push to new   array
function loopDivision(division_id){
    var results = $.grep(division, function(e){ return e.parent === division_id; });
    for (var i = 0; i < results.length; i++) {
    	division_array.push(results[i]);
    }
    for (var i = 0; i < results.length; i++) {
    	loopDivision(results[i].id);
    }
}

function loopPosition(position_id){
    var results = $.grep(position, function(e){ return e.parent === position_id; });
 //    var new_item = {"id":count, "parent": parent_id, "description": position_array[j], "label": name};
	// items.push(new_item);
    for (var i = 0; i < results.length; i++) {
    	// position_array.push(results[i]);
   		var users = $.grep(real_data, function(e){ return e.position_id === results[i].id; });
   		for (var j = 0; j < users.length; j++) {
   			var parent_id = position_id === 0 ? null: users[j].parent;
   			var new_item = {"id":users[j].id, "parent": parent_id, "description": results[i].name, "label": users[j].name};
   			items.push(new_item);
   		};
    }
    for (var i = 0; i < results.length; i++) {
    	loopPosition(results[i].id);
    }
}
//end linked list array init


loopDivision(0);
for (var i = 0; i < division_array.length; i++) {
    $('#test').append('<p>'+division_array[i].name+'</p>');
}
// for(int i = 0 ; i < real_data.length; i++){
//     if(i == 0){
//         var result = $.grep(real_data, function(e){ return e.position_id == top_user.position_id && e.division_id == top_user.division_id; });
//     	items[i] = {"id":i, "parent":null, description:"", image:"assets/orgchart/images/photos/a.png", groupTitleColor: "", label: result.name};
//     } else {
        
//         var result = $.grep(real_data, function(e){ return e.position_id == top_user.position_id && e.division_id == top_user.division_id; });
//     }
// }

// $('#test').append('<p>'+division_array[i].name+'</p>');
var parent_id = null;
var count = 0;

for (var i = 0; i < division_array.length; i++) {
	for (var j = 0; j < position_array.length; j++) {
		
		if(i != 0){
			var results = $.grep(items, function(e){ return e.division_id === i && e.position_id === j; });
		}
		var new_item = {"id":count, "parent": parent_id, "description": position_array[j], "label": name};
		items.push(new_item);
	}	
}