// ROLE BUTTONS

const roleButtons = document.querySelectorAll(".role-btn");
const gradeSection = document.querySelector(".grade-section");

roleButtons.forEach(btn=>{
btn.addEventListener("click",()=>{

roleButtons.forEach(b=>b.classList.remove("active"));
btn.classList.add("active");

if(btn.dataset.role==="student"){
gradeSection.style.display="block";
}else{
gradeSection.style.display="none";
}

});
});


// GRADE BUTTONS

const gradeButtons = document.querySelectorAll(".grade-btn");

gradeButtons.forEach(btn=>{
btn.addEventListener("click",()=>{

gradeButtons.forEach(b=>b.classList.remove("active"));
btn.classList.add("active");

});
});
