<!DOCTYPE html>
<html lang="en">
    <head>
        <title>მახო</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
            input[type="number"] {
                width:45px;
            }

            table, tr, td, th {
                border:1px solid black;
            }
        </style>

    </head>
    <body>

        <div class="container">
            <a href="calculator.php">მანძილი/სიჩქარე/დრო</a> &nbsp;<a href="excel.php" target="_blank">EXCEL</a> 
            <div class="row">

                <div class="col-sm-5">

                    <table id="calculationTable" class="table"  >

                        <tbody>
                            <tr>
                                <td>
                                    <input id="roundCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                                </td>
                                <td>
                                    ბრუნის დრო
                                </td>


                                <td>
                                    <table>
                                        <tr id="roundTr">
                                            <td >

                                                <input id="roundInputHour" class="input" type="number" min="-1" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                            <td >

                                                <input id="roundInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                            <td>

                                                <input id="roundInputSecond" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                        </tr>
                                        <tr id="roundMinutesTr">
                                            <td colspan="2" style="padding-top:5px; padding-left:40px">
                                                <input id="roundInputMinutes" class="input" type="number" disabled="true" value="00" style="width:50px;" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                            <td style="padding-top:5px">

                                                <input id="roundInputSeconds" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td> 
                                        </tr>
                                    </table>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <input id="busCheckBox" type="checkbox" onclick="checkCheckBoxes(event)" >
                                </td>
                                <td>
                                    ავტ. რაოდ.
                                </td>
                                <td colspan="3">
                                    <input id="busInput" class="input" type="number" disabled="true" style="width:135px;" max="200" min="1" value="0" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                </td>


                            </tr>

                            <tr >
                                <td>
                                    <input id="intervalCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                                </td>

                                <td>
                                    ინტერვალი
                                </td>
                                <td>
                                    <table>
                                        <tr id="intervalTr">
                                            <td >

                                                <input id="intervalInputHour" class="input" type="number" min="-1" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                            <td >

                                                <input id="intervalInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                            <td>

                                                <input id="intervalInputSecond" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                                            </td>
                                        </tr>
                                    </table>
                                </td>


                            </tr>

                            <tr>
                                <td colspan="3" style="padding:0px">
                                    <table style="width:100%">
                                        <tr>

                                            <td style="width:20%">
                                                <label>+/-</label>
                                                <input id="plusMinusInput" type="number" min="0" value="2" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                            </td>
                                            <td style="widht:60%">
                                                <button type="button" class="btn  btn-primary" style="width:100%;" onclick="checkAndCalculate()"><b>გამოთვლა</b></button>
                                            </td>
                                            <td style="width:20%">

                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style=" padding:0px; padding-top:2px;"> 
                                    <table>
                                        <tr>
                                            <td >
                                                <button class="btn btn-success" type="button" id="backButton"  disabled="true" onclick="goBack()"><<<<</button>
                                            </td>
                                            <td style="width:800px">  <label id="notes"> შენიშვბები</label></td>
                                            <td >      <button  class="btn btn-success" type="button" id="forwardButton"  disabled="true" onclick="goForward()">>>>></button>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>




                <div id="resultTables" class="col-sm-7" style="height: 400px; overflow:auto"> 
                    <div >

                        <table style="width:100%">
                            <tr>
                                <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო წუთებში</b></h6></th><th><h6><b>ავტ.</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                            </tr>
                            <tbody id="zeroTableBody">
                            </tbody>
                        </table>
                        <hr>
                        <div>
                            <label>ყველა შედეგები</label>
                            <table style="width:100%">
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო წუთებში</b></h6></th><th><h6><b>ავტ.</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tbody id="allTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var h = [];
            var hNumber = 0;



            function incoming(event) {
                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                if (event.keyCode === 13) {
                    checkAndCalculate();
                }
            }

            function checkCheckBoxes(event) {
                var target = event.target;

                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                var trElements = event.target.parentElement.parentElement;
                var trInputs = trElements.querySelectorAll(".input");
                if (selectedParametres() == 3) {

                    target.checked = false;
                    notes.style.color = "red";
                    notes.innerHTML = "სამივე პარამეტრის არჩევა დაუშვებელია";

                    //  redNotes.innerHTML = "სამივე პარამეტრის არჩევა დაუშვებელია";
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = true;
                    }
                } else {
                    notes.innerHTML = "";
                    if (target.checked == false) {
                        for (i = 0; i < trInputs.length; i++) {
                            trInputs[i].disabled = true;
                        }
                    } else {
                        for (i = 0; i < trInputs.length; i++) {
                            trInputs[i].disabled = false;
                        }
                    }
                }



            }

            function selectedParametres() {
                return document.querySelectorAll('input[type=checkbox]:checked').length;
            }



            function checkAndCalculate() {

                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";


                if (inputsValid()) {
                    saveInputs();
                    checkHistory();
                    calculate();
                }

            }

            function checkHistory() {
                if (h.length > 0 && hNumber > 0) {
                    backButton.disabled = false;
                }
                if (h.length > 0 && hNumber < h.length) {
                    forwardButton.disabled = false;
                }
                if (hNumber == 1) {
                    backButton.disabled = true;
                }
                if (hNumber == h.length) {
                    forwardButton.disabled = true;
                }

            }

            function saveInputs() {
                var inputs = [];
                inputs.push(roundCheckBox.checked);
                inputs.push(busCheckBox.checked);
                inputs.push(intervalCheckBox.checked);

                inputs.push(roundInputHour.value);
                inputs.push(roundInputMinute.value);
                inputs.push(roundInputSecond.value);

                inputs.push(busInput.value);

                inputs.push(intervalInputHour.value);
                inputs.push(intervalInputMinute.value);
                inputs.push(intervalInputSecond.value);

                inputs.push(plusMinusInput.value);

                h.push(inputs);
                hNumber = h.length;
            }

            function getRoundSeconds() {
                return  (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);

            }

            function getIntervalSeconds() {
                return (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + intervalInputSecond.value * 1;
            }

            function calculate() {
                var seconds = getRoundSeconds();
                if (roundCheckBox.checked === true & busCheckBox.checked === true) {

                    calculateInterval(seconds);
                } else if (roundCheckBox.checked === true & intervalCheckBox.checked === true) {
                    calculateBus(seconds);
                } else if (busCheckBox.checked === true & intervalCheckBox.checked === true) {
                    calculateRound();
                }
                copyHoursToMinutes();
                calculateTable();

            }
            function calculateRound() {
                var seconds = getIntervalSeconds();
                ;
                var result = seconds * busInput.value;

                var date = new Date(0);
                date.setSeconds(result);
                var resultString = date.toISOString().substr(11, 8);
                var splittedResult = resultString.split(":");
                roundInputHour.value = splittedResult[0];
                roundInputMinute.value = splittedResult[1];
                roundInputSecond.value = splittedResult[2];
                notes.style.color = "green";
                notes.innerHTML = "შედეგი ჯერადია.";
            }

            function calculateBus(seconds) {
                var intervalSeconds = getIntervalSeconds();

                var result = seconds / intervalSeconds;
                var nashti = result % 1;
                busInput.value = parseInt(result);
                if (nashti == 0) {
                    notes.style.color = "green";
                    notes.innerHTML = "შედეგი ჯერადია.";
                } else {

                    notes.innerHTML = "<label style='color:red;'>შედეგი არ არის ჯერადი </label>";
                    // + "<br><label>ნაშთი= " + nashti + "</label><br>"
                    //+ "<label>ჯერადი შედეგის მისაღებად ან დააკელი ბრუნის დრო ან გაზარდე ინტერვალის დრო</label>";

                }
            }

            function calculateInterval(seconds) {
                var result = seconds / busInput.value;
                var date = new Date(0);
                date.setSeconds(result);
                var resultString = date.toISOString().substr(11, 8);
                var splittedResult = resultString.split(":");
                intervalInputHour.value = splittedResult[0];
                intervalInputMinute.value = splittedResult[1];
                intervalInputSecond.value = splittedResult[2];
                var nashti = result % 3600 % 60 % 1;
                if (nashti == 0) {
                    notes.style.color = "green";
                    notes.innerHTML = "შედეგი ჯერადია.";
                } else {
                    var recalculatedSeconds = (splittedResult[0] * 3600) + (splittedResult[1] * 60) + parseInt(splittedResult[2]);
                    var recalculatedTime = recalculatedSeconds * busInput.value;
                    var recalculatedDate = new Date(0);
                    recalculatedDate.setSeconds(recalculatedTime);
                    var recalculatedTime = recalculatedDate.toISOString().substr(11, 8);
                    notes.innerHTML = "<label style='color:red;'>შედეგი არ არის ჯერადი</label>";
                    //  + "<br><label>ნაშთი= " + nashti + "</label><br>"
                    // + "<label>მიღებული ინტერვალისგან გადათვლილი ბრუნის დრო უდრის " + recalculatedTime + "</label>";
                }
            }

            function calculateTable() {

                if (window.innerWidth < 550) {

                    resultTables.style.height = (window.innerHeight - calculationTable.offsetHeight - 50) + "px";
                } else {
                    resultTables.style.height = (window.innerHeight - 50) + "px";
                }

                var roundSeconds = (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);
                var plusMinus = plusMinusInput.value * 60;
                var startingTime = roundSeconds + plusMinus;
                var endingTime = roundSeconds - plusMinus;

                var zeroTableRows = "";
                var allTableRows = "";
                var a = 0;
                var busCounts = new Array();
                var busCount = busInput.value;
                if (!busCheckBox.checked && busCount > 1) {
                    busCount--;
                    busCounts.push(busCount);
                    busCount++;
                    busCounts.push(busCount);
                    busCount++;
                    busCounts.push(busCount);

                } else if (!busCheckBox.checked && busCount == 1)
                {
                    busCounts.push(busCount);
                    busCount++;
                    busCounts.push(busCount);
                } else {
                    busCounts.push(busCount);
                }


                for (y = 0; y < busCounts.length; y++) {
                    for (x = startingTime; x > endingTime - 1; x--) {
                        if (x == 0) {
                            break;
                        }

                        var result = x / busCounts[y];
                        var roundTime = new Date(0);
                        roundTime.setSeconds(x);
                        var roundTimeResultString = roundTime.toISOString().substr(11, 8);

                        var intervalTime = new Date(0);
                        intervalTime.setSeconds(result);
                        var intervalTimeResultString = intervalTime.toISOString().substr(11, 8);

                        var roundTimeSplittedResult = roundTimeResultString.split(":");
                        var roundTimeHour = roundTimeSplittedResult[0];
                        var roundTimeMinute = roundTimeSplittedResult[1];
                        var roundTimeMinutes = (roundTimeHour * 60) + parseInt(roundTimeMinute);
                        var roundTimeSeconds = roundTimeSplittedResult[2];
                        var minutesText = roundTimeMinutes + ":" + roundTimeSeconds;
                        var interalSplittedResult = intervalTimeResultString.split(":");
                        var intervalSeconds = interalSplittedResult[2];


                        var nashti = result % 3600 % 60 % 1;
                        if (nashti == 0) {
                            allTableRows = allTableRows + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + minutesText + "</td><td>" + busCounts[y] + "</td><td>" + intervalTimeResultString + "</td></tr>";
                            if (intervalSeconds == 00 || intervalSeconds == 30) {
                                zeroTableRows = zeroTableRows + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + minutesText + "</td><td>" + busCounts[y] + "</td><td>" + intervalTimeResultString + "</td></tr>";

                            }
                            a++;
                        }

                    }
                }
                zeroTableBody.innerHTML = zeroTableRows;
                allTableBody.innerHTML = allTableRows;
            }

            function inputsValid() {
                if (roundCheckBox.checked & intervalCheckBox.checked) {
                    var seconds = (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);
                    var intervalSeconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + parseInt(intervalInputSecond.value);

                    if (intervalSeconds > seconds) {
                        notes.style.color = "red";
                        notes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                        return false;
                        //    redNotes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                    }
                }

                if (selectedParametres() < 2) {
                    notes.style.color = "red";
                    notes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    // redNotes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    return false;
                }
                if (intervalCheckBox.checked & intervalInputHour.value == 0 & intervalInputMinute.value == 0 & intervalInputSecond.value == 0) {
                    notes.style.color = "red";
                    notes.innerHTML = "მითითებული ინტერვალი დაუშვებელია (0). ინტერვალი უნდა იყოს არანაკლებ 1 წამი";
                    //  redNotes.innerHTML = "მითითებული ინტერვალი დაუშვებელია (0). ინტერვალი უნდა იყოს არანაკლებ 1 წამი";
                    return false;
                }
                if (busCheckBox.checked & (busInput.value <= 0)) {
                    notes.style.color = "red";
                    notes.innerHTML = " ავტობუსების რაოდენობის ველში მითითებულია დაუშვებელი რიცხვი (" + busInput.value + ")";
                    // redNotes.innerHTML = " ავტობუსების რაოდენობის ველში მითითებულია დაუშვებელი რიცხვი (0)";
                    return false;
                }
                return true;
            }

            function adjastTimeInputs(event) {
                notes.innerHTML = "";
                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                var targetTR = event.target.parentNode.parentNode.id;
                var targetInputs;

                if (targetTR == "roundTr") {
                    targetInputs = "round";
                    adjastTimeInputs_1(targetInputs);
                }
                if (targetTR == "roundMinutesTr") {

                    adjastTimeInputs_2();
                }
                if (targetTR == "intervalTr") {
                    targetInputs = "interval";
                    adjastTimeInputs_1(targetInputs);
                }




            }
            function adjastTimeInputs_1(targetInputs) {

                var second = document.getElementById(targetInputs + "InputSecond");
                var minute = document.getElementById(targetInputs + "InputMinute");
                var hour = document.getElementById(targetInputs + "InputHour");

                if (second.value == 60) {
                    second.value = 0;
                    minute.value = parseInt(minute.value) + 1;
                }
                if (second.value == -1) {
                    minute.value = parseInt(minute.value) - 1;
                    if (hour.value == 0 && minute.value == -1) {
                        second.value = 0;
                    } else {
                        second.value = 59;
                    }
                }


                if (minute.value == 60) {
                    minute.value = 0;
                    timeInputHourPlusPlus(targetInputs);
                }
                if (minute.value == -1) {

                    minute.value = 59;
                    timeInputHourMinusMinus(targetInputs);
                }

                if (hour.value == -1) {
                    hour.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";

                }

                copyHoursToMinutes();
            }


            function adjastTimeInputs_2() {
                var second = document.getElementById("roundInputSeconds");
                var minute = document.getElementById("roundInputMinutes");
                console.log(second.value);

                if (second.value == 60) {
                    second.value = 0;
                    minute.value = parseInt(minute.value) + 1;
                }
                if (second.value == -1) {
                    minute.value = parseInt(minute.value) - 1;
                    if (minute.value == -1) {
                        second.value = 0;
                    } else {
                        second.value = 59;
                    }
                }


                if (minute.value == -1) {

                    minute.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "წუთების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                }


                copyMinutesToHours();
            }

            function copyMinutesToHours() {
                var minutes = roundInputMinutes.value;
                var hour = parseInt(minutes / 60);
                var minute = minutes % 60;
                if (hour < 10) {
                    roundInputHour.value = "0" + hour;
                } else {
                    roundInputHour.value = hour;
                }
                if (minute < 10) {
                    roundInputMinute.value = "0" + minute;
                } else {
                    roundInputMinute.value = minute;
                }

                roundInputSecond.value = roundInputSeconds.value;


            }
            function copyHoursToMinutes() {
                var hour = roundInputHour.value;
                var minute = roundInputMinute.value;
                var minutes = (hour * 60) + parseInt(minute);
                roundInputMinutes.value = minutes;
                roundInputSeconds.value = roundInputSecond.value;
            }

            function   timeInputHourPlusPlus(targetInputs) {
                var hour = document.getElementById(targetInputs + "InputHour");
                var x = parseInt(hour.value) + 1
                if (x < 10) {
                    hour.value = "0" + x;
                } else {
                    hour.value = x;
                }
            }

            function   timeInputHourMinusMinus(targetInputs) {
                var hour = document.getElementById(targetInputs + "InputHour");
                var minute = document.getElementById(targetInputs + "InputMinute");

                var x = parseInt(hour.value) - 1
                if (x < 0) {
                    x = 0;
                    hour.value = 0;
                    minute.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                }
                if (x < 10) {
                    hour.value = "0" + x;
                } else {
                    hour.value = x;
                }
            }

            function goBack() {
                hNumber--;
                var inputs = h[hNumber - 1];
                roundCheckBox.checked = inputs[0];
                busCheckBox.checked = inputs[1];
                intervalCheckBox.checked = inputs[2];

                checkCheckBoxHistory(roundCheckBox);
                checkCheckBoxHistory(busCheckBox);
                checkCheckBoxHistory(intervalCheckBox);

                roundInputHour.value = inputs[3];
                roundInputMinute.value = inputs[4];
                roundInputSecond.value = inputs[5];

                busInput.value = inputs[6];

                intervalInputHour.value = inputs[7];
                intervalInputMinute.value = inputs[8];
                intervalInputSecond.value = inputs[9];

                plusMinusInput.value = inputs[10];

                checkHistory();

                calculate();
            }

            function goForward() {
                hNumber++;
                var inputs = h[hNumber - 1];
                roundCheckBox.checked = inputs[0];
                busCheckBox.checked = inputs[1];
                intervalCheckBox.checked = inputs[2];

                checkCheckBoxHistory(roundCheckBox);
                checkCheckBoxHistory(busCheckBox);
                checkCheckBoxHistory(intervalCheckBox);


                roundInputHour.value = inputs[3];
                roundInputMinute.value = inputs[4];
                roundInputSecond.value = inputs[5];

                busInput.value = inputs[6];

                intervalInputHour.value = inputs[7];
                intervalInputMinute.value = inputs[8];
                intervalInputSecond.value = inputs[9];


                plusMinusInput.value = inputs[10];

                checkHistory();

                calculate();
            }

            function   checkCheckBoxHistory(checkBox) {
                var trElements = checkBox.parentElement.parentElement;
                var trInputs = trElements.querySelectorAll(".input");
                if (checkBox.checked == true) {
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = false;
                    }
                } else {
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = true;
                    }
                }
            }
        </script>
    </body>
</html>
