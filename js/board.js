  function dragStart(event) {
	event.dataTransfer.setData("Text", event.target.id);
    // console.log(2);
};
let arr = {};
async function dragEnter(event) {
	event.preventDefault();
	// let column_id;
	let card_id;
	if (event.target.className == "taskColumn") {
		event.target.style.border = "1px dotted rgb(255,0,0)";
		// console.log(event.target.getAttribute("column_id"));
		arr.column_id = event.target.getAttribute("column_id")
	} else if (event.target.className == "taskDiv") {
		event.target.style.backgroundColor = "grey";
		// card_id = event.target.id
		arr.card_id = event.target.id

		// if(column_id && card_id){
		// 	console.log(column_id);
		// }

	}



};

function dragLeave(event) {
	event.preventDefault();
	event.target.style.border = "";
	event.target.style.backgroundColor = "";
    // console.log(event.target);

};

function dragOver(event) {
	event.preventDefault();
	// console.log(event.target);
};

// function swapTasks(node1, node2) {
// 	node1.parentNode.replaceChild(node1, node2);
// 	node1.parentNode.insertBefore(node2, node1);
// 	console.log(node1);
// 	console.log(node2);
// }

function drop(event) {
	event.preventDefault();
	if (event.target.className == "taskColumn") {
		var data = event.dataTransfer.getData("Text");
		event.target.appendChild(document.getElementById(data));

		var curDateTime = new Date();
		var actionDateTime = curDateTime.getHours() + ":" + curDateTime.getMinutes() + ":" + curDateTime.getSeconds();
		var actionSpan = document.createElement('div');
		// actionSpan.innerHTML = 'Moved to: ' + event.target.id + ' ' + actionDateTime;
		document.getElementById(data).appendChild(actionSpan);
		// console.log(event.target);
		// console.log(arr);
		$.ajax({
			url : '/board/UpdateCardColumn/column_id/1',
			type : 'POST',
			data : arr,
			dataType:'json',
			success : function(data) {              
				console.log('Data: '+data);
			},
			error : function(request,error){
				console.log("Request: "+JSON.stringify(request));
			}
		});


		event.target.style.border = "";
	} else if (event.target.className == "taskDiv") {
		var data = event.dataTransfer.getData("Text");
		// swapTasks(document.getElementById(data), document.getElementById(event.target.id));
		var curDateTime = new Date();
		console.log(event.target);

		// console.log("Task: " + data + " " + curDateTime.getHours() + ":" + curDateTime.getMinutes() + ":" + curDateTime.getSeconds());
		event.target.style.border = "";
		event.target.style.backgroundColor = "";
	} else {
		console.log('Not a drop target');
	}
};

var taskDivs = document.querySelectorAll('.taskDiv');
[].forEach.call(taskDivs, function(divs) {
	divs.addEventListener('dragstart', dragStart, false);
});

var targetDivs = document.querySelectorAll('.taskColumn');
[].forEach.call(targetDivs, function(columns) {
	columns.addEventListener('dragenter', dragEnter, false);
	columns.addEventListener('dragleave', dragLeave, false);
	columns.addEventListener('dragover', dragOver, false);
	columns.addEventListener('drop', drop, false);
});