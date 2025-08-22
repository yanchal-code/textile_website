$(document).ready(function () {
    function displayImagePreview(file, previewElement) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // Updateing the src attribute
            previewElement.attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    }
    var updImg = $('#updimgPreview1');
    var updImg2 = $('#updimgPreview2');
    var updImg3 = $('#updimgPreview3');
    var updImg4 = $('#updimgPreview4');
    if (updImg) {
        updImg.hide();
        $('#updimgPreview1Span').hide()
        $('#updimage1').change(function () {
            $('#updimgPreview1Span').show();
            displayImagePreview($(this)[0].files[0], $('#updimgPreview1'));
        });
    }
    if (updImg2) {
        updImg2.hide();
        $('#updimgPreview2Span').hide()
        $('#updimage2').change(function () {
            $('#updimgPreview2Span').show();
            displayImagePreview($(this)[0].files[0], $('#updimgPreview2'));
        });
    }
    if (updImg3) {
        updImg.hide();
        $('#updimgPreview3Span').hide()
        $('#updimage3').change(function () {
            $('#updimgPreview3Span').show();
            displayImagePreview($(this)[0].files[0], $('#updimgPreview3'));
        });
    }
    if (updImg4) {
        updImg.hide();
        $('#updimgPreview4Span').hide()
        $('#updimage4').change(function () {
            $('#updimgPreview4Span').show();
            displayImagePreview($(this)[0].files[0], $('#updimgPreview4'));
        });
    }

});