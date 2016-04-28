/**
 * Created by Dominik on 1.3.2016.
 */
$(function(){

    function heads(){
        $.fn.snow();
    }


    function event_handler() {
        var map = {65: false, 82: false, 87: false, 73:false}; // arwi
        var map2 = {70: false, 69: false, 84: false, 65:false, 75:false}; // fetak
        var buzzer = $('#buzzer')[0];
        var lol = $('#lol')[0];
        $("body").keydown(function (e) {
            var char = e.keyCode;
            if (char == 68) {
                window.location.href = "/dont-eat-yellow-snow";
            }
            if(char == 99){
                heads();
            }

            if (char in map) {
                map[char] = true;
                if (map[65] && map[82] && map[87] && map[73]) {
                    buzzer.pause();
                    lol.play();
                    alert("01101000 01101111 01100100 01101111 01101110 01101001 01101110 00100000 01100111 01100001 01101110 01100111 01110011 01110100 01100001 00100000 01101101 01100001 01100100 01100001 01100110 01100001 01101011 01100001 00101110 00101110 00101110 00100000 00100011 01101000 01101001 01100111 01101000 01101010 01100001 01101011 01101111 01110011 01100001 01101101 01110101 01110010 01100001 01111001 00100000 00100011 01100001 01110010 01110111 01101001 00100000 00111010 01000100 00100000 01101100 01101111 01101100 00100000 00111010 01000100");
                    return false;
                }
            }

            if (char in map2) {
                map[char] = true;
                if (map[70] && map[69] && map[84] && map[65] && map[75]) {
                        lol.pause();
                        buzzer.play();
                        alert("Smažko vykurvená :DD");
                        return false;
                }
            }

        }).keyup(function(e) {
            if (map[65] == true && map[82] == true && map[87] == true && map[73] == true) {
                map[65] = false;
                map[82] = false;
                map[87] = false;
                map[73] = false;
            }

            if (map[70] && map[69] && map[84] && map[65] && map[75]) {
                map[70] = false;
                map[69] = false;
                map[84] = false;
                map[65] = false;
                map[75] = false;
            }
        });
    }

    event_handler();

});