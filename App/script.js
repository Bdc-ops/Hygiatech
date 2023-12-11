function loader() {
  document.getElementById("loader").style.display = "block";
  document.body.classList.add("no-scroll");
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      document.getElementById("loader").style.display = "none";
      document.body.classList.remove("no-scroll");
      if (xhr.status == 200) {
          document.getElementById("dataContainer").innerHTML = xhr.responseText;
        } else {
            console.error(
                "Erreur de chargement des données:",
                xhr.status,
                xhr.statusText
                );
            }
        }
    };
    xhr.open("GET", "index.php", true);
}

document.getElementById("loaderBTN").addEventListener("click", loader);

document.getElementById("downloadData").addEventListener("click", () => {
    document.getElementById("popup-container").style.display = "block"; 
}); 

document.getElementById("closePopup").addEventListener("click", () => {
    document.getElementById("popup-container").style.display = "none"; 
}); 
