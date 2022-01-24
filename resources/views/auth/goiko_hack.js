
// ====== Imports ======

import OnirixSDK from "https://sdk.onirix.com/0.3.0/ox-sdk.esm.js";
import * as THREE from "./dependencies/three.module.js";
import { Kitchen,mixer } from "./components/kitchen.js";
import { Burger, burgerMixer } from "./components/burger.js";
import { Rapper, rapperMixer } from "./components/rapper.js";
import { Gamer, gamerMixer } from "./components/gamer.js";
import { Muppie, muppieMixer } from "./components/muppie.js";
import { Skater, skaterMixer } from "./components/skater.js";
import { Galleta, galletaMixer } from "./components/biscuit.js";
import { Bonus } from "./components/bonus.js";
import { TV, tvMixer} from "./components/tv.js";
import { Virus, virusMixer } from "./components/virus.js";
import { Year, yearMixer } from "./components/year.js";
import { Snowman , snowmanMixer } from "./components/snowman.js";
import { Game, SpriteLoad } from "./game.js";
import {boxBurger,boxRapper,boxMuppie,boxGamer,boxSkater,boxSmash,boxBiscuit,boxTV,boxVirus,boxYear,boxSnow,helperSnow,galletaHitBox,helperBurger, helperSmash } from "./hitboxes.js";


//FIREBASE
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
import { getAnalytics, logEvent } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-analytics.js";
import { getFirestore, collection, getDocs, setDoc, doc, addDoc, query, where, getDoc, orderBy, limit } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js";




/*
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCe9oaBSiR25sm8mWRBUsmh8pQlXTp2X6g",
  authDomain: "goikodb-f038e.firebaseapp.com",
  databaseURL: "https://goikodb-f038e-default-rtdb.europe-west1.firebasedatabase.app",
  projectId: "goikodb-f038e",
  storageBucket: "goikodb-f038e.appspot.com",
  messagingSenderId: "1011259818207",
  appId: "1:1011259818207:web:d4e8f811654bdf44b7a96a"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);



*/


const firebaseConfig = {
    apiKey: "AIzaSyD9H6z8jlC1ftONbDKUTAZYayN3LBjkLGg",
    authDomain: "goikosmash.firebaseapp.com",
    projectId: "goikosmash",
    storageBucket: "goikosmash.appspot.com",
    messagingSenderId: "900506773008",
    appId: "1:900506773008:web:4c450a9fdb84432de60c95",
    measurementId: "G-N41XS5KNTB"
  };

/*
const firebaseConfig = {
    apiKey: "AIzaSyCe9oaBSiR25sm8mWRBUsmh8pQlXTp2X6g",
    authDomain: "goikodb-f038e.firebaseapp.com",
    databaseURL: "https://goikodb-f038e-default-rtdb.europe-west1.firebasedatabase.app",
    projectId: "goikodb-f038e",
    storageBucket: "goikodb-f038e.appspot.com",
    messagingSenderId: "1011259818207",
    appId: "1:1011259818207:web:d4e8f811654bdf44b7a96a"
  };
*/
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const db = getFirestore(app);
// ====== ThreeJS ======

var renderer, scene, camera, delta;
var loaded = false;
var progress;
var started = false;

//UI ELEMENTS

var loadingScreen = document.querySelector(".loading_screen");
var loadBar = document.querySelector(".loadbar");
var ui = document.querySelector(".ui");
var homeScreen = document.querySelector(".home_screen");
var inGameUI = document.querySelector(".in_game");
var UIStart = document.querySelector('.start_button_home');
var inGameStart = document.querySelector("#in_game_start")
var gameOver = document.querySelector(".game_over");
var smashButton =  document.querySelector(".smash");
var scoreOver = document.querySelector("#score_display");
var howToButton = document.querySelector("#howto");
var prizesButton = document.querySelector("#prizes");
var howToScreen = document.querySelector(".howto_screen");
var prizesScreen = document.querySelector(".prizes_screen");
var legalButton = document.querySelector("#legal");
var legalScreen = document.querySelector(".legal_screen");
var cookiesButton = document.querySelector("#cookies");
var cookiesScreen = document.querySelector(".cookies_screen");
var underStood = document.querySelector("#understood");
var underStoodPrizes = document.querySelector("#understood_prizes");
var underStoodLegal = document.querySelector("#understood_legal");
var underStoodCookies = document.querySelector("#understood_cookies");
var orientationScreen = document.querySelector("#orientation");
var goButton = document.querySelector(".go_button_home");
var lostScreen = document.querySelector(".lost");
var trackingScreen = document.querySelector(".tracking");
var rankingButton = document.querySelector("#ranking");
var rankingScreen = document.querySelector(".ranking_screen");
var underStoodRanking = document.querySelector("#understood_ranking");
var top100  = document.querySelector(".top_100>ul");
var scoreAnnouncer = document.querySelector("#score_announcer");
var loader = document.querySelector("#loader");
var buttonPlayAgain = document.querySelector("#play_again");
var remiderText = document.querySelector("#reminder");
var displayPosition = document.querySelector("#display_position")

//====================================================

var form = document.querySelector("#form");
var formAlias = document.querySelector("#alias");
var formName = document.querySelector("#name");
var formApellidos = document.querySelector("#apellidos");
var formMail = document.querySelector("#mail");
var formSubmit = document.querySelector("#submit");

//====================================================
//UI ELEMENTS

const manager = new THREE.LoadingManager();
    manager.onStart = function ( url, itemsLoaded, itemsTotal ) {
    ui.style.display = 'none';
    inGameUI.style.display = 'none';
    getRanking();
	console.log( 'Started loading file: ' + url + '.\nLoaded ' + itemsLoaded + ' of ' + itemsTotal + ' files.' );
    };
    manager.onLoad = function ( ) {
	console.log( 'Loading complete!');
    loaded = true;
    loadingScreen.style.display = 'none';
    ui.style.display = 'flex';
    ui.style="z-index:1";
    logEvent(analytics,"page_view");
    };
    manager.onProgress = function ( url, itemsLoaded, itemsTotal ) {
    var percent = itemsLoaded/itemsTotal;
    progress = percent*100
    console.log(progress + ' %');
    loadingScreen.style.display = 'flex';
    loadBar.style.width = progress + '%'; 
    
    };
    manager.onError = function ( url ) {
	console.log( 'There was an error loading ' + url );
    };

    const kitchen = new Kitchen(manager);
    const burger = new Burger(manager);
    const rapper = new Rapper(manager);
    const gamer = new Gamer(manager);
    const muppie = new Muppie(manager);
    const skater = new Skater(manager);
    const bonus = new Bonus(manager);
    const galleta = new Galleta(manager);
    const tv = new TV(manager);
    const virus = new Virus(manager);
    const year = new Year(manager);
    const snowman = new Snowman(manager);
    const game = new Game(burger,rapper,gamer,muppie,skater,galleta,tv,virus,year,snowman,manager);

    burger.visible = false;
    rapper.visible = false;
    gamer.visible = false;
    muppie.visible = false;
    skater.visible = false;
    galleta.visible = false;
    tv.visible = false;
    virus.visible = false;
    year.visible = false;
    snowman.visible = false;
    
function setupRenderer(rendererCanvas) {
    
    const width = rendererCanvas.width;
    const height = rendererCanvas.height;
    
    // Initialize renderer with rendererCanvas provided by Onirix SDK
    renderer = new THREE.WebGLRenderer({ canvas: rendererCanvas, alpha: true, antialiasing:false });
    renderer.setClearColor(0x000000, 0);
    renderer.setSize(width, height);
    
    // Ask Onirix SDK for camera parameters to create a 3D camera that fits with the AR projection.
    const cameraParams = OX.getCameraParameters();
    camera = new THREE.PerspectiveCamera(cameraParams.fov, cameraParams.aspect, 1, 1000);
    camera.matrixAutoUpdate = false;
    
    // Create an empty scene
    scene = new THREE.Scene();
    
    // Add some lights
    const ambientLight = new THREE.AmbientLight(0xcccccc, 0.4);
    scene.add(ambientLight);
    const hemisphereLight = new THREE.HemisphereLight(0xbbbbff, 0x444422);
    scene.add(hemisphereLight);

}

function updatePose(pose) {

    // When a new pose is detected, update the 3D camera
    let modelViewMatrix = new THREE.Matrix4();
    modelViewMatrix = modelViewMatrix.fromArray(pose);
    camera.matrix = modelViewMatrix;
    camera.matrixWorldNeedsUpdate = true;

}

function onResize() {

    // When device orientation changes, it is required to update camera params.
    const width = renderer.domElement.width;
    const height = renderer.domElement.height;
    const cameraParams = OX.getCameraParameters();
    camera.fov = cameraParams.fov;
    camera.aspect = cameraParams.aspect;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);

}

function render() {
    //document.getElementById("loading-screen").style.display = 'none';
    // Just render the scene
    renderer.render(scene, camera);
}

function collisionUpdater(kitchen,burger,rapper,muppie,gamer,skater,biscuit,tv,virus,year,snowman){
    if(!boxSmash.intersectsBox(boxBurger) && !boxSmash.intersectsBox(boxRapper) && 
    !boxSmash.intersectsBox(boxMuppie) &&  !boxSmash.intersectsBox(boxGamer) &&
    !boxSmash.intersectsBox(boxSkater) && !boxSmash.intersectsBox(boxBiscuit) && 
    !boxSmash.intersectsBox(boxTV) && !boxSmash.intersectsBox(boxVirus) && 
    !boxSmash.intersectsBox(boxYear) && !boxSmash.intersectsBox(boxSnow)){
        kitchen.isIronEmpty = true;
        burger.inIron = false;
        rapper.inIron = false;
        muppie.inIron = false;
        gamer.inIron = false;
        skater.inIron = false;
        biscuit.inIron = false;
        tv.inIron = false;
        virus.inIron = false;
        year.inIron = false;
    }else{
        if(boxSmash.intersectsBox(boxBurger)){
            burger.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxRapper)){
            rapper.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxMuppie)){
            muppie.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxGamer)){
            gamer.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxSkater)){
            skater.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxBiscuit)){
            biscuit.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxTV)){
            tv.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxVirus)){
            virus.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxYear)){
            year.inIron = true;
            kitchen.isIronEmpty = false;
        }
        else if(boxSmash.intersectsBox(boxSnow)){
            snowman.inIron = true;
            kitchen.isIronEmpty = false;
        }
    }
}   

const sprite = new SpriteLoad(manager,'./ui/smash.png');
sprite.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite.sprite.scale.set(1.5*.2,1.5*.2);

const sprite1 = new SpriteLoad(manager,'./ui/chorreo.png');
sprite1.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite1.sprite.scale.set(1.5*.2,1.5*.2);

const sprite2 = new SpriteLoad(manager,'./ui/crack.png');
sprite2.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite2.sprite.scale.set(1.5*.2,1.5*.2);

const sprite3 = new SpriteLoad(manager,'./ui/fuckyeah.png');
sprite3.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite3.sprite.scale.set(1.5*.2,1.5*.2);

const sprite4 = new SpriteLoad(manager,'./ui/ouch.png');
sprite4.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite4.sprite.scale.set(1.5*.2,1.5*.2);

const sprite5 = new SpriteLoad(manager,'./ui/top.png');
sprite5.sprite.position.set(0.2*.2,2*.2,4*.2);
sprite5.sprite.scale.set(1.5*.2,1.5*.2);

const spriteFail = new SpriteLoad(manager,'./ui/fail.png');
spriteFail.sprite.position.set(0.2*.2,2*.2,4*.2);
spriteFail.sprite.scale.set(1.5*.2,1.5*.2);


function randomSprite(){
    var rnd = Math.floor(Math.random()*6);
    switch(rnd){
        case 0:
            sprite.animate();
            break;
        case 1:
            sprite1.animate();
            break;
        case 2:
            sprite2.animate();
            break;
        case 3:
            sprite3.animate();
            break;
        case 4:
            sprite4.animate();
            break;
        case 5:
            sprite5.animate();
            break;
    }
}

var clock = new THREE.Clock();
var id;
function renderLoop() {

    delta = clock.getDelta();
    render();  
    document.getElementById("score").innerText = 192160;
    document.getElementById("lifes").innerHTML = game.lifes.join('');

    galletaHitBox.position.x = galleta.position.x;
    galletaHitBox.position.y = galleta.position.y;
    galletaHitBox.position.z = galleta.position.z;

    if(game.xmasActive){
        scene.add(bonus)
    }else{
        scene.remove(bonus)
    }

    mixer !== null ? mixer.update(delta*2.25) : null;
    burgerMixer !== null ? burgerMixer.update(delta) : null;
    rapperMixer !== null ? rapperMixer.update(delta*1.3) : null;
    muppieMixer !== null ? muppieMixer.update(delta) : null;
    gamerMixer !== null ? gamerMixer.update(delta) : null;
    skaterMixer !== null ? skaterMixer.update(delta) : null;
    galletaMixer !== null ? galletaMixer.update(delta) : null;
    tvMixer !== null ? tvMixer.update(delta) : null;
    virusMixer !== null ? virusMixer.update(delta) : null;
    yearMixer !== null ? yearMixer.update(delta) : null;
    snowmanMixer !== null ? snowmanMixer.update(delta) : null;

    boxBurger.setFromObject(burger);
    boxRapper.setFromObject(rapper);
    boxMuppie.setFromObject(muppie);
    boxGamer.setFromObject(gamer);
    boxSkater.setFromObject(skater);
    boxBiscuit.setFromObject(galletaHitBox);
    boxTV.setFromObject(tv);
    boxVirus.setFromObject(virus);
    boxYear.setFromObject(year);
    boxSnow.setFromObject(snowman)

    collisionUpdater(kitchen,burger,rapper,muppie,gamer,skater,galleta,tv,virus,year,snowman,helperSnow);
    
    id = requestAnimationFrame(() => renderLoop());  
}

smashButton.addEventListener('click', ()=>{
    smashButton.classList.add("smashed");
    setTimeout(()=>{smashButton.classList.remove("smashed");},200)
    if(kitchen){
    kitchen.play()
    }
    if(kitchen.isIronEmpty && !game.xmasActive){
        game.lifes.pop(); 
        spriteFail.animate();
        game.checkLifes();
    }else{
        if(burger.inIron){
            randomSprite();
            game.score+=burger.points;
            burger.setSmash();
            burger.smashed=true;
        }
        else if(rapper.inIron){
            randomSprite();
            game.score+=rapper.points;
            rapper.setSmash();
            rapper.smashed=true;
        }
        else if(muppie.inIron){
            randomSprite();
            game.score+=muppie.points;
            muppie.setSmash();
            muppie.smashed = true;
        }
        else if(gamer.inIron){
            randomSprite();
            game.score+=gamer.points;
            gamer.setSmash();
            gamer.smashed=true;
        }
        else if(skater.inIron){
            randomSprite();
            game.score+=skater.points;
            skater.setSmash();
            skater.smashed=true;
        }
        else if(galleta.inIron){
            randomSprite();
            game.score+=20;
            galleta.setSmash();
            galleta.smashed=true;
        }
        else if(tv.inIron){
            randomSprite();
            game.score+=20;
            tv.setSmash();
            tv.smashed=true;
        }
        else if(virus.inIron){
            randomSprite();
            game.score+=20;
            virus.setSmash();
            virus.smashed=true;
        }
        else if(year.inIron){
            randomSprite();
            game.score+=20;
            year.setSmash();
            year.smashed=true;
        }
        else if(snowman.inIron){
            randomSprite();
            game.score+=20;
            snowman.setSmash();
            snowman.smashed=true;
        }
    }
    if(game.over){
        game.gameOverSfx();
        game.stop_ambient_music();
        game.destroy();
        gameOver.style = 'display:flex';
        scoreOver.innerText = 192160;
    }
})

let OX = new OnirixSDK("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjcwOTAsInByb2plY3RJZCI6MjE0MTgsInJvbGUiOjMsImlhdCI6MTYzOTA4NDUyMH0.k34puOloHFUF5gLbQv8uCC8IMJzIvegZfxoMMFjUVBY");

let config = {
    mode: OnirixSDK.TrackingMode.Image
}

function loadOnirix(){
OX.init(config).then(rendererCanvas => {

    // Setup ThreeJS renderer
    setupRenderer(rendererCanvas);

    
    
    renderLoop();
    OX.subscribe(OnirixSDK.Events.OnDetected, function (id) {
        scene.visible = true;
        console.log("Detected Image: " + id);
        lostScreen.style.display = 'none';
        trackingScreen.style.display = 'none';
        if(loaded){
            scene.add(kitchen,burger,rapper,gamer,muppie,skater,bonus,sprite.sprite,spriteFail.sprite,sprite1.sprite,sprite2.sprite,sprite3.sprite,sprite4.sprite,sprite5.sprite,galleta,tv,virus,year,snowman);
        }else{

        }
        if(!started){
            inGameStart.style = 'display:block';
            smashButton.style.display = 'none';
        }else{
            smashButton.style.display = 'block'; 
        }
        
        scene.background = new THREE.VideoTexture(OX.getCameraFeed());
    });

    OX.subscribe(OnirixSDK.Events.OnPose, function (pose) {
        updatePose(pose);
    });

    OX.subscribe(OnirixSDK.Events.OnLost, function (id) {
        console.log("Lost Image: " + id);

        if(!game.over){
            smashButton.style.display = 'none';
            lostScreen.style.display = 'flex';
        }
        if(!started){
            lostScreen.style.display = 'none';
            trackingScreen.style.display = 'flex';
            inGameStart.style = 'display:none';
        }
        scene.visible = false;
        scene.background = null;
    });

    OX.subscribe(OnirixSDK.Events.OnResize, function () {
        onResize();
    });

}).catch((error) => {




    switch (error.name) {

        case 'INTERNAL_ERROR':
           
            break;

        case 'CAMERA_ERROR':
            
            break;

        case 'SENSORS_ERROR':
            
            break;

        case 'LICENSE_ERROR':
            
            break;

    }

    document.getElementById("error-screen").style.display = 'flex';

});
}



var mailExist;
var position = 0;

async function getRanking(){
    const highScore = await collection(db, "score");
    const q = query(highScore, orderBy("score","desc"), limit(100));
    const snap = await getDocs(q);
    snap.forEach((doc) => {
        top100.innerHTML += `<li><span class="pos_number">${position+=1}.</span> <span class="pos_alias">${doc.data().alias}</span><span class="pos_score"> - ${doc.data().score}</span></li>`
    });
    
}

var position;
async function checkPosition(email){
    var count = 0;
    const highScore = await collection(db, "score");
    const q = query(highScore, orderBy("score","desc"), limit(100));
    const snap = await getDocs(q);
    snap.forEach((doc)=>{
        count+=1;
        if(email == doc.id){
            position = count;
            console.log('la posicion es: ',position);
        }
    })
}

async function setScore(mail,alias,name,apellidos){
    const docRef = doc(db, "score", mail);
    const docSnap = await getDoc(docRef);
    if (docSnap.exists()) {
        await setDoc(doc(db,'score', mail),{
            alias: await docSnap.data().alias,
            nombre: await docSnap.data().nombre,
            apellidos: await docSnap.data().apellidos,
            score: 192160
        });
    
        mailExist = true;
        console.log("SI EXISTE");
    } else {
        await setDoc(doc(db,'score', mail),{
            alias: alias,
            nombre: name,
            apellidos: apellidos,
            score: 192160
        })
        mailExist = false;
    console.log("NO EXISTE");
    }
}


function validMail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
  {
    return (true)
  }
    return (false)
}


form.addEventListener("submit", async (e)=>{

    e.preventDefault();
    const alias = formAlias.value;
    const name = formName.value;
    const apellidos = formApellidos.value;
    const mail = formMail.value;
    console.log(validMail(mail))
    console.log(formAlias.value.length);
    console.log(formName.value.length);
    console.log(formApellidos.value.length);

    if(validMail(mail) && formAlias.value.length === 3 && formName.value.length > 0 && formApellidos.value.length > 0){
        form.style.display = 'none';
        loader.style.display = 'block';
        remiderText.style.display = 'none';
        scoreAnnouncer.innerText = 'UN MOMENTO...';
        await setScore(formMail.value,alias,name,apellidos);
        await checkPosition(formMail.value);
        logEvent(analytics,"score_submit");
        setTimeout(() => {
            scoreAnnouncer.style = 'text-align:center';
            scoreAnnouncer.innerHTML = `PUNTUACIÓN ENVIADA! <br> ESTÁS ENTRE LOS 100 MEJORES!!`;
            displayPosition.innerHTML = `<span id="position_sent">${position}.</span><span id="alias_sent">${alias}</span>`
            loader.style.display = 'none';
        }, 800);
        mailExist ? console.log('actualizable') : console.log('creado');  
    }else{
        alert("Comprueba que todos los campos están correctamente rellenados.")
    }

    
});

UIStart.addEventListener("click", ()=>{
    loadOnirix();
    console.log('homestart pressed');
    orientationScreen.style.display = 'flex';
});

goButton.addEventListener("click", ()=>{
    orientationScreen.style.display = 'none';
    trackingScreen.style.display = 'flex';
    homeScreen.style = 'display:none;'
    inGameUI.style.display = 'flex';
});


inGameStart.addEventListener("click", ()=>{
    inGameStart.style = 'display:none';
    smashButton.style = 'display:block';
    trackingScreen.style.display = 'none';
    game.level1();
    game.ambient_music();
    started = true;
    logEvent(analytics,"game_started");

});

howToButton.addEventListener('click', ()=>{
    howToScreen.style.display = 'flex';
});

rankingButton.addEventListener('click',()=>{
    rankingScreen.style.display = 'flex';
});

underStoodRanking.addEventListener('click',()=>{
    rankingScreen.style.display = 'none';
});

buttonPlayAgain.addEventListener('click', ()=>{
    location.reload();
})

prizesButton.addEventListener('click', ()=>{
    prizesScreen.style.display = 'flex';
});

legalButton.addEventListener('click', ()=>{
    legalScreen.style.display = 'flex';
});

cookiesButton.addEventListener('click', ()=>{
    cookiesScreen.style.display = 'flex';
});

underStood.addEventListener('click', ()=>{
    howToScreen.style.display = 'none';
})

underStoodPrizes.addEventListener('click', ()=>{
    prizesScreen.style.display = 'none';
})

underStoodLegal.addEventListener('click', ()=>{
    legalScreen.style.display = 'none';
})

underStoodCookies.addEventListener('click', ()=>{
    cookiesScreen.style.display = 'none';
})
