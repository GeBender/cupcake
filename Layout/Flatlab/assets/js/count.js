$(document).ready(function() {
    $('.count-up').show(function() {

        countUp($(this).html(), $(this));
    })
});
function countUp(count, display)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = display,
        run_count = 1,
        fracao = 0,
        int_speed = 24;

        if (speed == 0) {
            speed = 1;
        }

        fracao = (count - parseInt(count));
        count = (count - fracao);

        $display.text(0);
        $display.css('visibility', 'visible');

    var int = setInterval(function() {
        if((speed * run_count) <= count){
            $display.text(speed * run_count);

            if ((speed * run_count) == count) {
                $display.text(FormatCurrency(count + fracao));
            }
            run_count++;
        }
    }, int_speed);

}


function FormatCurrency (amount)
{
    return amount.toLocaleString('pt-BR', { style: 'decimal', currency: 'USD' });
}
