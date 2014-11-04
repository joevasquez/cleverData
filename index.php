<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>JSON Transform</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min.js"></script>
    <script type="text/javascript" src="https://raw.github.com/douglascrockford/JSON-js/master/json2.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">    
 
    <script type="text/template" id="tpl-html">
	
	<style>
	table { width:100%; table-layout:fixed; }
	td { word-wrap:break-word; }
	td:nth-child(2), td:nth-child(3), td:nth-child(4) { text-align:center }
	th:nth-child(2), th:nth-child(3), th:nth-child(4) { text-align:center }
	</style>

        <div class="well">
            <table class="table" id="clevertable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Students</th>
                </tr>
                </thead>
                <tbody>
                <% _.each( target, function(i) {%>
                    <tr>
                        <td><%= i.course_name %></td>
                        <td><%= i.subject %></td>
                        <td><%= i.students %></td>
                    </tr>
                <% }); %>
                </tbody>
            </table>
        </div>
    </script>
 
    <script>
 		var xhReq = new XMLHttpRequest();
		xhReq.open("GET", "assets/json/temp.json", false);
		xhReq.send(null);
		var rawData = JSON.parse(xhReq.responseText);
		var length;		
		var result = [];
		var subjectList = [];
		var classList = [];
 
        $(document).ready(function() {
            var data = { target:rawData };
            var template = _.template( $("#tpl-html").text() );
            $("#output").html( template(data) );
        });

		for(var i in rawData){
			if (rawData[i].students instanceof Array) {
				length = rawData[i].students.length;
			} else { length = 0; }
    		result.push([i, rawData[i].subject, rawData[i].course_name, length]);
			subjectList.push([rawData[i].subject]);
			classList.push([rawData[i].course_name]);
		}
		console.log(result);	
    </script>
   
</head>
<body style="padding:50px 10px ">
    <div class="container">
      
      <h1>Subject Data</h1>
      <table id="tb" class="table">
      <thead>
        <tr>
            <th>Subject</th>
            <th>Students/Course</th>
            <th>Total Students</th>
            <th>Total Courses</th>
        </tr>      
      </thead>
      </table>
      
      <h1>Class Data</h1>
      <table id="tb-2" class="table">
      <thead>
        <tr>
            <th>Class</th>
            <th>Students/Course</th>
            <th>Total Students</th>
            <th>Total Courses</th>
        </tr>      
      </thead>
      </table>
      <div id="output"></div>
    </div>
 
</body>

    <script>
	var newSubjectList;
	var newClassList;
	newSubjectList = _.uniq( _.collect( subjectList, function( x ){
		return JSON.stringify( x );
	}));
	console.log( "New List: "+newSubjectList );
	
	newClassList = _.uniq( _.collect( classList, function( x ){
		return JSON.stringify( x );
	}));
	console.log( "New List: "+newClassList );
	
	var subjectData = [];
	for(var i = 0; i < newSubjectList.length; i++){
		var res = newSubjectList[i].replace("[", "");
		var res = res.replace("]", "");
		var res = res.replace('"', "");
		var res = res.replace('"', "");
		newSubjectList[i] = res;
		
		var total = 0;
		var classes = 0;
		var average = 0;

		for(var j in result) { 
			if (result[j][1] == newSubjectList[i]){
				total += result[j][3]; 
				console.log("Total: "+total);
				classes++;				
			}
		}
		average = Math.round((total / classes) * 100) / 100;
		subjectData[i] = {
			name: newSubjectList[i],
			students: total,
			classes: classes,
			average: average
		};
	}
	
	var classData = [];
	for(var i = 0; i < newClassList.length; i++){
		var res = newClassList[i].replace("[", "");
		var res = res.replace("]", "");
		var res = res.replace('"', "");
		var res = res.replace('"', "");
		newClassList[i] = res;
		
		var total = 0;
		var classes = 0;
		var average = 0;

		for(var j in result) { 
			if (result[j][2] == newClassList[i]){
				total += result[j][3]; 
				console.log("Total: "+total);
				classes++;				
			}
		}
		average = Math.round((total / classes) * 100) / 100;
		classData[i] = {
			name: newClassList[i],
			students: total,
			classes: classes,
			average: average
		};
	}

	var theTable = "";
	for(var j=0;j<newSubjectList.length;j++){
		theTable += '<tr>';
		theTable += '<td>'+newSubjectList[j]+'</td>';
		theTable += '<td class="average">'+subjectData[j].average+'</td>';
		theTable += '<td>'+subjectData[j].students+'</td>';
		theTable += '<td>'+subjectData[j].classes+'</td>';
		theTable += '</tr>';
	}
	$('#tb').append(theTable);	


	var theTable = "";
	for(var j=0;j<newClassList.length;j++){
		theTable += '<tr>';
		theTable += '<td>'+newClassList[j]+'</td>';
		theTable += '<td class="average">'+classData[j].average+'</td>';
		theTable += '<td>'+classData[j].students+'</td>';
		theTable += '<td>'+classData[j].classes+'</td>';
		theTable += '</tr>';
	}
	$('#tb-2').append(theTable);
	
var cell = $('.average');

cell.each(function() {
    var cell_value = $(this).html();
    if ((cell_value >= 25) && (cell_value <=40)) {
        $(this).css({'background' : '#ffff00'});   
    } else if ((cell_value > 40)) {
        $(this).css({'background' : '#FF0000'});
    }
});	
	
		
	</script>

</html>