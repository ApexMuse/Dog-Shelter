var text_max = 300;
$('#textarea_feedback').html(text_max + ' characters remaining');

$('#dogDescriptionText').keyup(function() {
    var text_length = $('#dogDescriptionText').val().length;
    var text_remaining = text_max - text_length;
    $('#textarea_feedback').html(text_remaining + ' characters remaining');
});

