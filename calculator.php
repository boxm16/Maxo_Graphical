<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
            input[type="number"] {
                width:60px;
            }
            table {
                border-collapse: collapse;
            }   

            table,  td , th{
                border: 1px solid black;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <a href="index.php">საწყის გვერდზე დაბრუნება</a>&nbsp;<a href="graphical.php" target="_blank">გრაფიკული გამოსახვა</a> 
          
            <div class="row">

                <div class="col-sm"> 
                    <table id="calculationTable" class="table" style="width:300px">
                        <tr >
                            <td>
                                <input id="distanceCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                            </td>
                            <td>მანძილი(კმ)</td>
                            <td colspan="3">
                                <input id="distanceInput" type="number" class="input" style="width:100%" value="0" disabled="true" onkeyup="incoming(event)" onfocus="this.select()">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input id="speedCheckBox" type="checkbox" onclick="checkCheckBoxes(event)" >
                            </td>
                            <td>სიჩქარე(კმ/ს)</td>
                            <td colspan="3">
                                <input id="speedInput" type="number" class="input" style="width:100%" value="15" disabled="true" onkeyup="incoming(event)" onfocus="this.select()">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input id="timeCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                            </td>
                            <td>დრო</td>
                            <td style="padding-left:0px; padding-right: 0px" >
                                <input id="hourInput" type="number" class="input" value="00" disabled="true" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                            </td>
                            <td style="padding-left:0px; padding-right: 0px">
                                <input id="minuteInput" type="number" class="input" value="00"  disabled="true"  oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                            </td>
                            <td style="padding-left:0px; padding-right: 0px">
                                <input id="secondInput" type="number" class="input" value="00" disabled="true"  oninput="adjastTimeInputs(event)" onkeyup="incoming(event)" onfocus="this.select()">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"><button class="btn btn-primary"  style="width:100%" onclick="checkAndCalculate()">გამოთვლა</button></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div id="notes"></div>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>
        <script>

            function incoming(event) {

                if (event.keyCode === 13) {

                    checkAndCalculate();
                }
            }
            function checkAndCalculate() {
                notes.innerHTML = "";
                if (inputsValid()) {
                    if (distanceCheckBox.checked && speedCheckBox.checked) {
                        calculateTime();
                    } else if (distanceCheckBox.checked && timeCheckBox.checked) {
                        calculateSpeed();
                    } else {
                        calculateDistance();
                    }


                }

            }

            function calculateDistance() {
                var timeSeconds = (hourInput.value * 60 * 60) + (minuteInput.value * 60) + (secondInput.value * 1);
                var distance = speedInput.value * timeSeconds / 3600;
                distanceInput.value = distance;
            }
            function calculateSpeed() {
                var timeSeconds = (hourInput.value * 60 * 60) + (minuteInput.value * 60) + (secondInput.value * 1);
                var speed = distanceInput.value / timeSeconds * 3600;
                speedInput.value = speed;
            }

            function calculateTime() {
                var timeInSeconds = (distanceInput.value / speedInput.value) * 3600;

                /*        console.log(timeInSeconds);
                 var date = new Date(0);
                 date.setSeconds(timeInSeconds);
                 var resultString = date.toISOString().substr(11, 8);
                 var splittedResult = resultString.split(":"); */
                var hour = parseInt(timeInSeconds / (60 * 60));
              
                var minuteNashti = timeInSeconds % (60 * 60);
                var minute = parseInt(minuteNashti / 60);
              
                var secondNashti = minuteNashti % 60;
                var second = parseInt(secondNashti);
                var nashti = secondNashti-second;

                hourInput.value = hour;
                minuteInput.value = minute;
                secondInput.value = second;

                if (nashti !== 0) {
                    notes.style.color = "red";
                    notes.innerHTML = "შედეგი არ არის ჯერადი, დარჩენილია ნაშთი :" + nashti + " Milliseconds";

                }

            }

            function inputsValid() {


                if (selectedParametres() < 2) {
                    notes.style.color = "red";
                    notes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    // redNotes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    return false;
                }
                if (distanceCheckBox.checked & speedCheckBox.checked) {
                    if (speedInput.value <= 0) {
                        notes.style.color = "red";
                        notes.innerHTML = "დაუშვებელია სიჩქარე იყოს 0 ან 0-ზე ნაკლები";
                        return false;
                        //    redNotes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                    }
                }
                if (distanceCheckBox.checked & timeCheckBox.checked) {
                    var time = (hourInput.value * 60 * 60) + (minuteInput.value * 60) + (secondInput.value * 1);

                    if (time <= 0) {
                        notes.style.color = "red";
                        notes.innerHTML = "დაუშვებელია დრო იყოს 0 ან 0-ზე ნაკლები";
                        return false;
                        //    redNotes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                    }
                }

                return true;
            }

            function   timeInputHourPlusPlus(targetInputs) {
                var x = parseInt(hourInput.value) + 1
                if (x < 10) {
                    hourInput.value = "0" + x;
                } else {
                    hourInput.value = x;
                }
            }

            function   timeInputHourMinusMinus(targetInputs) {


                var x = parseInt(hourInput.value) - 1
                if (x < 0) {
                    x = 0;
                    hourInput.value = 0;
                    minuteInput.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                }
                if (x < 10) {
                    hourInput.value = "0" + x;
                } else {
                    hourInput.value = x;
                }
            }


            function adjastTimeInputs(event) {
                notes.innerHTML = "";

                var targetTR = event.target.parentNode.parentNode.id;
                var targetInputs;



                if (secondInput.value == 60) {
                    secondInput.value = 0;
                    minuteInput.value = parseInt(minuteInput.value) + 1;
                }
                if (secondInput.value == -1) {
                    minuteInput.value = parseInt(minuteInput.value) - 1;
                    if (hourInput.value == 0 && minuteInput.value == -1) {
                        secondInput.value = 0;
                    } else {
                        secondInput.value = 59;
                    }
                }


                if (minuteInput.value == 60) {
                    minuteInput.value = 0;
                    timeInputHourPlusPlus(targetInputs);
                }
                if (minuteInput.value == -1) {

                    minuteInput.value = 59;
                    timeInputHourMinusMinus(targetInputs);
                }

                if (hourInput.value == -1) {
                    hourInput.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";

                }


            }


            function checkCheckBoxes(event) {
                var target = event.target;
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
        </script>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>

</html>
