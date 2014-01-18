$(function() { //shorthand document.ready function
    $('.apiform').on('submit', function(e) { //use on if jQuery 1.7+
        e.preventDefault();  //prevent form from submitting
        var data = $(this).serializeArray();
        var submitthing = $(this).children(':input[name=method]').val();
        //submitthing = submitthing.substring(1, submitthing.length)
         $.ajax({
            type: "POST",
            url: "submit.php?leht=pildid",
            data:data,
            cache: false,
            success: function(data, status, jqxhr){
            	console.log('results');
                console.log(submitthing);
                var header = '#' + submitthing + '-response-header';
                console.log(header);

                $('#' + submitthing + '-response-header').show();
                $('#' + submitthing + '-response-header').html(data.response.headers_raw);

                $('#' + submitthing + '-response-body').show();
                $('#' + submitthing + '-response-body').html(data.response.body_raw);
                console.log(data);
            }
        });
    });
});