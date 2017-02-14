<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="util/jcanvas.min.js"></script>
<script>
// Cross-browser support for requestAnimationFrame
var w = window;
requestAnimationFrame = w.requestAnimationFrame
    || w.webkitRequestAnimationFrame
    || w.msRequestAnimationFrame
    || w.mozRequestAnimationFrame;

var h = $(w).height() - 30;
var hF = h / 500;

var Direction = {
    LEFT: -1,
    NONE: 0,
    RIGHT: 1
}

var EnemyType = {
    FIRST: 0,
    SECOND: 1,
    THIRD: 2,
    UFO: 3
}

var dir = Direction.NONE;

var shipX = 250 * hF;
var shipY = 450 * hF;

var shotsFired = 0;
var canShoot = Date.now();
var shotCooldown = 650;
var shotSpeed = 750 * hF;

var speed = hF * 135;

var lastUpdate = Date.now();

var shots = [];
function shot(x, y){
    this.x = x;
    this.y = y;
    shots[shots.length] = this;
}

var enemies = [[],[],[],[],[],[],[],[],[],[],[]];
var enemySpeed = 5 * hF;
var enemiesAlive = 55;
var eSpeedCoeff = 1;
function enemy(x, y, type, alive){
    this.x = x;
    this.y = y;
    this.type = type;
    this.alive = alive;
}

//Enemy reset
function resetEnemies(){
    corner = {x: 50 * hF, y: 200 * hF};
    enemiesAlive = 55;
    rOffLeft = 0;
    rOffRight = 0;
    offBot = 0;
    var n;
    var k;
    var posX;
    var posY;
    var eWidth = 25;
    var eRatio;
    for (n = 0; n < 11; n++){
        for (k = 0; k < 5; k++){
            posX = n + 1;
            posY = k + 1;
            switch (k) {
                case 0:
                eRatio = 76 / 120;
                enemies[n][k] = new enemy(posX, posY, EnemyType.FIRST, true);
                 $("#c").drawImage({
                     name: "e" + n + k,
                     source: "spaceinvaders/images/a1n.png",
                     x: corner.x + ((eWidth + 10) * hF * n),
                     y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                     width: eWidth * hF,
                     height: eWidth * eRatio * hF,
                     layer: true
                 });
                break;
                case 1:
                eRatio = 76 / 120;
                enemies[n][k] = new enemy(posX, posY, EnemyType.FIRST, true);
                 $("#c").drawImage({
                     name: "e" + n + k,
                     source: "spaceinvaders/images/a1n.png",
                     x: corner.x + ((eWidth + 10) * hF * n),
                     y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                     width: eWidth * hF,
                     height: eWidth * eRatio * hF,
                     layer: true
                 });
                break;
                case 2:
                eRatio = 68 / 96;
                enemies[n][k] = new enemy(posX, posY, EnemyType.SECOND, true);
                 $("#c").drawImage({
                     name: "e" + n + k,
                     source: "spaceinvaders/images/a2n.png",
                     x: corner.x + ((eWidth + 10) * hF * n),
                     y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                     width: eWidth * hF,
                     height: eWidth * eRatio * hF,
                     layer: true
                 });
                break;
                case 3:
                eRatio = 68 / 96;
                enemies[n][k] = new enemy(posX, posY, EnemyType.SECOND, true);
                 $("#c").drawImage({
                     name: "e" + n + k,
                     source: "spaceinvaders/images/a2n.png",
                     x: corner.x + ((eWidth + 10) * hF * n),
                     y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                     width: eWidth * hF,
                     height: eWidth * eRatio * hF,
                     layer: true
                 });
                break;
                case 4:
                eRatio = 70 / 70;
                enemies[n][k] = new enemy(posX, posY, EnemyType.THIRD, true);
                 $("#c").drawImage({
                     name: "e" + n + k,
                     source: "spaceinvaders/images/a3n.png",
                     x: corner.x + ((eWidth + 10) * hF * n),
                     y: corner.y - (((eWidth - 7) * eRatio + 17) * hF * k),
                     width: (eWidth - 5) * hF,
                     height: (eWidth - 5) * eRatio * hF,
                     layer: true
                 });

                break;
            }
        }
    }
}

//Redraw enemies
function drawEnemies(alt){
    var n;
    var k;
    var posX;
    var posY;
    var eWidth = 25;
    var eRatio;
    var source;
    for (n = 0; n < 11; n++){
        for (k = 0; k < 5; k++){
            posX = n + 1;
            posY = k + 1;
            if (enemies[n][k].alive){
                switch (k) {
                    case 0:
                    case 1:
                        if (alt) { source = "spaceinvaders/images/a1a.png";}
                        else { source = "spaceinvaders/images/a1n.png";}
                        eRatio = 76 / 120;
                        $("#c").setLayer("e" + n + k, {
                            x: corner.x + ((eWidth + 10) * hF * n),
                            y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                            source: source
                        });
                    break;
                    case 2:
                    case 3:
                        if (alt) {source = "spaceinvaders/images/a2a.png";}
                        else { source = "spaceinvaders/images/a2n.png";}
                        eRatio = 68 / 96;
                        $("#c").setLayer("e" + n + k, {
                            x: corner.x + ((eWidth + 10) * hF * n),
                            y: corner.y - ((eWidth * eRatio + 17) * hF * k),
                            source: source
                        });
                    break;
                    case 4:
                        if (alt){ source = "spaceinvaders/images/a3a.png";}
                        else { source = "spaceinvaders/images/a3n.png";}
                        eRatio = 70 / 70;
                        $("#c").setLayer("e" + n + k, {
                            x: corner.x + ((eWidth + 10) * hF * n),
                            y: corner.y - (((eWidth - 7) * eRatio + 17) * hF * k),
                            source: source
                        });
                    break;
                }
            } else {
                $("#c").removeLayer("e" + n + k);
            }
        }
    }
}

//Main loop

var over = false;
function main(){
    if (!over){
        now = Date.now();
        elapsed = now - lastUpdate;
        update(elapsed);
        lastUpdate = now;
        requestAnimationFrame(main);
    }
}

var lastAlt = Date.now();
var isAlt = false;
var bombIntFact = 350
var bombTime = Date.now() + Math.random() * bombIntFact;
var bombsToRemove = [];
var bTRTimers = [];
var bombSpeed = 100 * hF;
var pause = false;

var offBot = 0;

var ufoTimer = (Math.random() * 5000 + 10000) + Date.now();
var ufoExists = false;

var alerted = false;
var firstUpdate = true;
//Position updater
function update(modifier){
    if (modifier > (100) && !firstUpdate && !alerted){
        alerted = true;
        window.alert("Your browser appears to be running slowly. This can cause bugs. If they occur try refreshing or opening a new browser tab.");
    }
    firstUpdate = false;

    if (pause) {
        bombTime = now + Math.random() * bombIntFact;
        return; }

    if (!pause && now > bombTime){
        dropBomb();
        bombTime += 10 * Math.random() * bombIntFact;
    }

    if (ufoTimer < now){
        $("#c").drawImage({
             name: "ufo",
             source: "spaceinvaders/images/ufo.png",
             x: 500 * hF,
             y: 25 * hF,
             width: 40 * hF,
             height: 40 * hF * 68/140,
             layer: true
         });
         ufoExists = true;
    }

    if (ufoExists){
        var newX = $("#c").getLayer("ufo").x -= (50 * hF * modifier / 1000);
        $("#c").setLayer("ufo", {x: newX});
        if (newX <= 0){
            $("#c").removeLayer("ufo");
            ufoExists = false;
            ufoTimer = (Math.random() * 10000 + 5000) + now;
        }
    }

    if (enemySpeed > 0){
        enemySpeed += 0.75 * hF * modifier / 1000;
    } else {
        enemySpeed -= 0.75 * hF * modifier / 1000;
    }

    switch(dir){
        case Direction.LEFT:
         if ((shipX - (20 * hF)) >= 0){
           shipX -= speed * modifier / 1000;
           $("#c").setLayer("ship", {
             x: shipX,
             y: shipY,
           })
         } else {
           dir = Direction.NONE;
         }
        break;

        case Direction.NONE:

        break;

        case Direction.RIGHT:
          if ((shipX + (20 * hF)) <= (500 * hF)){
            shipX += speed * modifier / 1000;
            $("#c").setLayer("ship", {
              x: shipX,
              y: shipY,
            })
          } else {
          dir = Direction.NONE;
          }
        break;
    }

    if (willShoot && (now > canShoot)){
        canShoot = now + shotCooldown;
        shoot(shipX, shipY);
    }
    willShoot = false;

    var p;
    var bombDif = bombSpeed * modifier / 1000;
    for (p = 0; p < bombs.length; p++){
            var newY = $("#c").getLayer(bombs[p]).y + bombDif;
            if (newY <= 500 * hF){
                $("#c").setLayer(bombs[p], {
                y: newY
                }).drawLayers();
                var thisB = $("#c").getLayer(bombs[p]);
                var ship = $("#c").getLayer("ship");
                if (testColl(ship.x, ship.y, (ship.width - (317 * hF)), ship.height,
                             thisB.x, thisB.y, thisB.width, thisB.height) ||
                    testColl(ship.x, ship.y, (ship.width), (ship.height - ship.height),
                             thisB.x, thisB.y, thisB.width, thisB.height) ||
                    testColl(ship.x, ship.y, (ship.width), (ship.height - ship.height),
                             thisB.x, thisB.y, thisB.width, thisB.height)){
                   death();
                   $("#c").setLayer(bombs[p], {
                       width: 10 * hF,
                       height: 10 * hF * 228 / 150,
                       source: "spaceinvaders/images/explosion1.png"
                   });
                   bombsToRemove[bombsToRemove.length] = bombs[p];
                   bTRTimers[bTRTimers.length] = now + 100;
                   bombs.splice(p,1);
                   break;
                }
                var q;
                for (q = 0; q < shots.length; q++){
                    var thisShot = $("#c").getLayer(shots[q]);
                    if (testColl(thisShot.x, thisShot.y, thisShot.width, thisShot.height,
                                 thisB.x, thisB.y, thisB.width, thisB.height)){
                        $("#c").setLayer(bombs[p], {
                            width: 10 * hF,
                            height: 10 * hF * 228 / 150,
                            source: "spaceinvaders/images/explosion1.png"
                        });
                        bombsToRemove[bombsToRemove.length] = bombs[p];
                        bTRTimers[bTRTimers.length] = now + 100;
                        bombs.splice(p,1);
                        break;
                    }
                }
            } else {
                $("#c").removeLayer(bombs[p]);
                bombs.splice(p,1);
            }
    }

    var c;
    for (c = 0; c < bombsToRemove.length; c++){
        if (bTRTimers[c] < now){
        $("#c").removeLayer(bombsToRemove[c]);
        bombsToRemove.splice(c,1);
        bTRTimers.splice(c,1);
        }
    }

    var i;
    var shotDif = shotSpeed * modifier / 1000;
    var n;
    var k;
    var j;
    for (i = 0; i<shots.length; i++){
        var thisS = $("#c").getLayer(shots[i]);
        var broken = false;
        if (ufoExists){
            var ufoT = $("#c").getLayer("ufo");
            if (testColl(ufoT.x, ufoT.y, ufoT.width, ufoT.height,
                         thisS.x, thisS.y, thisS.width, thisS.height)){
                $("#c").removeLayer("ufo");
                ufoExists = false;
                ufoTimer = (Math.random() * 10000 + 5000) + now;
                score += 100;
                $("#c").setLayer("score", {text: "Score: " + score});
                $("#c").removeLayer(shots[i]);
                shots.splice(i,1);
                broken = true;
            }
        }
        for (n = 0; n < 11; n++){
           for (k = 0; k < 5; k++){
              if (enemies[n][k].alive){
                var thisE = $("#c").getLayer("e" + n + k);
                if (testColl(thisS.x, thisS.y, thisS.width, thisS.height,
                             thisE.x, thisE.y, thisE.width, thisE.height)){
                    $("#c").removeLayer(shots[i]);
                    shots.splice(i,1);
                    $("#c").removeLayer("e" + n + k);
                    enemies[n][k].alive = false;
                    enemiesAlive--;
                    switch (enemies[n][k].type){
                        case 0:
                            score += 10;
                        break;
                        case 1:
                            score += 20;
                        break;
                        case 2:
                            score += 30;
                        break;
                    }

                    $("#c").setLayer("score", {text: "Score: " + score});

                    var i;
                    var j;
                    for (i = 0; i < 11; i++){
                    var removeRow = true;
                    for (j = 0; j < 5; j++){
                        if (enemies[i][j].alive){
                            removeRow = false;
                            console.log("test");
                        }
                    }
                    if (removeRow){
                        if (i == rOffLeft){
                            rOffLeft++;
                        }
                        if (i == (10 - rOffRight)){
                            rOffRight++;
                        }
                    }
                    }

                    broken = true;
                    break;
                }
              }
            }
            if (broken){ break;
            broken = false;}
        }

        var liveCounters = [0,0,0,0,0]
        var n;
        var k;
        for (n = 0; n < 11; n++){
        for (k = 0; k < 5; k++){
            if (enemies[n][k].alive){
                liveCounters[k]++;
            }
        }}
        var u;
        for (u = offBot; u < 5; u++){
            if (liveCounters[u] == 0){
                offBot++;
                break;
            }
        }

        if (broken != true){
            $("#c").getLayer(shots[i]).y -= shotDif;
            var newY = $("#c").getLayer(shots[i]).y;
            if (newY > 0){
                $("#c").setLayer(shots[i], {
                y: newY
                }).drawLayers();
            } else {
                $("#c").removeLayer(shots[i]);
                shots.splice(i,1);
            }
        }
    }

    if ((corner.x + ((-12.5 + (rOffLeft * 35)) * hF)) <= 0){
        eSpeedCoeff *= -1;
       // corner.x += -((corner.x + (((enemySpeed * eSpeedCoeff * 2 * modifier / 1000) + (rOffLeft * 35) - 12.5) * hF)));
        corner.y += (17 * hF);
    }
    if ((corner.x + (10 * 35 + 12.5 - (rOffRight * 35)) * hF) >= (500 * hF)){
        eSpeedCoeff *= -1;
        //corner.x += ((500 * hF) - (corner.x + (10 * 35 + (enemySpeed * eSpeedCoeff * 2 * modifier / 1000) - (rOffRight * 35)) * hF));
        corner.y += (17 * hF);
    }

    corner.x += eSpeedCoeff * enemySpeed * hF * modifier / 1000;

    //enemySpeed = eSpeedCoeff * (925 / ((4 * enemiesAlive) - 35));

    if ((now - lastAlt) > 500){
        if (isAlt){
            isAlt = false;
        } else {
            isAlt = true;
        }
        lastAlt = now;
    }
    drawEnemies(isAlt);
    $("#c").drawLayers();

    switch (offBot){
        case 0:
            difOffY = 0;
        break;
        case 1:
            difOffY = hF * 155 / 6;
        break;
        case 2:
            difOffY = hF * 155 / 3;
        break;
        case 3:
            difOffY = hF * ((155 / 3) + (700 / 24) + 15);
        break;
        case 4:
            difOffY = hF * ((155 / 3) + (700 / 12) + 15);
        break;
    }

    if (enemiesAlive == 0){
        gameOver(true);
    }

    if ((corner.y + (hF * 12.5) - difOffY)  > 450 * hF){
        gameOver(false);
    }
}

$("#c").ready(function(){
    $("#start").click(function(){
    over = false;
    pause = false;
    $("#c").removeLayers();
    $("#start").hide();
    $("#c").attr("height", 500 * hF);
    $("#c").attr("width", 500 * hF);
    var width = 40 * hF;
    var height = width * 79 / 114;
    $("#c").drawImage({
       name: "ship",
       source: "spaceinvaders/images/ship.png",
       x: shipX,
       y: shipY,
       width: width,
       height: height,
       layer: true
    });
    resetEnemies();
    lives = 3;
    drawLives();
    drawScore();
    main();
    });
});

var willShoot = false;
$(document).keydown(function(event){
    var key = event.which;
    switch (key) {
        case 37:
            dir = Direction.LEFT;
        break;

        case 39:
            dir = Direction.RIGHT;
        break;

        case 32:
            willShoot = true;
        break;

        case 27:
            if (pause){ pause = false; }
            else { pause = true; }
    }
});

$(document).keyup(function(event){
    if((event.which == 37 && dir == Direction.LEFT) || (event.which == 39 && dir == Direction.RIGHT)){
        dir = Direction.NONE;
    }
});

var shots = [];
function shoot(x, y){
    shotsFired++;
    var width = 2 * hF;
    var height = width * 400 / 150;
    var name = "shot" + shotsFired;
    shots[shots.length] = name;
    $("#c").drawImage({
        name: name,
        source: "spaceinvaders/images/shot.png",
        x: x,
        y: y - (10 * hF),
        width: width,
        height: height,
        layer: true
    });
}

function testColl(x1, y1, w1, h1, x2, y2, w2, h2){
    if((x1 + w1 / 2) >= (x2 - w2 / 2)
    && (x1 - w1 / 2) <= (x2 + w2 /2)
    && (y1 + h1 / 2) >= (y2 - h2 / 2)
    && (y1 - h1 / 2) <= (y2 + h2 / 2)) {
        return true;
    } else {
        return false;
    }
}

$(w).blur(function(){ pause = true; });
$(w).focus(function(){ pause = false; });

var bombs = [];
var bombsDropped = 0;
function dropBomb(){
    bombsDropped++;

    var alienX = Math.round(Math.random() * 10);
    var k;
    for (k = 0; k < enemies[alienX].length; k++){
        if (enemies[alienX][k].alive){
            thisE = $("#c").getLayer("e" + alienX + k);
            var width = 5 * hF;
            var height = width * 228 / 99;
            var name = "bomb" + bombsDropped;
            bombs[bombs.length] = name;
            $("#c").drawImage({
                name: name,
                source: "spaceinvaders/images/ebomb.png",
                x: thisE.x,
                y: thisE.y,
                width: width,
                height: height,
                layer: true
            });
            break;
        }
    }
}

var lives = 3;

function drawLives(){
    var lifeW = 20 * hF;
    var k;
    for (k = 1; k <= 3; k++){
        var newX = k * (lifeW + 10);
        var height = lifeW * 79 / 114;
        $("#c").drawImage({
            name: "life" + k,
            source: "spaceinvaders/images/life.png",
            x: newX,
            y: 500 * hF - height + 1,
            height: height,
            width: lifeW,
            layer: true
        });
    }
}

function death(){
    lives--;
    var thisLayer = lives + 1;
    $("#c").removeLayer("life" + thisLayer);
    if (lives == 0){
        $("#c").drawLayers();
        pause = true;
        gameOver();
    }
}

var score = 0;

function drawScore(){
    $("#c").drawText({
        name: "score",
        fillStyle: 'white',
        strokeWidth: 2,
        x: 250 * hF, y: (500 - 14) * hF,
        fontSize: 14 * hF,
        fontFamily: 'Verdana, sans-serif',
        text: "Score: " + score,
        layer: true
    });
}

function gameOver(win){
    var text;
    if (win){
        resetEnemies();
    } else {
        text = "GAME OVER";
    $("#c").drawText({
        name: "gameover",
        fillStyle: 'white',
        strokeWidth: 5,
        x: 250 * hF, y: 250 * hF,
        fontSize: 40 * hF,
        fontFamily: 'Verdana, sans-serif',
        text: "GAME OVER",
        layer: true
    })
    $("#c").setLayer("score", {x: 250 * hF, y: 280 * hF});
    $("#c").drawLayers();
    over = true;
    $("#start").show();
    }
}
</script>
<style>
#c {
   // border-style: solid;
   // border-width: 2px;
   // border-color: #000000;
    background-color: #000000;
}
</style>
</head>
<body>
<div id="holder" style="position: absolute; z-index: 0;">
<canvas id="c" width="500" height="500">Your browser does not support this feature.</canvas>
</div>
<p id="start" style="position: absolute; z-index: 1; background-color: #66FF33; color: #ffffff; font-size: 40px; cursor: pointer">Start Game</p>
</body>
</html>
