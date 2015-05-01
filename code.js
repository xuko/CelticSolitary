var holes = document.getElementsByClassName("hole"); //hole divs
var pieces = new Array(holes.length); //Array of pieces, "false" for empty hole, "true" for piece in it
var selectedPiece = -1;
var selectedHole = -1;
var placed = false; //"true" if player has placed the pieces
var time = 0; //time in seconds, 0 for endless
var score = 0;
var user = "invitado";
var savedGame = new Array(holes.length);
var savedScore = 0;

var counter; //counter time left

function getUser() { //get user prompt
    var shadow = document.createElement("div");
    var msg = document.createElement("div");
    var textmsg = document.createElement("h2");
    var input = document.createElement("input");
    var acceptButton = document.createElement("submit");
    var cancelButton = document.createElement("button");
    shadow.id = "getUserMsg";
    shadow.className = "shadow";
    msg.className = "msg cream";
    textmsg.innerHTML = "Por favor introduce tu nombre";
    textmsg.style.paddingTop = "20px";
    input.type = "text";
    input.id = "userInput";
    input.autofocus = true;
    msg.style.textAlign = "center";
    acceptButton.className = "green";
    acceptButton.style.right = "10px";
    acceptButton.style.bottom = "10px";
    acceptButton.style.position = "absolute";
    cancelButton.className = "red";
    cancelButton.style.left = "10px";
    cancelButton.style.bottom = "10px";
    cancelButton.style.position = "absolute";
    acceptButton.type = "button";
    cancelButton.type = "button";
    acceptButton.innerHTML = "Aceptar";
    cancelButton.innerHTML = "Cancelar";
    cancelButton.setAttribute("onclick", "closeGetUser()");
    acceptButton.setAttribute("onclick", "acceptUser()");
    msg.appendChild(textmsg);
    msg.appendChild(input);
    msg.appendChild(acceptButton);
    msg.appendChild(cancelButton);
    shadow.appendChild(msg);
    document.body.appendChild(shadow);
}

function acceptUser() { //accept button in getUser
    var userSpan = document.createElement("span");
    userSpan.id = "user";
    user = document.getElementById("userInput").value;
    userSpan.innerHTML = "Usuario: " + user;
    document.getElementById("header").appendChild(userSpan);
    removeElementById("getUserMsg");
}

function closeGetUser() { //cancel button in getUser
    var userSpan = document.createElement("span");
    userSpan.id = "user";
    userSpan.innerHTML = "Usuario: " + user;
    document.getElementById("header").appendChild(userSpan);
    removeElementById("getUserMsg");
}

function msg(text) { //custom alert("text");
    var shadow = document.createElement("div");
    var box = document.createElement("div");
    var textmsg = document.createElement("h2");
    var acceptButton = document.createElement("button");
    shadow.id = "msg";
    shadow.className = "shadow";
    box.className = "msg cream";
    textmsg.innerHTML = text;
    textmsg.style.padding = "20px";
    textmsg.style.textAlign = "center";
    acceptButton.className = "green";
    acceptButton.style.right = "10px";
    acceptButton.style.bottom = "10px";
    acceptButton.style.position = "absolute";
    acceptButton.type = "button";
    acceptButton.innerHTML = "Aceptar";
    acceptButton.setAttribute("onclick", "removeElementById('msg')");
    box.appendChild(textmsg);
    box.appendChild(acceptButton);
    shadow.appendChild(box);
    document.body.appendChild(shadow);
}

function start() { //assign "false" to all pieces

    for (var i = 0; i < holes.length; i++) {
        removePiece(i);
    }

}

function play() { //set board and pieces

    time = parseInt(document.getElementById("time").value);
    if (isNaN(time) || time > 0) {
        btnPlayToStop();

        document.getElementById("btnPlace").disabled = true;
        document.getElementById("holeC").disabled = true;
        document.getElementById("holeR").disabled = true;
        document.getElementById("drag_drop").disabled = true;
        document.getElementById("time").disabled = true;
        document.getElementById("board").className = "cream";
        if (!document.getElementById("scoreboard")) createScoreboard();
        else document.getElementById("scoreboard").className = "cream";
        score = 0;
        document.getElementById("score").innerHTML = score;

        if (!placed) start();

        if (!isNaN(time)) {
            createTimeLeft();

            timeStart();
        }
        if (document.getElementById("holeC").checked && !placed)
            boardCenterHole();
        else if (document.getElementById("holeR").checked && !placed)
            boardRandomHole();

        if (document.getElementById("drag_drop").checked) {
            dragAndDrop();
        }

        for (var i = 0; i < holes.length; i++) {
            onClickMovement(i);
        }

    } else msg("Introduce un numero mayor que 0");
}

function createScoreboard() {
    var scoreboard = document.createElement("div");
    scoreboard.id = "scoreboard";
    scoreboard.className = "cream";
    scoreboard.innerHTML = "Puntuación: <span id='score'></span>";
    document.getElementById("leftSection").appendChild(scoreboard);
}

function removeElementById(id) { //remove an element from DOM
    var element = document.getElementById(id);
    element.parentNode.removeChild(element);
}

function createTimeLeft() {
    var game = document.getElementById("game");
    var board = document.getElementById("board");
    var timeLeft = document.createElement("div");
    var timeLeftBar = document.createElement("div");
    timeLeftBar.id = "timeLeftBar";
    timeLeft.id = "timeLeft";
    timeLeftBar.className = "green";
    timeLeft.className = "green";
    timeLeft.innerHTML = time;
    game.insertBefore(timeLeft, board);
    document.body.appendChild(timeLeftBar);
}

function btnPlayToStop() {
    document.getElementById("btnPlay").className = "red";
    document.getElementById("btnPlay").setAttribute("onclick", "stop()");
    document.getElementById("btnPlay").innerHTML = "Terminar";
    document.getElementById("btnPlay").id = "btnStop";
}

function btnStopToPlay() {
    document.getElementById("btnStop").className = "green";
    document.getElementById("btnStop").setAttribute("onclick", "play()");
    document.getElementById("btnStop").innerHTML = "Jugar";
    document.getElementById("btnStop").id = "btnPlay";
}

function btnCancelToPlace() {
    document.getElementById("btnCancel").className = "blue";
    document.getElementById("btnCancel").setAttribute("onclick", "place()");
    document.getElementById("btnCancel").innerHTML = "Situar";
    document.getElementById("btnCancel").id = "btnPlace";
}

function btnPlaceToCancel() {
    document.getElementById("btnPlace").className = "red";
    document.getElementById("btnPlace").setAttribute("onclick", "cancel()");
    document.getElementById("btnPlace").innerHTML = "Cancelar";
    document.getElementById("btnPlace").id = "btnCancel";
}

function stop() {
    clearInterval(counter);
    btnStopToPlay();
    placed = false;
    document.getElementById("btnPlace").disabled = false;
    document.getElementById("holeC").disabled = false;
    document.getElementById("holeR").disabled = false;
    document.getElementById("drag_drop").disabled = false;
    document.getElementById("time").disabled = false;
    if (score >= 0) {
        document.getElementById("scoreboard").className = "green";

    } else document.getElementById("scoreboard").className = "red";
    if (document.getElementById("timeLeft")) {
        removeElementById("timeLeft");
        removeElementById("timeLeftBar");
    }
    for (var i = 0; i < holes.length; i++) {
        onClickNull(i);
    }
    if (document.getElementById("drag_drop").checked) {
        for (var j = 0; j < holes.length; j++) {
            holes[j].draggable = false;
        }
    }
}

function timeStart() {
    counter = setInterval(timer, 1000);
    var count = parseInt(document.getElementById("time").value);

    function timer() {
        count -= 1;
        document.getElementById("timeLeftBar").style.width = (count) * 100 / time + "%";
        document.getElementById("timeLeft").innerHTML = count;

        if (count < 20) {
            document.getElementById("timeLeft").className = "red";
            document.getElementById("timeLeftBar").className = "red";
        }
        if (count <= 0) {
            clearInterval(counter);
            msg("Tiempo agotado!!!");
            score -= remainingPieces().length * 50;
            document.getElementById("score").innerHTML = score;
            stop();
        }
    }
}

function onClickPlacePiece(i) {
    holes[i].onclick = function() {
        placePiece(i);
    };
}

function onClickMovement(i) {

    holes[i].onclick = function() {
        selectPieceHole(i);
        movement();
    };
}

function onClickNull(i) {
    holes[i].onclick = null;
}

function movement() {



    if (selectedPiece != -1 && selectedHole != -1) {

        switch (selectedPiece) {
            case 0:
                switch (selectedHole) {
                    case 2:
                        if (pieces[1]) eatPiece(1);
                        else cancelMovement();
                        break;
                    case 8:
                        if (pieces[3]) eatPiece(3);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();
                }
                break;
            case 1:
                switch (selectedHole) {
                    case 9:
                        if (pieces[4]) eatPiece(4);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 2:
                switch (selectedHole) {
                    case 0:
                        if (pieces[1]) eatPiece(1);
                        else cancelMovement();
                        break;
                    case 10:
                        if (pieces[5]) eatPiece(5);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 3:
                switch (selectedHole) {
                    case 5:
                        if (pieces[4]) eatPiece(4);
                        else cancelMovement();
                        break;
                    case 15:
                        if (pieces[8]) eatPiece(8);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 4:
                switch (selectedHole) {
                    case 16:
                        if (pieces[9]) eatPiece(9);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 5:
                switch (selectedHole) {
                    case 3:
                        if (pieces[4]) eatPiece(4);
                        else cancelMovement();
                        break;
                    case 17:
                        if (pieces[10]) eatPiece(10);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 6:
                switch (selectedHole) {
                    case 8:
                        if (pieces[7]) eatPiece(7);
                        else cancelMovement();
                        break;
                    case 20:
                        if (pieces[13]) eatPiece(13);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 7:
                switch (selectedHole) {
                    case 9:
                        if (pieces[8]) eatPiece(8);
                        else cancelMovement();
                        break;
                    case 21:
                        if (pieces[14]) eatPiece(14);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 8:
                switch (selectedHole) {
                    case 0:
                        if (pieces[3]) eatPiece(3);
                        else cancelMovement();
                        break;
                    case 6:
                        if (pieces[7]) eatPiece(7);
                        else cancelMovement();
                        break;
                    case 10:
                        if (pieces[9]) eatPiece(9);
                        else cancelMovement();
                        break;
                    case 22:
                        if (pieces[15]) eatPiece(15);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 9:
                switch (selectedHole) {
                    case 1:
                        if (pieces[4]) eatPiece(4);
                        else cancelMovement();
                        break;
                    case 7:
                        if (pieces[8]) eatPiece(8);
                        else cancelMovement();
                        break;
                    case 11:
                        if (pieces[10]) eatPiece(10);
                        else cancelMovement();
                        break;
                    case 23:
                        if (pieces[16]) eatPiece(16);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 10:
                switch (selectedHole) {
                    case 2:
                        if (pieces[5]) eatPiece(5);
                        else cancelMovement();
                        break;
                    case 8:
                        if (pieces[9]) eatPiece(9);
                        else cancelMovement();
                        break;
                    case 12:
                        if (pieces[11]) eatPiece(11);
                        else cancelMovement();
                        break;
                    case 24:
                        if (pieces[17]) eatPiece(17);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 11:
                switch (selectedHole) {
                    case 9:
                        if (pieces[10]) eatPiece(10);
                        else cancelMovement();
                        break;
                    case 25:
                        if (pieces[18]) eatPiece(18);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;

            case 12:
                switch (selectedHole) {
                    case 10:
                        if (pieces[11]) eatPiece(11);
                        else cancelMovement();
                        break;
                    case 26:
                        if (pieces[19]) eatPiece(19);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 13:
                switch (selectedHole) {
                    case 15:
                        if (pieces[14]) eatPiece(14);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 14:
                switch (selectedHole) {
                    case 16:
                        if (pieces[15]) eatPiece(15);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 15:
                switch (selectedHole) {
                    case 3:
                        if (pieces[8]) eatPiece(8);
                        else cancelMovement();
                        break;
                    case 13:
                        if (pieces[14]) eatPiece(14);
                        else cancelMovement();
                        break;
                    case 17:
                        if (pieces[16]) eatPiece(16);
                        else cancelMovement();
                        break;
                    case 27:
                        if (pieces[22]) eatPiece(22);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 16:
                switch (selectedHole) {
                    case 4:
                        if (pieces[9]) eatPiece(9);
                        else cancelMovement();
                        break;
                    case 14:
                        if (pieces[15]) eatPiece(15);
                        else cancelMovement();
                        break;
                    case 18:
                        if (pieces[17]) eatPiece(17);
                        else cancelMovement();
                        break;
                    case 28:
                        if (pieces[23]) eatPiece(23);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 17:
                switch (selectedHole) {
                    case 5:
                        if (pieces[10]) eatPiece(10);
                        else cancelMovement();
                        break;
                    case 15:
                        if (pieces[16]) eatPiece(16);
                        else cancelMovement();
                        break;
                    case 19:
                        if (pieces[18]) eatPiece(18);
                        else cancelMovement();
                        break;
                    case 29:
                        if (pieces[24]) eatPiece(24);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 18:
                switch (selectedHole) {
                    case 16:
                        if (pieces[17]) eatPiece(17);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 19:
                switch (selectedHole) {
                    case 17:
                        if (pieces[18]) eatPiece(18);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 20:
                switch (selectedHole) {
                    case 6:
                        if (pieces[13]) eatPiece(13);
                        else cancelMovement();
                        break;
                    case 22:
                        if (pieces[21]) eatPiece(21);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 21:
                switch (selectedHole) {
                    case 7:
                        if (pieces[14]) eatPiece(14);
                        else cancelMovement();
                        break;
                    case 23:
                        if (pieces[22]) eatPiece(22);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 22:
                switch (selectedHole) {
                    case 8:
                        if (pieces[15]) eatPiece(15);
                        else cancelMovement();
                        break;
                    case 20:
                        if (pieces[21]) eatPiece(21);
                        else cancelMovement();
                        break;
                    case 24:
                        if (pieces[23]) eatPiece(23);
                        else cancelMovement();
                        break;
                    case 30:
                        if (pieces[27]) eatPiece(27);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 23:
                switch (selectedHole) {
                    case 9:
                        if (pieces[16]) eatPiece(16);
                        else cancelMovement();
                        break;
                    case 21:
                        if (pieces[22]) eatPiece(22);
                        else cancelMovement();
                        break;
                    case 25:
                        if (pieces[24]) eatPiece(24);
                        else cancelMovement();
                        break;
                    case 31:
                        if (pieces[28]) eatPiece(28);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 24:
                switch (selectedHole) {
                    case 10:
                        if (pieces[17]) eatPiece(17);
                        else cancelMovement();
                        break;
                    case 22:
                        if (pieces[23]) eatPiece(23);
                        else cancelMovement();
                        break;
                    case 26:
                        if (pieces[25]) eatPiece(25);
                        else cancelMovement();
                        break;
                    case 32:
                        if (pieces[29]) eatPiece(29);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 25:
                switch (selectedHole) {
                    case 11:
                        if (pieces[18]) eatPiece(18);
                        else cancelMovement();
                        break;
                    case 23:
                        if (pieces[24]) eatPiece(24);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 26:
                switch (selectedHole) {
                    case 12:
                        if (pieces[19]) eatPiece(19);
                        else cancelMovement();
                        break;
                    case 24:
                        if (pieces[25]) eatPiece(25);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 27:
                switch (selectedHole) {
                    case 15:
                        if (pieces[22]) eatPiece(22);
                        else cancelMovement();
                        break;
                    case 29:
                        if (pieces[28]) eatPiece(28);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 28:
                switch (selectedHole) {
                    case 16:
                        if (pieces[23]) eatPiece(23);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 29:
                switch (selectedHole) {
                    case 17:
                        if (pieces[24]) eatPiece(24);
                        else cancelMovement();
                        break;
                    case 27:
                        if (pieces[28]) eatPiece(28);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 30:
                switch (selectedHole) {
                    case 22:
                        if (pieces[27]) eatPiece(27);
                        else cancelMovement();
                        break;
                    case 32:
                        if (pieces[31]) eatPiece(31);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 31:
                switch (selectedHole) {
                    case 23:
                        if (pieces[28]) eatPiece(28);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();

                }
                break;
            case 32:
                switch (selectedHole) {
                    case 24:
                        if (pieces[29]) eatPiece(29);
                        else cancelMovement();
                        break;
                    case 30:
                        if (pieces[31]) eatPiece(31);
                        else cancelMovement();
                        break;
                    default:
                        cancelMovement();
                }
                break;
            default:
                selectedPiece = -1;
                selectedHole = -1;

        }

        if (document.getElementById("drag_drop").checked) {
            dragAndDrop();
        }

        checkEnded();

    }

}

function remainingPieces() {
    var remaining_Pieces = [];
    for (var i = 0; i < holes.length; i++) {

        if (pieces[i] === true) {
            remaining_Pieces.push(i);
        }
    }
    return remaining_Pieces;
}

function checkEnded() {
    var remaining_Pieces = remainingPieces();
    var numberOfMovements = 0;
    if (remaining_Pieces.length == 1) {
        msg("Felicidades!!! Juego terminado");
        if (remaining_Pieces[0] == 16) {
            score += 150;
            document.getElementById("score").innerHTML = score;
        }
        stop();
    } else {
        for (var i = 0; i < remaining_Pieces.length; i++) {

            switch (remaining_Pieces[i]) {
                case 0:
                    if (pieces[1] && !pieces[2]) numberOfMovements++;
                    if (pieces[3] && !pieces[8]) numberOfMovements++;
                    break;
                case 1:
                    if (pieces[4] && !pieces[9]) numberOfMovements++;
                    break;
                case 2:
                    if (pieces[1] && !pieces[0]) numberOfMovements++;
                    if (pieces[5] && !pieces[10]) numberOfMovements++;
                    break;
                case 3:
                    if (pieces[4] && !pieces[5]) numberOfMovements++;
                    if (pieces[8] && !pieces[15]) numberOfMovements++;
                    break;
                case 4:
                    if (pieces[9] && !pieces[16]) numberOfMovements++;
                    break;
                case 5:
                    if (pieces[4] && !pieces[3]) numberOfMovements++;
                    if (pieces[10] && !pieces[17]) numberOfMovements++;
                    break;
                case 6:
                    if (pieces[7] && !pieces[8]) numberOfMovements++;
                    if (pieces[13] && !pieces[20]) numberOfMovements++;
                    break;
                case 7:
                    if (pieces[8] && !pieces[9]) numberOfMovements++;
                    if (pieces[14] && !pieces[21]) numberOfMovements++;
                    break;
                case 8:
                    if (pieces[3] && !pieces[0]) numberOfMovements++;
                    if (pieces[7] && !pieces[6]) numberOfMovements++;
                    if (pieces[9] && !pieces[10]) numberOfMovements++;
                    if (pieces[15] && !pieces[22]) numberOfMovements++;
                    break;
                case 9:
                    if (pieces[4] && !pieces[1]) numberOfMovements++;
                    if (pieces[8] && !pieces[7]) numberOfMovements++;
                    if (pieces[10] && !pieces[11]) numberOfMovements++;
                    if (pieces[16] && !pieces[23]) numberOfMovements++;
                    break;
                case 10:
                    if (pieces[5] && !pieces[2]) numberOfMovements++;
                    if (pieces[9] && !pieces[8]) numberOfMovements++;
                    if (pieces[11] && !pieces[12]) numberOfMovements++;
                    if (pieces[17] && !pieces[24]) numberOfMovements++;
                    break;
                case 11:
                    if (pieces[10] && !pieces[9]) numberOfMovements++;
                    if (pieces[18] && !pieces[25]) numberOfMovements++;
                    break;

                case 12:
                    if (pieces[11] && !pieces[10]) numberOfMovements++;
                    if (pieces[19] && !pieces[26]) numberOfMovements++;
                    break;
                case 13:
                    if (pieces[14] && !pieces[15]) numberOfMovements++;
                    break;
                case 14:
                    if (pieces[15] && !pieces[16]) numberOfMovements++;
                    break;
                case 15:
                    if (pieces[8] && !pieces[3]) numberOfMovements++;
                    if (pieces[14] && !pieces[13]) numberOfMovements++;
                    if (pieces[16] && !pieces[17]) numberOfMovements++;
                    if (pieces[22] && !pieces[27]) numberOfMovements++;
                    break;
                case 16:
                    if (pieces[9] && !pieces[4]) numberOfMovements++;
                    if (pieces[15] && !pieces[14]) numberOfMovements++;
                    if (pieces[17] && !pieces[18]) numberOfMovements++;
                    if (pieces[23] && !pieces[28]) numberOfMovements++;
                    break;
                case 17:
                    if (pieces[10] && !pieces[5]) numberOfMovements++;
                    if (pieces[16] && !pieces[15]) numberOfMovements++;
                    if (pieces[18] && !pieces[19]) numberOfMovements++;
                    if (pieces[24] && !pieces[29]) numberOfMovements++;
                    break;
                case 18:
                    if (pieces[17] && !pieces[16]) numberOfMovements++;
                    break;
                case 19:
                    if (pieces[18] && !pieces[17]) numberOfMovements++;
                    break;
                case 20:
                    if (pieces[13] && !pieces[6]) numberOfMovements++;
                    if (pieces[21] && !pieces[22]) numberOfMovements++;
                    break;
                case 21:
                    if (pieces[14] && !pieces[7]) numberOfMovements++;
                    if (pieces[22] && !pieces[23]) numberOfMovements++;
                    break;
                case 22:
                    if (pieces[15] && !pieces[8]) numberOfMovements++;
                    if (pieces[21] && !pieces[20]) numberOfMovements++;
                    if (pieces[23] && !pieces[24]) numberOfMovements++;
                    if (pieces[27] && !pieces[30]) numberOfMovements++;
                    break;
                case 23:
                    if (pieces[16] && !pieces[9]) numberOfMovements++;
                    if (pieces[22] && !pieces[21]) numberOfMovements++;
                    if (pieces[24] && !pieces[25]) numberOfMovements++;
                    if (pieces[28] && !pieces[31]) numberOfMovements++;
                    break;
                case 24:
                    if (pieces[17] && !pieces[10]) numberOfMovements++;
                    if (pieces[23] && !pieces[22]) numberOfMovements++;
                    if (pieces[25] && !pieces[26]) numberOfMovements++;
                    if (pieces[29] && !pieces[32]) numberOfMovements++;
                    break;
                case 25:
                    if (pieces[18] && !pieces[11]) numberOfMovements++;
                    if (pieces[24] && !pieces[23]) numberOfMovements++;
                    break;
                case 26:
                    if (pieces[19] && !pieces[12]) numberOfMovements++;
                    if (pieces[25] && !pieces[24]) numberOfMovements++;
                    break;
                case 27:
                    if (pieces[22] && !pieces[15]) numberOfMovements++;
                    if (pieces[28] && !pieces[29]) numberOfMovements++;
                    break;
                case 28:
                    if (pieces[23] && !pieces[16]) numberOfMovements++;
                    break;
                case 29:
                    if (pieces[24] && !pieces[17]) numberOfMovements++;
                    if (pieces[28] && !pieces[27]) numberOfMovements++;
                    break;
                case 30:
                    if (pieces[27] && !pieces[22]) numberOfMovements++;
                    if (pieces[31] && !pieces[32]) numberOfMovements++;
                    break;
                case 31:
                    if (pieces[28] && !pieces[23]) numberOfMovements++;
                    break;
                case 32:
                    if (pieces[29] && !pieces[24]) numberOfMovements++;
                    if (pieces[31] && !pieces[30]) numberOfMovements++;
                    break;
                default:
                    numberOfMovements = 0;

            }
        }
        if (numberOfMovements == 0) {

            score -= remaining_Pieces.length * 50;
            document.getElementById("score").innerHTML = score;
            msg("No hay más movimientos");
            stop();
        }
    }
}

function pieceSelected() {
    holes[selectedPiece].className = "hole pieceSelected";
}

function pieceUnselected() {
    holes[selectedPiece].className = "hole pieceUnselected";
}

function selectPieceHole(i) {
    if (pieces[i]) {
        if (selectedPiece != -1) pieceUnselected();
        selectedPiece = i;
        pieceSelected();
    } else if (selectedPiece != -1) selectedHole = i;
}

function eatPiece(i) {
    pieceUnselected();
    removePiece(selectedPiece);
    removePiece(i);
    addPiece(selectedHole);
    score += 15;
    document.getElementById("score").innerHTML = score;
    selectedPiece = -1;
    selectedHole = -1;

}

function cancelMovement() {
    pieceUnselected();

    selectedPiece = -1;
    selectedHole = -1;

}

function readyToPlay() {
    var placedPieces = 0;
    var emptyHoles = 0;
    for (var i = 0; i < holes.length; i++) {

        if (pieces[i] === true) {
            placedPieces++;
        } else emptyHoles++;
    }
    if (placedPieces > 1 && emptyHoles > 0) return true;
    else {
        msg("Situa más de una ficha y deja al menos un hueco libre");
        return false;
    }
}

function boardCenterHole() { //place pieces with center hole
    removePieces();
    for (var i = 0; i < holes.length; i++) {
        if (i != 16) {
            addPiece(i);
        } else removePiece(i);

    }
}

function boardRandomHole() { //place pieces with random hole
    removePieces();
    var x = Math.floor(Math.random() * 33);
    for (var i = 0; i < holes.length; i++) {
        if (i != x) {
            addPiece(i);
        } else removePiece(i);

    }
}

function removePieces() { //remove all pieces from board

    for (var i = 0; i < holes.length; i++) {

        removePiece(i);

    }
}

function cancel() {
    removePieces();
    btnCancelToPlace();
    placed = false;
    document.getElementById("holeC").disabled = false;
    document.getElementById("holeR").disabled = false;
    document.getElementById("board").className = "cream";
    document.getElementById("btnPlay").setAttribute("onclick", "play()");
    for (var i = 0; i < holes.length; i++) {
        onClickNull(i);

    }
}

function playPlace() {
    if (readyToPlay()) {
        btnCancelToPlace();
        document.getElementById("btnPlay").setAttribute("onclick", "play()");
        play();
    }
}

function place() { //place pieces where you want
    btnPlaceToCancel();
    document.getElementById("holeC").disabled = true;
    document.getElementById("holeR").disabled = true;
    if (document.getElementById("scoreboard")) removeElementById("scoreboard");
    document.getElementById("board").className = "blue";
    document.getElementById("btnPlay").setAttribute("onclick", "playPlace()");
    for (var i = 0; i < holes.length; i++) {
        onClickPlacePiece(i);

    }
    start();
    if (!placed) removePieces();
    document.getElementById("holeC").disabled = true;
    document.getElementById("holeR").disabled = true;
    placed = true;

}

function addPiece(i) { //add piece in the hole i
    holes[i].className = "hole piece";
    pieces[i] = true;
}

function removePiece(i) { //remove piece from the hole i
    holes[i].className = "hole empty";
    pieces[i] = false;
}

function placePiece(i) { //place piece in the hole i
    if (pieces[i] === false) {
        addPiece(i);

    } else {
        removePiece(i);

    }
}


function dragAndDrop() {
    for (var i = 0; i < holes.length; i++) {
        if (pieces[i]) {
            makedraggable(i);
        } else {
            makedroppable(i);
        }
    }
}

function makedraggable(i) {
    holes[i].draggable = true;
    holes[i].ondragstart = function() {
        drag(i);
    };

}

function makedroppable(i) {
    holes[i].draggable = false;
    holes[i].ondragover = function() {
        allowDrop(event);
    };
    holes[i].ondrop = function() {
        drop(i);
    };

}

function makeUndraggable(i) {
    holes[i].draggable = false;
}

function drag(i) {
    selectedPiece = i;
    pieceSelected();
}

function drop(i) {
    selectedHole = i;
    movement();
}

function allowDrop(event) {
    event.preventDefault();
}

function saveGame() {
    for (var i = 0; i < holes.length; i++) {
        savedGame[i] = pieces[i];
    }
    savedScore = score;
}

function loadGame() {
    for (var i = 0; i < holes.length; i++) {
        if (savedGame[i]) {
            addPiece(i);
        } else {
            removePiece(i);
        }
    }
    placed = true;
    score = savedScore;
    play();
}