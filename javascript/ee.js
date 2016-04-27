/**
 * Created by Dominik on 1.3.2016.
 */
$(function(){

    function heads(){
        $.fn.snow();
    }


    function event_handler() {
        $("body").keydown(function (e) {
            var char = e.keyCode;
            if (char == 68) {
                window.location.href = "/dont-eat-yellow-snow";
            }
            if(char == 99){
                heads();
            }
        });
    }

    event_handler();

});