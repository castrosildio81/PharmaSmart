    
let cores = ["#a89f9f", "#978484", "#705959;", "#3a2a2a"];
let i = 0;

setInterval(() => {
  document.body.style.backgroundColor = cores[i];
  i = (i + 1) % cores.length;
}, 1500);