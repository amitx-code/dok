  //  ANIMATION WHEN MOVING MOUSE
  let lFollowX = 0,
  lFollowY = 0,
  x = 0,
  y = 0,
  friction = 1 / 30;

function moveBackground() {
  x += (lFollowX - x) * friction;
  y += (lFollowY - y) * friction;

  translate = `translate(${x}px, ${y}px) scale(1)`;

  $('body .shapes,body .half-circle').css({
    '-webit-transform': translate,
    '-moz-transform': translate,
    transform: translate,
  });

  window.requestAnimationFrame(moveBackground);
}

$(window).on('mousemove click', (e) => {
  const lMouseX = Math.max(-55, Math.min(100, $(window).width() / 2 - e.clientX));
  const lMouseY = Math.max(-55, Math.min(100, $(window).height() / 2 - e.clientY));
  lFollowX = (0 * lMouseX) / 100; // 100 : 12 = lMouxeX : lFollow
  lFollowY = (50 * lMouseY) / 100;
});
moveBackground();
// TEXT ANIMATION WHEN MOVING MOUSE - ENDS HERE


// ANIMATION STATS
$(document).ready(($) => {
  'use strict';
  $('.counter').counterUp({
    delay: 50,
    time: 2000,
  });
});
// ANIMATION STATS - ENDS HERE

// magnificPopup Configuration (Portfolio)
$('.image-link').magnificPopup({
  type: 'image',
  mainClass: 'mfp-with-zoom', // this class is for CSS animation below

  zoom: {
    enabled: true, 

    duration: 700, // duration of the effect, in milliseconds
    easing: 'ease-in-out', // CSS transition easing function

    // The "opener" function should return the element from which popup will be zoomed in
    // and to which popup will be scaled down

    opener(openerElement) {
      // openerElement is the element on which popup was initialized, in this case its <a> tag
      // you don't need to add "opener" option if this code matches your needs, it's defailt one.
      return openerElement.is('img') ? openerElement : openerElement.find('img');
    },
  },

});
// magnificPopup Configuration (Portfolio) - ends here

//PORTFOLIO NAVLINKS FILTERING
$(function () {
  
  var selectedClass = "";
  $(".fil-cat").on("click", function () {
      selectedClass = $(this).attr("data-rel");
      $("#portfolio").fadeTo(400, 0);
      $("#portfolio div").not("." + selectedClass).fadeOut().removeClass('scale-anm');
      setTimeout(function () {
          $("." + selectedClass).fadeIn().addClass('scale-anm');
          $("#portfolio").fadeTo(400, 1);
      }, 300);

  });
});
//PORTFOLIO NAVLINKS FILTERING ENDS HERE


//GLOBAL CONFIGURATION FOR SCROLL ANIMATIONS
AOS.init({
  offset: 0,
  duration: 700,
  easing: 'ease',
  delay: 0,
  once: true
});

// home Carousel slider interval
$(document).ready(function() {
  'use strict';
    $('.carousel').carousel({
      interval: 6000, // change your prefered interval
      pause: "false"
    })
  });
// home carousel ends here


//CONTACT FORM
$(function () {
  'use strict';
  $("#contact-form").validator(), $("#contact-form").on("submit", function (t) {
    if (!t.isDefaultPrevented()) {
      return $.ajax({
        type: "POST",
        url: "contact.php",
        data: $(this).serialize(),
        success: function (t) {
          var a = "alert-" + t.type,
            e = t.message,
            s = '<div class="alert ' + a + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + e + "</div>";
          a && e && ($("#contact-form").find(".messages").html(s), $("#contact-form")[0].reset())
        }
      }), !1
    }
  })
});

// preloader
$("#fakeLoader").fakeLoader();
// preloader call ends here