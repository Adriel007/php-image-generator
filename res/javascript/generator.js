function send() {
    fetch("../php/scripts.php?img=1")
        .then(res => res.text())
        .then(data => {
            document.getElementById("img-generator").src = data;
            console.log("OK");
        })
        .catch(err => {
            console.error(err);
        });
}