// HEADER ANIMATION
window.onscroll = function() {scrollFunction()};
var element = document.getElementById("body");
function scrollFunction() {
  var shownavbar = $("#navbar").height();
  //console.log('shownavbar', shownavbar);
  if (document.body.scrollTop > shownavbar || document.documentElement.scrollTop > shownavbar) {
      $("#div-scroll-to-top").show();
      $(".navbar").addClass("fixed-top");
      element.classList.add("header-small");
      $("body").addClass("body-top-padding");
      $('.nav-link-home').removeClass("active");
  } else {
      $("#div-scroll-to-top").hide();
      $(".navbar").removeClass("fixed-top");
      element.classList.remove("header-small");
      $("body").removeClass("body-top-padding");
      $('.nav-link-home').addClass("active");
  }
}

function scrollToTop() {
  $("html, body").animate({
      scrollTop: 0
  }, {
      duration: 1000,
  });
  $('body').scrollspy({ target: '.navbar',offset: 0 });
}

function contactUsForm() {
  var name = $('#contact-us-form').find('#Name').val();
  var email = $('#contact-us-form').find('#Email').val();
  var subject = $('#contact-us-form').find('#Subject').val();
  var message = $('#contact-us-form').find('#Message').val();
  var url = "mailto:jambu@voipawesome.com?from="+email+"&subject="+subject+"&body="+message+"%20-%20"+name;
  window.open(url, "_blank");

}

// OWL-CAROUSAL
$('.owl-carousel').owlCarousel({
    items: 3,
    loop:true,
    nav:false,
    dot:true,
    autoplay: true,
    slideTransition: 'linear',
    autoplayHoverPause: true,
    responsive:{
      0:{
          items:1
      },
      600:{
          items:2
      },
      1000:{
          items:3
      }
  }
})

// SCROLLSPY
$(document).ready(function() {
  scrollFunction();
  $("#btn-contact-us-submit").click(function() { contactUsForm(); });
  $("#btn-scroll-to-top").click(function() { scrollToTop(); });
  $(".nav-link").click(function() {
      var t = $(this).attr("href");
      if (t == "#home") {
          $("html, body").animate({
              scrollTop: 0
          }, {
              duration: 1000,
          });
          $('body').scrollspy({ target: '.navbar',offset: 0 });
      } else if ((t != "#") && (!t.includes("javascript:"))) {
          $("html, body").animate({
              scrollTop: $(t).offset().top - 75
          }, {
              duration: 1000,
          });
          $('body').scrollspy({ target: '.navbar',offset: $(t).offset().top });
      }
      return false;
  });

});

// AOS
AOS.init({
    offset: 120,
    delay: 0,
    duration: 1200,
    easing: 'ease',
    once: true,
    mirror: false,
    anchorPlacement: 'top-bottom',
    disable: "mobile"
  });

//SIDEBAR-OPEN
  $('#navbarSupportedContent').on('hidden.bs.collapse', function () {
    $("body").removeClass("sidebar-open");
  })
  $('#navbarSupportedContent').find('a.nav-link').on('click', function () {
    $('#navbarSupportedContent').removeClass('show');
    $("body").removeClass("sidebar-open");
  })

  $('#navbarSupportedContent').on('shown.bs.collapse', function () {
    $("body").addClass("sidebar-open");
  })


  window.onresize = function() {
    var w = window.innerWidth;
    if(w>=992) {
      $('body').removeClass('sidebar-open');
      $('#navbarSupportedContent').removeClass('show');
    }
  }