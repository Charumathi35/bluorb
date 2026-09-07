 var $ = jQuery.noConflict();
/////////////////////////////////////////////////
 AOS.init({
      duration: 1000,
   });
//////////////////////////////////////////////////

$(document).ready(function() {
    var duration = 2000;

  
    $.easing.swing = function(x, t, b, c, d) {
        return c * (0.5 - Math.cos(x * Math.PI) / 2) + b;
    };

    var screenWidth = $(window).width();
    var targetFontSize = (screenWidth <= 768) ? "20px" : "30px"; 

    $(".text").animate({
        fontSize: targetFontSize
    }, {
        duration: duration,
        easing: 'swing', 
        step: function(now, fx) {
            $(this).css('transform', `scale(${now / 20})`);
        },
        complete: function() {
            setTimeout(function() {
                $(".preloader").css('width', '0').hide();
            }, 1000);
        }
    });
});



/////////////////////////////////////////////////////////
 document.addEventListener("DOMContentLoaded", function() {
        const preloader1 = document.getElementById("preloader");
        const circle = document.querySelector("#preloader .circle");
        const gif = document.querySelector(".preloader-gif,.preload-img-wrapper");

        window.addEventListener("load", function() {
            
            setTimeout(function() {
                gif.style.display = "none"; 
                circle.style.display = "block"; 
                circle.style.transition = "transform 1.6s";
                circle.style.transform = "scale(16)";
                setTimeout(function() {
                    preloader1.style.display = "none";
                }, 1600); 
            }, 3000);
        });
    });
/////////////////////////////////////////////
 
document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.homeSwiper', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        speed: 1000, 
        longSwipes: true,
        longSwipesRatio: 0.5,
        longSwipesMs: 500,
        shortSwipes: false,

        on: {
            slideChange: function() {
                var finishBtn = document.getElementById('finishBtn');
                if (this.isEnd) {
                    finishBtn.style.display = 'flex';
                    document.querySelector('.swiper-button-next').style.display = 'none';
                } else {
                    finishBtn.style.display = 'none';
                    document.querySelector('.swiper-button-next').style.display = 'flex';
                }
            },
        },
    });
});

////////////////////////////////////////////////////


//var swiper = new Swiper(".homeSwiper", {
//  navigation: {
//    nextEl: ".swiper-button-next",
//    prevEl: ".swiper-button-prev",
//  },
//
//  speed: 1500,
//  grabCursor: false,
//  draggable: false,
//
//
//});


////////////////////////////////////////////////
// menu open
function menuBtn(x){ 
  var menuClass = document.getElementById("menu-wrapper");
  var menuBtnHide = document.getElementById("remove-btn");
  if(x==1){
    menuClass.classList.add('open-menu')
    menuBtnHide.classList.add("remove-menu-btn")
    document.getElementById( 'menuopen-btn' ).style.display = 'none'; 
  }
 else if(x==2){
  menuClass.classList.remove('open-menu');
  menuBtnHide.classList.remove("remove-menu-btn")
     document.getElementById( 'menuopen-btn' ).style.display = 'block';
                                  
 }
}


//////////////////////////////////////////////////
        $(document).ready(function(){
            $('#bluorbmobile-menu-btn').on('click', function() {
                $('#hamburger1').prop('checked', function(i, val) {
                    return !val;
                });
            });
            
              $("#hamburger1,#bluorbmobile-menu-btn").on("click", function() {
    if ($('#mob-nav').hasClass('open-mob')) {
      $('#mob-nav').removeClass('open-mob')
    } else {
      $('#mob-nav').addClass('open-mob')
    }

  });
        });
   

////////////////////////////////////////////////
 $(document).ready(function() {
    $('.toggle-bgbluorb').click(function() {
      $('.bluorbswiperbg1, .bluorbswiperbg2').toggleClass('hidden');
    });
  }); 

    jQuery(document).ready(function($) {
        function reloadContent() {
            $.ajax({
                url: window.location.href,
                success: function(data) {
                    $('#contact-us').html($(data).find('#contact-us').html());
                }
            });
        }

        $(document).on('gform_confirmation_loaded', function(event, form_id) {
            if (form_id === 2) {
                $('#submissionModal').modal('show');
            }
        });

        $('#submissionModal').on('hidden.bs.modal', reloadContent);
        $('#closeModalButton').on('click', function() {
            $('#submissionModal').modal('hide');
        });
    });
