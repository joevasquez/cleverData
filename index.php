<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Clever Data Analysis</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>    
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min.js"></script>
    <script type="text/javascript" src="https://raw.github.com/douglascrockford/JSON-js/master/json2.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">    
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>	
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script>
function(){
  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  });
};

function(){
  $('#myTab2 a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  });
};
</script>

    <script type="text/template" id="tpl-html">
	
	<style>
	table { width:100%; table-layout:fixed; }
	td { word-wrap:break-word; }
	td:nth-child(2), td:nth-child(3), td:nth-child(4) { text-align:center }
	th:nth-child(2), th:nth-child(3), th:nth-child(4) { text-align:center }
	
	.axis path, .axis line { fill: none; stroke: #000; shape-rendering: crispEdges;}
	.bar { fill: orange; }
	.bar:hover { fill: orangered ;}
	.x.axis path { display: none; }
	.tick {text-align:right; padding-right:20px}
	
	.d3-tip {
	  line-height: 1;
	  font-weight: bold;
	  padding: 12px;
	  background: rgba(0, 0, 0, 0.8);
	  color: #fff;
	  border-radius: 2px;
	}
	
	/* Creates a small triangle extender for the tooltip */
	.d3-tip:after {
	  box-sizing: border-box;
	  display: inline;
	  font-size: 10px;
	  width: 100%;
	  line-height: 1;
	  color: rgba(0, 0, 0, 0.8);
	  content: "\25BC";
	  position: absolute;
	  text-align: center;
	}
	
	/* Style northward tooltips differently */
	.d3-tip.n:after { margin: -1px 0 0 0; top: 100%; left: 0; }
	
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
    
      <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#subject" data-toggle="tab"><h3>Subject Data</h3></a></li>
        <li><a href="#class" data-toggle="tab"><h3>Class Data</h3></a></li>
        <li><a href="#students" data-toggle="tab"><h3>Student Data</h3></a></li>
      </ul>
          
      <div class="tab-content">
        <div class="tab-pane active" id="subject">
        	<h1>Subject Data</h1>
      		<h3>The below chart was built with the d3.js library</h3><br/><br/>
      		<div class="chart"></div><br/>    
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
        </div>
        <div class="tab-pane " id="class">
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
        </div>
        <div class="tab-pane " id="students">
      		<div id="output"></div>
        </div>
      </div>    
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
    
    
	<script src="http://d3js.org/d3.v3.min.js"></script>

<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
<script>

var margin = {top: 40, right: 20, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var formatPercent = d3.format(".0%");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")

var tip = d3.tip()
  .attr('class', 'd3-tip')
  .offset([-10, 0])
  .html(function(d) {
    return "<strong>" + d.name + " Average:</strong> <span style='color:red'>" + d.average + "</span>";
  })

var svg = d3.select(".chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom + 30)
  .append("g")
    .attr("transform", "translate(60,10)");

svg.call(tip);

	// The new data variable.
	var data = [];
	for(var j=0;j<subjectData.length-1;j++){
        val = subjectData[j].name;
        newVal = '';
        val = val.split(' ');
        for(var c=0; c < val.length; c++) {
                newVal += val[c].substring(0,1).toUpperCase() +
val[c].substring(1,val[c].length) + ' ';
        }
        subjectData[j].name = newVal;		
		
		data[j] = {
			name: subjectData[j].name.substring(0, 45),
			average: subjectData[j].average
		};
	};
	
	console.log("Data: "+data);
	


// The following code was contained in the callback function.
x.domain(data.map(function(d) { return d.name; }));
y.domain([0, d3.max(data, function(d) { return d.average; })]);

svg.append("g")
    .attr("class", "x-axis")
    .attr("transform", "translate(10," + height + ")")
    .call(xAxis);

svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
  .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("average");

svg.selectAll(".bar")
    .data(data)
  .enter().append("rect")
    .attr("class", "bar")
    .attr("x", function(d) { return x(d.name); })
    .attr("width", x.rangeBand())
    .attr("y", function(d) { return y(d.average); })
    .attr("height", function(d) { return height - y(d.average); })
    .on('mouseover', tip.show)
    .on('mouseout', tip.hide)

function type(d) {
  d.average = +d.average;
  return d;
}

svg.selectAll(".x-axis text"). // select all the text elements for the xaxis
attr("transform", function(d) {
    return "translate(" + this.getBBox().height * -2 + "," + this.getBBox().height + ")rotate(-45)";
});

</script>


</html>