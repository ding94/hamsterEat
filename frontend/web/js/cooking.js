$(document).ready(function() {
	
  $(".nav.nav-tabs li:first-child").addClass('active');
  $(".tab-content div:first-child").addClass('active');
});

/* js code for zebra striping table. */
var tds = document.querySelectorAll("td");
var groups = [];

for(var i = 0; i < tds.length; i++){
	if(tds[i].getAttribute('rowspan') != null){
  	var rspan = tds[i];
  	groups.push({
    	parent: rspan.parentNode,
      height: rspan.getAttribute('rowspan')
    });
  }
}

var count = 0;
var rows = document.querySelectorAll('tr');
var dark = true;

for(var i = 0; i < rows.length; i++){
	var row = rows[i];
  var index = groupIndex(row);
  if(index != null && dark){
  	var group = groups[index];
    var height = parseInt(group.height);
    for(var j = i; j < i + height; j++){
    	rows[j].classList.add('dark');
    }
    i += height - 1;
    dark = !dark;
    continue;
  }
  if(dark){
  	rows[i].classList.add('dark');
  }
  dark = !dark;
}

function groupIndex(element){
	for(var i = 0; i < groups.length; i++){
  	var group = groups[i].parent;
    if(group == element){
    	return i;
    }
  }
  return null;
}

$(".switch").on('click', function(event) {
  event.preventDefault();
  /* Act on the event */
 
  $(".thead").each(function(index){
    if($(this).hasClass('none'))
    {
      $(this).removeClass('none');
    }
    else
    {
      $(this).addClass('none');
    }
  });

  $(".selection-thead").each(function(index){
   
    if($(this).hasClass('none'))
    {
      $(this).removeClass('none');
    }
    else
    {
      $(this).addClass('none');
    }
  });

  
  if($(this).hasClass('name'))
  {
    $(this).text('View Name');
    $(this).removeClass('name');
  }
  else
  {
    $(this).text('View NickNames');
    $(this).addClass('name');
  }
});