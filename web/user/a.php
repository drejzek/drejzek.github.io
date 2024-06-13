<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periodické načítání dat</title>
</head>
<body>
    <div id="data"></div>

    <script>
        setInterval(function() {
            loadData();
        }, 1000); // Načítání dat každou sekundu

        function loadData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById("data").innerHTML = this.responseText;
                }
            };
            xhr.open("GET", "aa.php", true);
            xhr.send();
        }
    </script>
</body>
</html>