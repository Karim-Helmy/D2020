$(document).ready(function () {

	"use strict";

	setTimeout(() => {
		$('.loader-container').hide()
		$('body').addClass('animated fadeIn')
	}, 500);
	var wow = new WOW(
		{
			boxClass: 'wow',      // default
			animateClass: 'animated', // default
			offset: 200,          // default
			mobile: true,       // default
			live: true        // default
		}
	)
	wow.init();

	// init carousel of header
	$('.main_hero').owlCarousel({
		dots: true,
		nav: false,
		items: 1,
		loop: true,
		rtl: true
	})

	// init carousel of courses
	$('.courses_slider').owlCarousel({
		dots: true,
		nav: true,
		items: 1,
		rtl: true,
		loop: true,
	})

	// init carousel of partners
	$('.partners_slider').owlCarousel({
		dots: true,
		nav: true,
		items: 1,
		loop: true,
		rtl: true,
		margin: 30,
		responsive: {
			0: {
				items: 2
			},
			768: {
				items: 3
			},
			1190: {
				items: 4
			}
		}
	})

	// init carousel of partners
	$('.user_slider').owlCarousel({
		dots: false,
		nav: true,
		rtl: true,
		items: 1,
		loop: true,
		margin: 30
	})

	$('.owl-carousel .owl-prev').html('<i class="fa fa-chevron-right"></i>')
	$('.owl-carousel .owl-next').html('<i class="fa fa-chevron-left"></i>')
	// clone nav menu to mobile menu

	$('.our_mobile_menu').append($('.navbar-nav').clone())

	$('.navbar_toggler').click(function (e) {
		$('.our_mobile_menu').addClass('open')
	})

	//
	$('.our_mobile_menu button').click(function (e) {
		e.preventDefault();
		$(this).parent().removeClass('open')
	});

	// open dashboard menu
	$('.profile a').click(function (e) {
		$(this).next('.profile_menu').toggleClass('show')
		e.stopPropagation()
	})

	// close dash

	$('body').click(function () {
		$('.profile_menu').removeClass('show')
	});

	// checkbox all

	var x = false
	$('.checkbox_all input').change(function () {
		$('.our_main_table tbody input').prop('checked', !x)
		x = !x
	});

	$('.data_input').datetimepicker();
	$('.date_only').datetimepicker({
		timepicker: false,
		format: 'd.m.Y'
	})
	// clone level

	$('.add_new_btn').click(function () {

		$('.levels_wrapper').append($('.level_panel:first').clone())
	});

	// delete line

	$(document).on('click', '.delete_line', function () {
		var parent = $(this).parents('.level_panel')
		parent.remove()
	});

	// upload image

	$('.input_image input').change(function () {
		var fileName = $(this).val()

		$(this).addClass('d-none')
		$(this).siblings('span').addClass('uploaded').html('<i class="fa fa-times"></i> Delete attachment')
		$(this).siblings('p').text(fileName)
	});
	// show image

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('.input_image img').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".input_image input").change(function () {
		readURL(this);
	});

	// remove attach

	$(document).on('click', '.input_image .uploaded', function () {
		$(this).removeClass('.uploaded').html('<i class="fal fa-upload"></i> Upload image')
		$(this).siblings('input').val('').removeClass('d-none')
		$(this).siblings('p').text('')
		$('.input_image img').attr('src', '');
	})

	// add answer
	$('.add_answer').click(function () {

		$('.answers_wrapper .row').append($('.answer_panel:first').clone())
	});

	// remove answer
	$(document).on('click', '.clear_answer', function () {
		$(this).parents('.answer_panel').remove()
	});

	$('.add_answer2').click(function () {

		$('.answers_wrapper2 .row').append($('.answer_panel2:first').clone())
	});

	// remove answer
	$(document).on('click', '.clear_answer2', function () {
		$(this).parents('.answer_panel2').remove()
	});

	// show hide based on select

	$('.q_select').change(function (e) {
		var val = $(this).val()
		$('.q_wrapper').addClass('d-none')
		$('#' + val).removeClass('d-none')
	});


	// add fixed after scroll

	$(window).scroll(function () {
		var scroll = $(window).scrollTop()

		if (scroll > 200) {
			$('.exam_time').addClass('fixed')
		} else {
			$('.exam_time').removeClass('fixed')
		}
	});

	// landing page click
	$("nav").find("a").click(function () {
		var section = $(this).attr("href");
		$("html, body").animate({
			scrollTop: $(section).offset().top
		});
	});

	// change location iframe

	$('.branches a').click(function (e) {
		var location = $(this).data('location')

		$('.our_location').attr('src', location)

	});

	// pricing panel
	$('.pricing_panel a').click(function () {
		var parent = $(this).parent()
		var plan = parent.find('h3').text()

		$('.choose_plan_form .plan_input').val(plan)

		$('body, html').animate({
			scrollTop: $('.choose_plan_form').offset().top
		}, 1000)
	});
});
