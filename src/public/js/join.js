const images = [
    "/images/IMG_0639.png",
    "/images/IMG_0638.png"
];


function popCharacter(){


    const area =
    document.getElementById("pop-area");


    const img =
    document.createElement("img");


    img.src =
    images[
        Math.floor(Math.random()*images.length)
    ];


    img.className="pop-character";



    // 左右へ飛ぶ距離
    const x =
    (Math.random()*800-400)+"px";


    // 中間位置
    const x2 =
    (Math.random()*1000-500)+"px";


    // 最後に左右へ落下
    const x3 =
    (Math.random()*1400-700)+"px";



    img.style.setProperty("--x",x);
    img.style.setProperty("--x2",x2);
    img.style.setProperty("--x3",x3);



    // 大きさランダム
    const size =
    70 + Math.random()*100;


    img.style.width=size+"px";



    // 発射位置
    img.style.left =
    (40 + Math.random()*20)+"%";



    area.appendChild(img);



    setTimeout(()=>{

        img.remove();

    },2600);


}



// 大量発射
setInterval(()=>{


    // 1回で複数個
    for(let i=0;i<3;i++){

        popCharacter();

    }


},250);
