$(function () {

    var page = '';
    var password = $( "#pwd" );
    var tips = $( ".validateTips" );

    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-error" );
    }

    function processPwd() {

        var form = document.getElementById("pwd-access");
        var jsonData = {};

        for (i = 0; i < form.length ;i++) { 
            var columnName = form.elements[i].name;
            jsonData[columnName] = form.elements[i].value;
        } 

        jsonData['page'] = page;

        $.ajax({
            url: "php/process_pwd.php",
            cache: false,
            type: "POST",
            dataType: "json",
            data: jsonData
        }) 
        .done(function( data ) {

              if (data["access"] === "correct"){
                 document.location.href = "http://www.cdntxconferences.com/subpages/" + data["section"] + "/" + page + ".html";
              }

              else{
                password.addClass( "ui-state-error" );
                updateTips( "Password incorrect" );
              }
              
        });

    }

    $( "#password-dialog-form" ).dialog({
         autoOpen: false,
         height: 350,
         width: 450,
         modal: true,
         buttons: {
           "Submit": function() {
              processPwd();
           },
           "Cancel": function() {
             tips.removeClass( "ui-state-error" );
             tips.empty();
             password.removeClass( "ui-state-error" );
             password.val("");
             $( this ).dialog( "close" );
           }
         },
          close: function() {
             tips.removeClass( "ui-state-error" );
             tips.empty();
             password.removeClass( "ui-state-error" );
             password.val("");
          }
    });

    $( "#restrict-dialog-form" ).dialog({
         autoOpen: false,
         height: 140,
         width: 460,
         modal: true,
    });

    //Use body to handle the event for dynamic content
    $('body').on('click', 'a.pop-up', function(event) {
        event.preventDefault();
        page = $(this).attr( "title" );
        var cookie_data = {};
        cookie_data['cookie_page'] = page;

        $.ajax({
            url: "php/process_pwd.php",
            cache: false,
            type: "POST",
            dataType: "json",  //CHANGE TO BE JSON
            data: cookie_data
        }) 
        .done(function( data ) {
              if (data["access"] === "access"){
                 document.location.href = "http://www.cdntxconferences.com/subpages/" + data["section"] + "/" + page + ".html";
              }

              else{ $( "#password-dialog-form" ).dialog( "open" ); }

        });
    });

    $('form#pwd-access').each(function() {
        
        $(this).find('input').keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
                e.preventDefault();
                processPwd();
            }
        });

    });

    $('body').on('click', 'a.restrict', function(event) {
          event.preventDefault();  
          $( "#restrict-dialog-form" ).dialog( "open" );
    });
});	