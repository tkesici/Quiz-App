let $jqDate = $('.date-slashes');

$jqDate.bind('keyup', function (ev) {
    if (ev.which !== 8) {
        let input = $jqDate.val();
        let out = input.replace(/\D/g, '');
        let len = out.length;

        if (len > 1 && len < 4) {
            out = out.substring(0, 2) + '/' + out.substring(2, 3);
        } else if (len >= 4) {
            out = out.substring(0, 2) + '/' + out.substring(2, 4) + '/' + out.substring(4, len);
            out = out.substring(0, 10)
        }
        $jqDate.val(out)
    }
});

$('#ad').keyup(function () {
    this.value = this.value.charAt(0).toLocaleUpperCase('tr-TR') +
        this.value.slice(1).toLocaleLowerCase('tr-TR');
});

$('#soyad').keyup(function () {

    this.value = this.value.toLocaleUpperCase('tr-TR');

});

$('#tckn').keyup(function (e) {
    if (/\D/g.test(this.value)) {
        // Filter non-digits from input value.
        this.value = this.value.replace(/\D/g, '');
    }
});

$('#tel').keyup(function (e) {
    if (this.value == 0) {
        this.value = "";
    } else {
        if (/\D/g.test(this.value)) {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    }
});

$('#dene').on('keyup', function (e) {
    var txt = $(this).val();
    txt = txt.charAt(0).toLocaleUpperCase('tr-TR') +
        txt.slice(1).toLocaleLowerCase('tr-TR');
    $(this).val(txt.replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toLocaleUpperCase('tr-TR');

    }));
});

