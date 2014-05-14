// ===========================================================
// jQuery datePicker usage script
// ===========================================================
// Copyright 2014 HM
//
// example jQuery script for excluding dates from jQuery datePicker.
// script has just taken from layout.phtml but was not tested for
// jQuery plugIn usage

// Dependencies:
// - /css/smoothness/jquery-ui-1.10.4.custom.css
// - /css/smoothness/jquery-ui-1.10.4.custom.min.css
// - /js/jquery-1.10.2.js
// - /js/jquery-ui-1.10.4.custom.js
// - /js/jquery-ui-1.10.4.custom.min.js
// ==========================================================
!function ($) {

    $.datepicker.setDefaults(
        $.extend( $.datepicker.regional[ 'de' ] )
    );
    $( '#datepicker' ).datepicker({
        minDate: '+3',
        maxDate: '+3m',
        beforeShowDay: noSunday
    });


 /* NO SUNDAY
  * =================== */

  function noSunday(date){
     var day = date.getDay();
     return [(day > 0), ''];
  }

}(window.jQuery);
