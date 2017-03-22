$(function () {

        $.ajax({
            url: "/php/forum_pwd.php",
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