$(function() { //shorthand document.ready function
    $('.apiform').on('submit', function(e) { //use on if jQuery 1.7+
        e.preventDefault();  //prevent form from submitting
        var data = $(this).serializeArray();
        console.log(data);
        console.log(data.method);
         $.ajax({
            type: "POST",
            url: "submit.php?leht=pildid",
            data:data,
            cache: false,
            success: function(data, status, jqxhr){
                console.log(data);
            }
        });
    });
});