$(document).ready(function() {
    $('body').on('click', '.calculate_winners', function() {
        var match_id = $(this).attr('id').substring(18);
        var data = {
            match_id: match_id
        };


        $.ajax({
            type: 'POST',
            data: data,
            url: '/matches/ajaxDetermineWinners',
            success: function(o) {
                var obj = jQuery.parseJSON(o);
                if(obj.response_status == 'ERROR') {
                    if (typeof obj.reason != 'undefined') {
                        alert('Failed to save: ' + obj.reason);
                    } else {
                        alert('Failed to save');
                    }
                }
                else {
                    var match_id = obj.data.match_id;
                    $('#row-' + match_id + ' .winners_col').html(obj.data.winners);
                }
            }
        });
    });
});