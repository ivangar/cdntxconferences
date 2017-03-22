$(function () {

        $.ajax({
            url: "/php/fellows_pwd.php",
            cache: false,
            type: "POST",
            dataType: "html"
        }) 
        .done(function( data ) {
              if (data === "restrict"){
                document.location.href = "http://www.cdntxconferences.com/2016.html";
              }
        });

});	