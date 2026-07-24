document.addEventListener("DOMContentLoaded", () => {

    const chars = document.querySelectorAll(".rolling");

    console.log(chars.length);

    chars.forEach(char => {

        char.addEventListener("click", function () {

            this.style.display = "none";

        });

    });

});
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".rolling").forEach(char => {

        char.onclick = function(){

            this.style.display = "none";

        };

    });

});
