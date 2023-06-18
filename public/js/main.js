function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function fixDate(val) {
    var fixDate = new Date(val);
    $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var result = fixDate.getDate() + ' ' + $bulan[fixDate.getMonth()] + ' ' + fixDate.getFullYear() + ' ' + fixDate.getHours() + ':' + fixDate.getMinutes();

    return result;
}