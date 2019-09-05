window.onload = function () {
    var sliders = document.getElementsByClassName("slider");
    for (var i = 0; i < sliders.length; i++) {
        sliders[i].onchange = function () {
            setBrightness(this.id, this.value);
        }
    }
    monitorBrightness();
    setInterval(
        function () {
            monitorBrightness();
        }, 15000);
}

function setBrightness(device, value) {
    var url = "http://192.168.1.72/wemo/index.php/api/brightness/update";
    var request = new XMLHttpRequest();
    var type = "POST";
    var json = {};
    json.device = device;
    json.brightness = value;
    request.open(type, url);
    request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    request.send(JSON.stringify(json));
}

function monitorBrightness() {
    var url = "http://192.168.1.72/wemo/index.php/api/status/all";
    var request = new XMLHttpRequest();
    var type = "GET";
    request.open(type, url);
    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            var json = JSON.parse(this.response);
            for (var i = 0; i < json.length; i++) {
                var status = json[i];
                var slider = document.getElementById(status.device);
                slider.value = status.dim; 
            }
        }
    }
    request.send();
}
