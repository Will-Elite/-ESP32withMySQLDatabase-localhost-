<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP32 WITH MYSQL DATABASE</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <style>
        html {font-family: Arial; display: inline-block; text-align: center;}
        p {font-size: 1.2rem;}
        h4 {font-size: 0.8rem;}
        body {margin: 0;}
        .topnav {overflow: hidden; background-color: #0C6980; color: white; font-size: 1.2rem;}
        .content {padding: 5px};
        .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140, 140, 140, 5); border: 1px solid #0c6980; border-radius: 15px;}
        
    </style>
</head>
<body>
    <div class="topnav">
        <h3>ESP32 WITH MYSQL DATABASE</h3>
    </div>

    <br>

    <!-- DISPLAY MONITORING AND CONTROLLING -->
    <div class="content">
        <div class="cards">
            <!-- MONITORING.........................-->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">MONITORING</h3>
                </div>

                <!-- Displays the hunidity and temperature values received from ESP32 -->
                 <h4 class="temperatureColor">
                    <i class="fas fa-thermoneter-half"></i>
                    TEMPERATURE
                </h4>
                <p class="temperatureColor">
                    <span class="reading">
                        <span id="ESP32_01_Temp"> &deg;C</span>
                    </span>
                </p>
                <h4 class="humidityColor">
                    <i class="fas fa-tint"></i>
                     HUMIDITY
                </h4>
                <p class="humidityColor">
                    <span class="reading">
                        <span id="ESP32_01_humd"></span>
                         &percnt;
                    </span>
                </p>
                <!-- ================================ -->
                 <p class="statusreadColor">
                    <span>Status Read Sensor DHT11 :</span>
                    <span id="ESP32_01_Status_Read_DHT11"></span>
                 </p>
            </div>
            <!-- ==================================== -->

            <!-- CONTROLLING -->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">CONTROLLING</h3>
                </div>

                <!-- Buttons for controlling the LEDs on Slave 2. -->
                <h4 class="LEDColor">
                    <i class="fas fa-lightbulb"></i>
                    LED 1
                </h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogLED_01" onclick="GetTogBtnLEDState('ESP32_01_TogLED_01')">
                    <div class="sliderTS"></div>
                </label>
                <h4 class="LEDColor">
                    <i class="fas fa-lightbulb"></i>
                     LED 2
                </h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogLED_02" onclick="GetTogBtnLEDState('ESP32_01_TogeLED_02')">
                    <div class="sliderTS"></div>
                </label>
                <!-- ............................................ -->
            </div>
            <!-- ................................................ -->
        </div>
    </div>

    <br>

    <div class="content">
        <div class="cards">
            <div class="card header" style="border-radius: 15px;">
                <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP32 [<span id="ESP32_01_LTRD"></span>]</h3>
                <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
                <h3 style="font-size: 0.7rem;"></h3>
            </div>
        </div>
    </div>
    <!-- .......................................................... -->

    <script>
        //..................................................
        document.getElementById("ESP32_01_Temp").innerHTML = "NN";
        document.getElementById("ESP32_01_Humd").innerHTML = "NN";
        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
        document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
        //..................................................

        Get_Data("esp32_01");

        setInterval(myTimer, 5000);

        //..................................................
        function myTimer() {
            Get_Data("esp32_01");
        }
        //..................................................

        //..................................................
        function Get_Data(id) {
            Get_Data("esp32_01");
        }
        //.................................................
        function Get_Data(id) {
            if(window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    const myObj = JSON.parse(this.responseText);
                    if(myObj.id == "esp32_01") {
                        document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
                        document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
                        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = myObj.status_read_sensor_dht11;
                        document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
                        if (myObj.LED_01 == "ON") {
                            document.getElementById("ESP32_01_TogLED_01").checked = true;
                        } else if (myObj.LED_01 == "OFF") {
                            document.getElementById("ESP32_01_TogLED_01").checked = false;
                        }
                        if (myObj.LED_02 == "ON") {
                            document.getElementById("ESP32_01_TogLED_02").checked = true;
                        } else if (myObj.LED_02 == "OFF") {
                            document.getElementById("ESP32_01_TogLED_02").checked = false;
                        }
                    }
                }
            };
            xmlhttp.open("POST","getdata.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id="+id);
        }
        //..................................................

        //..................................................
        function GetTogBtnLEDState(togbtnid) {
            if(togbtnid == "ESP32_01_TogLED_01") {
                var togbtnchecked = document.getElementById(togbtnid).checked;
                var togbtncheckedsend = "";
                if (togbtnchecked == true) togbtncheckedsend = "ON";
                if (togbtnchecked == false) togbtncheckedsend = "OFF";
                Update_LEDs("esp32_01","LED_01", togbtncheckedsend);
            }
            if (togbtid == "ESP32_01_TogLED_02") {
                var togbtnchecked = document.getElementById(togbtnid).checked;
                var togbtnchecksend = "";
                if (togbtnchecked == true) togbtncheckedsend = "ON";
                if (togbtnchecked =- false) togbtncheckedsend = "OFF";
                Update_LEDs("esp32_01","LED_02",togbtncheckedsend);
            }
        }
        //.................................................

        //.................................................
        function Update_LEDs(id, lednum, ledstate) {
            if (window.XHLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new XMLHttpRequest();
            } 
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // document.getElementById("demo").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("POST","updateLEDs.php",true);
            xmlhttp.setRequesHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send("id="+id+"&lednum="+lednum+"&ledstatus="+ledstate);
        }
        //.................................................
    </script>
</body>
</html>