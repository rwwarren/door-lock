function checkText(confPass, newPass) {
  //
  console.log("Dis be test: " + confPass.value + " new one: " + newPass);
  if(confPass.value == newPass){
    console.log("They are the same");
    document.getElementById("submit").disabled = false;
    document.getElementById("checkPass").innerHTML = "";
  } else {
    console.log("They are not the same");
    document.getElementById("submit").disabled = true;
    document.getElementById("checkPass").innerHTML = "Passwords do not match";
  }
}
