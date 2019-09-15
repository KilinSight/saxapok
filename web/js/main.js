PIXI.utils.sayHello('test');

const app = new PIXI.Application({
    width: 1366, height: 640, backgroundColor: 0x1099bb, resolution: window.devicePixelRatio || 1,
});
document.body.appendChild(app.view);

const container = new PIXI.Container();
const rootpath = '../../../../web/';
app.stage.addChild(container);

//
const background = PIXI.Texture.from(rootpath + 'img/background.jpg');
let bird;
let lizard;
let snake;
let plant;
let spider;
initialize();

function randomInteger(min, max) {
    // случайное число от min до max
    let rand = min + Math.random() * (max - min);
    return Math.floor(rand);
}
function drawBorder(object){
    const graphics = new PIXI.Graphics();
    graphics.lineStyle(4, 0xFF0000, 1);
    // graphics.beginFill(0xDE3249);
    graphics.drawRect(object.x-(object.width/2), object.y-(object.height/2), object.width, object.height);
    graphics.endFill();
    container.addChild(graphics);
}

function getRandomCoordinates(object, property) {
    let randInt = randomInteger(-(app.screen[property]/2), app.screen[property]/2);
    return randInt>0?randInt - object[property]:randInt+object[property];
}

$(document).on('click', () => {
    const mouseposition = app.renderer.plugins.interaction.mouse.global;
    console.log('X, Y');
    console.log(Math.round(mouseposition.x) + ', ' + Math.round(mouseposition.y));
})

function onAssetsLoaded(){
    $.each(getAllMonsters(), (index, item) => {
        let texture = item.texture;
        let coordinates = item.coordinates[randomInteger(0, item.coordinates.length)];
        let sprite = new PIXI.Sprite(texture);
        let randX = coordinates[0]-container.width/2;
        let randY =  coordinates[1]-container.height/2;
        sprite.scale.x = sprite.scale.y = coordinates[2];


        sprite.anchor.set(0.5);
        sprite.position.set(randX, randY);


        container.addChild(sprite);
        drawBorder(sprite);
    });
}

function getAllMonsters() {
    return [
        {
            name: 'bird',
            texture:  PIXI.Texture.from('bird'),
            coordinates: [
                [951, 140, 0.5],
                [1104, 133, 0.6],
                [1230, 137, 0.8],
            ]
        },
        {
            name: 'lizard',
            texture:  PIXI.Texture.from('lizard'),
            coordinates: [
                [719, 321, 0.4],
                [792, 372, 0.6],
                [684, 459, 0.8],
            ]
        },
        {
            name: 'snake',
            texture:  PIXI.Texture.from('snake'),
            coordinates: [
                [1121, 319, 0.5],
                [1046, 285, 0.3],
            ]
        },
        {
            name: 'plant',
            texture:  PIXI.Texture.from('plant'),
            coordinates: [
                [358, 316, 0.4],
                [361, 385, 0.6],
                [350, 424, 0.8],
            ]
        },
        {
            name: 'spider',
            texture:  PIXI.Texture.from('spider'),
            coordinates: [
                [588, 254, 0.2],
            ]
        },
    ];
}

function initialize() {
    const backgroundSprite = new PIXI.Sprite(background);


    // Move container to the center
    container.x = app.screen.width / 2;
    container.y = app.screen.height / 2;

    backgroundSprite.anchor.set(0.5);
    backgroundSprite.x = 0;
    backgroundSprite.y = 0;
    container.addChild(backgroundSprite);

    app.loader
        .add('bird', rootpath + 'img/Bird_4.png')
        .add('lizard', rootpath + 'img/Lizard_1.png')
        .add('snake', rootpath + 'img/Snake_2.png')
        .add('plant', rootpath + 'img/Plant.png')
        .add('spider', rootpath + 'img/Spider_1.png')
        .load(onAssetsLoaded);

}