function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function fixDate(val) {
    var fixDate = new Date(val);
    $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var result = fixDate.getDate() + ' ' + $bulan[fixDate.getMonth()] + ' ' + fixDate.getFullYear() + ' ' + fixDate.getHours() + ':' + fixDate.getMinutes();

    return result;
}

function fixDateOnly(val){
    var fixDate = new Date(val);
    $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var result = fixDate.getDate() + ' ' + $bulan[fixDate.getMonth()] + ' ' + fixDate.getFullYear();

    return result;
}

function fixPrice(val){
    var remainder = val.toString().length % 3;
    var newRemainder = (val.toString().length - remainder) / 3;

    var result = 'Rp. ';
    var counter = 0;
    if(remainder > 0){
        result += val.toString().substring(counter, counter+remainder) + '.';
        counter += remainder;
    }
    for(var i=1; i <= newRemainder;i++){
        result += val.toString().substring(counter, counter+3);
        if(i != newRemainder)
            result += '.';
        counter += 3;
    }
    // result += newRemainder;

    return result;
}