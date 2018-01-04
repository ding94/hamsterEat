function moveToBox2(){
  var box1 = document.getElementById('box1');
  var box2 = document.getElementById('box2');
  var box3 = document.getElementById('box3');
  box1.style = 'transform:translatex(-100%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
  box2.style = 'transform:translatex(0);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
  box3.style = 'transform:translatex(100%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
}

function moveToBox3(){
  var box1 = document.getElementById('box1');
  var box2 = document.getElementById('box2');
  var box3 = document.getElementById('box3');
  box1.style = 'transform:translatex(-200%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
  box2.style = 'transform:translatex(-100%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
  box3.style = 'transform:translatex(0);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
}

function moveToBox1(){
	var box1 = document.getElementById('box1');
	var box2 = document.getElementById('box2');
	var box3 = document.getElementById('box3');
	box1.style = 'transform:translatex(0);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
	box2.style = 'transform:translatex(100%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';
	box3.style = 'transform:translatex(200%);transition:transform 2s cubic-bezier(0.19, 1, 0.22, 1)';	
}