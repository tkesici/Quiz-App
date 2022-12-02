let $jqDate = $('.date-slashes');
let $jqName = $('.name');

$jqDate.bind('keyup', function(ev) {
    if (ev.which !== 8) {
        let input = $jqDate.val();
        let out = input.replace(/\D/g, '');
        let len = out.length;

        if (len > 1 && len < 4) {
            out = out.substring(0, 2) + '.' + out.substring(2, 3);
        } else if (len >= 4) {
            out = out.substring(0, 2) + '.' + out.substring(2, 4) + '.' + out.substring(4, len);
            out = out.substring(0, 10)
        }
        $jqDate.val(out)
    }
});
