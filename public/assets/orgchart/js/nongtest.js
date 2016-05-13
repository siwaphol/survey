    var division = [{"id": 1 , "name":"ใหญ่", "parent": 0, "type":"สำนักงาน" }
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

var position = [{"id": 1 , "name":"ประธานกรรมการ", "parent": 0 ,itemTitleColor: "#4169e1"}
  ,{"id": 2 , "name":"รองประธานกรรมการ", "parent": 1 ,itemTitleColor: "#4b0082"}
  ,{"id": 3 , "name":"ผู้ช่วยรองประธานกรรมการ", "parent": 2 ,itemTitleColor: "#a6f068"}
  ,{"id": 4 , "name":"กรรมการผู้จัดการ", "parent": 2 ,itemTitleColor: "#ddd574"}

  ,{"id": 5 , "name":"หัวหน้าฝ่ายบุคคล", "parent": 2 ,itemTitleColor: "#0e9c48"}
  ,{"id": 6 , "name":"หัวหน้าฝ่ายการเงิน", "parent": 2 ,itemTitleColor: "#4d8dda"}
  ,{"id": 7 , "name":"หัวหน้าด้านบัญชี", "parent": 2 ,itemTitleColor: "#C57F7F"}
  ,{"id": 8 , "name":"หัวหน้าฝ่ายบัญชี", "parent": 7 ,itemTitleColor: "#C57F7F"}
  ,{"id": 9 , "name":"หัวหน้าด้านสนับสนุน", "parent": 4 ,itemTitleColor: "#64644b"}
  ,{"id": 10 , "name":"หัวหน้าด้านปฏิบัติการ", "parent": 4 ,itemTitleColor: "#797865"}
  ,{"id": 11 , "name":"หัวหน้าฝ่ายกลยุทธ์", "parent": 4 ,itemTitleColor: "#ab7636"}
  ,{"id": 12 , "name":"หัวหน้าฝ่ายปฏิบัติการ 1", "parent": 10 ,itemTitleColor: "#e030d5"}
  ,{"id": 13 , "name":"หัวหน้าฝ่ายปฏิบัติการ 2", "parent": 10 ,itemTitleColor: "#9904fc"}
  ,{"id": 14 , "name":"หัวหน้าฝ่ายปฏิบัติการ 3", "parent": 10 ,itemTitleColor: "#9b683a"}
  ,{"id": 15 , "name":"หัวหน้าฝ่ายปฏิบัติการ 4", "parent": 10 ,itemTitleColor: "#a54444"}
  ,{"id": 16 , "name":"หัวหน้าฝ่ายข้อมูลปฏิบัติการและ IT", "parent": 10 ,itemTitleColor: "#5d561f"}
  ,{"id": 17 , "name":"หัวหน้าฝ่ายปฏิบัติการเภสัชกร", "parent": 10 ,itemTitleColor: "#861bcb"}
  ,{"id": 18 , "name":"หัวหน้าฝ่ายธุรการ", "parent": 9 ,itemTitleColor: "#4daded"}
  ,{"id": 19 , "name":"หัวหน้าฝ่ายจัดซื้อ", "parent": 9 ,itemTitleColor: "#c76928"}
  ,{"id": 20 , "name":"หัวหน้าฝ่ายพัฒนาธุรกิจ", "parent": 9 ,itemTitleColor: "#71211e"}
  ,{"id": 21 , "name":"หัวหน้าฝ่ายแฟรนไชน์", "parent": 9 ,itemTitleColor: "#cf0a81"}
  ,{"id": 22 , "name":"หัวหน้าฝ่ายวิศวกรรมซ่อมบำรุง", "parent": 9 ,itemTitleColor: "#40d9d2"}
  ,{"id": 23 , "name":"หัวหน้าฝ่ายพัฒนาบุคคล", "parent": 9 ,itemTitleColor: "#dcb18e"}
   ];

var real_data = [{"id": 1 ,"name":"วรกร ตันตรานนท์", "position_id": 1, "division_id": 1}
  ,{"id": 2 ,"name":"ฤทัยรัตน วิสิทธิ์", "position_id": 2, "division_id": 1}
  ,{"id": 3 ,"name":"ศะมะนี ตันตรานนท์", "position_id": 3, "division_id": 1}
  ,{"id": 4 ,"name":"วรวัชร ตันตรานนท์", "position_id": 4, "division_id": 1}
  ,{"id": 5 ,"name":"ปราณี กีฬาแปง", "position_id": 5, "division_id": 18}
  ,{"id": 6 ,"name":"วันดี ศรีสุวรรณ", "position_id": 6, "division_id": 19}
  ,{"id": 7 ,"name":"วันทนา รัตนาคาร", "position_id": 7, "division_id": 4}
  ,{"id": 8 ,"name":"โชติกา สมประดี", "position_id": 8, "division_id": 20}

  ,{"id": 9 ,"name":"วธัญญู ตันตรานนท์", "position_id": 11, "division_id": 5}
  ,{"id": 10 ,"name":"บวรเวทย์ ตันตรานนท์", "position_id": 10, "division_id": 2}
  ,{"id": 11 ,"name":"วธัญญู ตันตรานนท์", "position_id": 9, "division_id": 3}

  ,{"id": 12 ,"name":"ศิริรัตน์ จงก้าวหน้า", "position_id": 12, "division_id": 6}
  ,{"id": 13 ,"name":"บัญชา บ้านเรือน", "position_id": 13, "division_id": 7}
  ,{"id": 14 ,"name":"อดุลย์ เสียงสนั่น", "position_id": 14, "division_id": 8}
  ,{"id": 15 ,"name":"ประมวล วิรัตน์เกษม", "position_id": 15, "division_id": 9}
  ,{"id": 16 ,"name":"อภิสิทธ์ กอนแสง", "position_id": 16, "division_id": 10}
  ,{"id": 17 ,"name":"วธัญญู ตันตรานนท์", "position_id": 17, "division_id": 11}

  ,{"id": 18 ,"name":"วรรณา ถินยม", "position_id": 18, "division_id": 12}
  ,{"id": 19 ,"name":"วรรณา ถินยม", "position_id": 19, "division_id": 13}
  ,{"id": 20 ,"name":"เกศแก้ว สุภา", "position_id": 20, "division_id": 14}
  ,{"id": 21 ,"name":"เกศแก้ว สุภา", "position_id": 21, "division_id": 15}
  ,{"id": 22 ,"name":"วธัญญู ตันตรานนท์", "position_id": 22, "division_id": 16}
  ,{"id": 23 ,"name":"มนชาต เชื้อดวงผุย", "position_id": 23, "division_id": 17}
  ,{"id": 24 ,"name":"วธัญญู ตันตรานนท์", "position_id": 8, "division_id": 20}
  ];

var myItems = [];
var items2 = [];

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
  items2= [];
    var results = $.grep(division, function(e){ return e.parent === division_id; });
    for (var i = 0; i < results.length; i++) {
      // division_array.push(results[i]);
      var parent_id = null;
      if(division_id !== 0){
        parent_id = results[i].parent;  
      }
      division_array.push(new primitives.orgdiagram.ItemConfig({
        id: results[i].id,
        parent: parent_id,
        title: results[i].type + results[i].name,
        description: "คำอธิบายเพิ่มเติม"
      }));
      //new test
      items2[results[i].id] = new primitives.orgdiagram.ItemConfig({
        id: results[i].id,
        parent: parent_id,
        title: results[i].type + results[i].name,
        description: "คำอธิบายเพิ่มเติม"
      });
      //new test
    }
    for (var i = 0; i < results.length; i++) {
      loopDivision(results[i].id);
    }
}

function loopPosition(position_id){
  items2 = [];
    var results = $.grep(position, function(e){ return e.parent === position_id; });
    for (var i = 0; i < results.length; i++) {
      var parent_id = null;
      if(position_id !== 0){
        parent_id = results[i].parent;  
      }
      position_array.push(new primitives.orgdiagram.ItemConfig({
        id: results[i].id,
        parent: parent_id,
        title: results[i].name,
        itemTitleColor: results[i].itemTitleColor,
        description: "คำอธิบายเพิ่มเติม"
      }));
      items2[results[i].id] = new primitives.orgdiagram.ItemConfig({
        id: results[i].id,
        parent: parent_id,
        title: results[i].name,
        itemTitleColor: results[i].itemTitleColor,
        description: "คำอธิบายเพิ่มเติม"
      })
    }
    for (var i = 0; i < results.length; i++) {
      loopPosition(results[i].id);
    }
}
//end linked list array init
function loopPosition2(position_id){
  items2 = [];
    // find nodes with parent == position_id
    var results = $.grep(position, function(e){ return e.parent === position_id; });
    // for nodes
    for (var i = 0; i < results.length; i++) {
      // users with current nodes with parent=position_id
      var users = $.grep(real_data, function(e){ return e.position_id === results[i].id; });
      // 
      for (var j = 0; j < users.length; j++) {
        var parent_id = null;
        if(position_id !== 0){
          var all_will_be_parent = $.grep(real_data, function(e){ return e.position_id === results[i].parent; });
          parent_id = all_will_be_parent[0].id;
        }
        // var all_will_be_parent = $.grep(real_data, function(e){ return e.position_id === results[i].id; });
        // var parent_id = position_id == 0 ? null: users[j].parent;
        myItems.push(new primitives.orgdiagram.ItemConfig({
            id: users[j].id,
            parent: parent_id,
            title: users[j].name,
            description: results[i].name,
            itemTitleColor: results[i].itemTitleColor,
            image: "assets/orgchart/images/photos/a.png"
        }));
        items2[users[j].id] = new primitives.orgdiagram.ItemConfig({
            id: users[j].id,
            parent: parent_id,
            title: users[j].name,
            description: results[i].name,
            itemTitleColor: results[i].itemTitleColor,
            image: "assets/orgchart/images/photos/a.png"
        });
      };
    }
    for (var i = 0; i < results.length; i++) {
      loopPosition2(results[i].id);
    }
}
// loopPosition(0);
// console.log(myItems);

function getMyItems () {
  loopPosition2(0); //nong note this will really slow when swap between view
  return myItems;
}

function getDivisionItems () {
  loopDivision(0); //nong note this will really slow when swap between view
  return division_array;
}

function getPositionItems () {
  loopPosition(0); //nong note this will really slow when swap between view
  return position_array;
}

function getItems () {
  return items2;
}