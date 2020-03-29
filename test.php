<!DOCTYPE html>
<html>
<body>

<button onclick="changeText(this)">请点击此文本！</button>

<script>
    function changeText(id) {
        alert("Hello! I am an alert box!");
        id.innerHTML = "谢谢！";
    }
</script>

</body>
</html>
