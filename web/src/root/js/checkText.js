function checkText(confPass, newPass) {
  if(confPass == newPass){
    //console.log("They are the same");
    document.getElementById("submit").disabled = false;
    document.getElementById("checkPass").innerHTML = "";
    return true;
  } else {
    document.getElementById("submit").disabled = true;
    document.getElementById("checkPass").innerHTML = "Passwords do not match";
    return false;
  }
}

function checkPass(pass, confPass, newPass){
  if(pass.length < 1 || pass == null || !checkText(confPass, newPass)){
    document.getElementById("submit").disabled = true;
  } else {
    document.getElementById("submit").disabled = false;
  }
}
