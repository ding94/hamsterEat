// var theToggle = document.getElementById('toggle-menu');
var theToggleTest = document.getElementById('toggle-menu-test');


// hasClass
// function hasClass(elem, className) {
// 	return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
// }
// // addClass
// function addClass(elem, className) {
//     if (!hasClass(elem, className)) {
//     	elem.className += ' ' + className;
//     }
// }
// // removeClass
// function removeClass(elem, className) {
// 	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
// 	if (hasClass(elem, className)) {
//         while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
//             newClass = newClass.replace(' ' + className + ' ', ' ');
//         }
//         elem.className = newClass.replace(/^\s+|\s+$/g, '');
//     }
// }
// // toggleClass
// function toggleClass(elem, className) {
// 	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, " " ) + ' ';
//     if (hasClass(elem, className)) {
//         while (newClass.indexOf(" " + className + " ") >= 0 ) {
//             newClass = newClass.replace( " " + className + " " , " " );
//         }
//         elem.className = newClass.replace(/^\s+|\s+$/g, '');
//     } else {
//         elem.className += ' ' + className;
//     }
// }

// theToggle.onclick = function() {
//    toggleClass(this, 'on');
//    return false;
// }

/* Show Mobile Filter JS */
$(document).ready(function(){

  $('#filter-btn').click(function(event){
    event.stopPropagation();
    $(".filter").slideToggle("slow");
  });

	 $('.toggle').click(function(event){
        event.stopPropagation();
         $(".filter").slideToggle("slow");
          $('#menu').toggleClass("on");
    });
    $(".toggle").on("click", function (event) {
        event.stopPropagation();
    });
});

if (parseInt(window.innerWidth)<768) {
  $(".filter").on("click", function (event) {
        event.stopPropagation();
    });
  $("#filter-box").addClass('in');
    $(document).on("click", function () {
        $(".filter").slideUp(500);
    });
    // $('#bottom-navbar').on("click", function () {
    //     $(".filter").hide();
    // });
}

$('#toggle-menu-test').on('click', function(e) {
    $('#menu').toggleClass("on"); //you can list several class names 
    e.preventDefault();
});

/* Filter JS Functions */
var itemsToFilter = document.querySelectorAll(".menu-container .list");

var checkBoxes = document.querySelectorAll(".filter-list li input");

var checkedBox = [];

var itemsToShow = [];

var noItemContainer = document.getElementsByClassName("item-na-container");

for (var i = 0; i < checkBoxes.length; i++) {
  checkBoxes[i].addEventListener("click", filterItems, false);
  checkBoxes[i].addEventListener("click", showNoItemsDisplay, false);
}

function uncheckAll(){
    for (var i = 0; i < checkBoxes.length; i++){
        checkBoxes[i].checked = false;
    }
    checkedBox = [];
    showNoItemsDisplay();
    showAll();
}

function filterItems(e) {
  var clickedItem = e.target;
  if (clickedItem.checked == true) {
    checkedBox.push(clickedItem.value);
    addItems();
  } else if (clickedItem.checked == false) {
    var posOfElement = checkedBox.indexOf(clickedItem.value);
    checkedBox.splice(posOfElement, 1);
    removeItems();
  } else {
    // deal with the indeterminate state if needed
  }
}

function addItems(){
  for(var i = 0; i < checkedBox.length; i++){
    for(var k = 0; k < itemsToFilter.length; k++){
      var dataTypeArray = itemsToFilter[k].getAttribute('data-type').split(',');
      for(var o = 0; o < dataTypeArray.length; o++){
        if(checkedBox[i]==dataTypeArray[o]){
          if(itemsToShow.indexOf(itemsToFilter[k])==-1){
            itemsToShow.push(itemsToFilter[k]); 
          }
        }
      }
    }
  }
  showOrHideClass();
}

function removeItems(){
  itemsToShow = [];
  for(var i = 0; i < checkedBox.length; i++){
    for(var k = 0; k < itemsToFilter.length; k++){
      var dataTypeArray = itemsToFilter[k].getAttribute('data-type').split(',');
      for(var o = 0; o < dataTypeArray.length; o++){
        if(checkedBox[i]==dataTypeArray[o]){
          if(itemsToShow.indexOf(itemsToFilter[k])==-1){
            itemsToShow.push(itemsToFilter[k]); 
          }
        }
      }
    }
  }
  showOrHideClass();
}

function showOrHideClass(){
  for(var k = 0; k < itemsToFilter.length; k++){
    // fadeOut(itemsToFilter[k].parentElement);
    itemsToFilter[k].parentElement.classList.remove('showItem');
    itemsToFilter[k].parentElement.classList.add('hideItem');
    // setTimeout(function() {itemsToFilter[k].parentElement.classList.add('hideItem');}, 1000);
  }
  for(var i = 0; i < itemsToShow.length; i++){
    // fadeIn(itemsToShow[i].parentElement);
    itemsToShow[i].parentElement.classList.remove('hideItem');
    itemsToShow[i].parentElement.classList.add('showItem');
    // itemsToShow[i].parentElement.style.opacity = 1;
    // itemsToShow[i].parentElement.style.display = 'block';
  }
  showAll();
  showNoItemsDisplay();
}

function showNoItemsDisplay(){
    if((itemsToShow > -1)&&(!(checkedBox > -1))){
        noItemContainer[0].classList.remove('hideItem');
        noItemContainer[0].classList.add('showItemFlex');
    } else {
        noItemContainer[0].classList.remove('showItemFlex');
        noItemContainer[0].classList.add('hideItem');
    }
}

function showAll(){
  if(checkedBox > -1){
    for(var o = 0; o < itemsToFilter.length; o++){
    // fadeIn(itemsToFilter[o].parentElement);
      itemsToFilter[o].parentElement.classList.remove('hideItem');
      itemsToFilter[o].parentElement.classList.add('showItem');
    //   itemsToShow[i].parentElement.style.opacity = 1;
    // itemsToShow[i].parentElement.style.display = 'block';
    }
  }
}

// function fadeOut(el){
//   el.style.opacity = 1;

//   (function fade() {
//     if ((el.style.opacity -= .1) < 0) {
//       el.style.display = "none";
//     } else {
//       requestAnimationFrame(fade);
//     }
//   })();
// }

// // fade in

// function fadeIn(el){
//   el.style.opacity = 0;
//   el.style.display = "block";
//   var val = parseFloat(el.style.opacity);

//   (function fade() {
//     if (!((val += .1) > 1.1)) {
//       el.style.opacity = val;
//       console.log(val);
//       requestAnimationFrame(fade);
//     } else{
//         el.style.opacity = 1;
//         el.style.display = 'block';
//         console.log(el.style.opacity);
//     }
//   })();
// }