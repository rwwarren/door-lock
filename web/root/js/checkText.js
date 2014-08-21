function checkText(confPass, newPass) {
  //console.log("Dis be test: " + confPass.value + " new one: " + newPass);
  if(confPass == newPass){
    //console.log("They are the same");
    document.getElementById("submit").disabled = false;
    document.getElementById("checkPass").innerHTML = "";
    return true;
  } else {
    //console.log("They are not the same");
    document.getElementById("submit").disabled = true;
    document.getElementById("checkPass").innerHTML = "Passwords do not match";
    return false;
  }
}

function checkPass(pass, confPass, newPass){
  //Boolean passwords = checkText(confPass, newPass);
  if(pass.length < 1 || pass == null || !checkText(confPass, newPass)){
//  if(pass == null || !passwords){
    document.getElementById("submit").disabled = true;
  } else {
    document.getElementById("submit").disabled = false;
  }
}
