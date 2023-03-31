"use strict"


function gettimezone() {
	var timezone_offset_minutes = new Date().getTimezoneOffset();
	timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
	$.ajax({
		url: $("#siteurl").val() + "/settimezone" + "/" + timezone_offset_minutes,
		method: "get",
		success: function (data) {

		}
	});
}

function changehiddenstatus() {
	$("#is_chat").val(1);
}


AOS.init();

function opendatepicker() {
	$('#app_date').datepicker();
	$('#app_date').datepicker('show');
}

function play_sound() {
	var source = $("#soundnotify").val();
	var audioElement = document.createElement('audio');
	audioElement.autoplay = true;
	audioElement.load();
	audioElement.addEventListener("load", function () {
		audioElement.play();
	}, true);
	audioElement.src = source;
	audioElement.id = "notificationsound";
}

function PlaySound() {
	var source = $("#soundnotify").val();
	var audioElement = document.getElementById('notificationboxaudio');
	audioElement.autoplay = true;
	audioElement.load();
	audioElement.addEventListener("load", function () {
		audioElement.play();
	}, true);
	audioElement.src = source;
}


function StopSound() {
	var player = document.getElementById('notificationboxaudio');
	player.Src = $("#soundnotify").val();
	player.autoplay = false;

	player.pause();

}

function isPlaying() {
	if (!document.getElementById('notificationboxaudio').paused) {
		return 1;
	}
}


function showmodel() {
	$("#ssuccess").toggle('show');
}

function closemode() {
	$("#ssuccess").toggle('hide');
}

function logout() {
	//alert("hello");
	var currentuser = $("#authid").val();
	firebase.database().ref(currentuser + "/TokenList/").update({
		web: 0
	});
	$("#tab-lg").addClass("active");
	window.location.href = $("#siteurl").val() + "/userlogout";
}

function changedoctorblog(idata) {
	var totaldoctor = $("#avilabledoctor").val();
	for (var i = 0; i < totaldoctor; i++) {
		if ($("#collapse" + i).length) {
			if (i == idata) {
				$("#collapse" + i).addClass(" in");
			} else {
				$("#collapse" + i).removeClass(" in");
			}
		}
	}

}


var lastScrollTop = 0;
$(window).scroll(function (event) {
	var $header = $('#header');
	var headerPinned = 'header-pinned';
	var headerUnpinned = 'header-unpinned';
	var scroll = $(this).scrollTop();
	var os = $header.offset().top;
	var ht = $header.height();
	if (scroll > lastScrollTop) {
		if (scroll > ht) {
			$header.addClass(headerUnpinned).removeClass(headerPinned);
		}
	} else {
		$header.addClass(headerPinned).removeClass(headerUnpinned);
	}
	lastScrollTop = scroll;
});
var a = 0;
$(window).scroll(function () {
	if (document.getElementById("counter")) {
		var oTop = $('#counter').offset().top - window.innerHeight;
		if (a == 0 && $(window).scrollTop() > oTop) {
			$('.counter-value').each(function () {
				var $this = $(this),
					countTo = $this.attr('data-count');
				$({
					countNum: $this.text()
				}).animate({
					countNum: countTo
				}, {
					duration: 2000,
					easing: 'swing',
					step: function () {
						$this.text(Math.floor(this.countNum));
					},
					complete: function () {
						$this.text(this.countNum);
					}
				});
			});
			a = 1;
		}
	}
});


$(function () {
	var left = $("#leftimg").val();
	var right = $("#rightimg").val();
	$('.owl-carousel.testimonial-carousel').owlCarousel({
		nav: true,
		navText: ['<img src="' + right + '">', '<img src="' + left + '">'],
		dots: false,
		responsive: {
			0: {
				items: 1,
			},
			750: {
				items: 2,
			}
		}
	});
});
$(window).load(function () {

	$(".loader").delay(2000).fadeOut("slow");
	$("#overlayer").delay(2000).fadeOut("slow");


})

function getserviceanddoctor(val) {
	$.ajax({
		url: $("#siteurl").val() + "/getserviceanddoctor" + "/" + val,
		method: "get",
		success: function (data) {
			var str = JSON.parse(data);
			var txt = "";
			var txt1 = "";
			for (var i = 0; i < str.service.length; i++) {
				txt = txt + '<option value="' + str.service[i].id + '">' + str.service[i].name + '</option>';
			}
			document.getElementById("service").innerHTML = txt;
			for (var i = 0; i < str.doctor.length; i++) {
				txt1 = txt1 + '<option value="' + str.doctor[i].user_id + '">' + str.doctor[i].name + '</option>';
			}
			document.getElementById("doctors").innerHTML = txt1;
		}
	});
}

function userloginalert() {

	document.getElementById("loginerrorreview").innerHTML = '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">' + $("#loginalert").val() + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
}
$(document).ready(function () {
	$('.customer-logos').slick({
		slidesToShow: 6,
		slidesToScroll: 1,
		autoplay: false,
		autoplaySpeed: 800,
		arrows: false,
		dots: false,
		pauseOnHover: false,
		responsive: [{
			breakpoint: 768,
			settings: {
				slidesToShow: 4
			}
		}, {
			breakpoint: 520,
			settings: {
				slidesToShow: 3
			}
		}]
	});
});

function openCity(evt, cityName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
}
if (document.getElementById("defaultOpen")) {
	document.getElementById("defaultOpen").click();
}

function savesubscribe() {
	var email = $("#subscribeemail").val();
	if (ValidateEmail(email) == "invaild") {
		alert("Please Enter Vaild Email");
	} else {
		$.ajax({
			url: $("#siteurl").val() + "/savesubscribe" + "/" + email,
			method: "get",
			success: function (data) {
				alert(__('messages.newsletteralert'));
			}
		});
	}
}

function ValidateEmail(mail) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
		return "vaild";
	}

	return "invaild";
}


function showloginmodel() {
	$("#loginmodel").show();
	$("#registermodel").hide();
	$("#forgotmodel").hide();
}

function showregmodel() {
	$("#loginmodel").hide();
	$("#registermodel").show();
	$("#forgotmodel").hide();
}

function showforgotmodel() {
	$("#loginmodel").hide();
	$("#registermodel").hide();
	$("#forgotmodel").show();
}

function Postlogin() {
	var email = $("#login_email").val();
	var password = $("#login_password").val();
	var temp = 0;
	if (email == "") {
		document.getElementById("login_email_error").innerHTML = $("#emailreq").val();
		temp = 1;
	}
	if (ValidateEmail(email) == "invaild") {
		document.getElementById("login_email_error").innerHTML = $("#vaildemail").val();
		temp = 1;
	}
	if (password == "") {
		document.getElementById("login_password_error").innerHTML = $("#pwdreq").val();
		temp = 1;
	}
	if (temp == 1) {
		$("#login_email").val("");
		$("#login_password").val("");
	} else {

		$.ajax({
			url: $("#siteurl").val() + "/postlogin",
			method: "get",
			data: {
				email: email,
				password: password,
				rem_me: 0
			},
			success: function (data) {
				var str = JSON.parse(data);
				if (str.status == 1) {
					console.log(str.data.id);
					var starCountRef1 = firebase.database().ref(str.data.id);
			            starCountRef1.once('value', (snapshot1) => {
				                const user = snapshot1.val();
				                console.log(user);
								if (user == null) {
									firebase.database().ref(str.data.id).update({
											name: str.data.name,
											profile:'profile/'+str.data.profile_pic,
											usertype:1
									}, (error) => {
										if (error) {

										} else {
												if ($("#is_chat").val() == 1) {
												    $("#is_chat").val(0);
												    window.location.href = $("#siteurl").val() + "/chatarea";
											    } else {
												    window.location.href = $("#siteurl").val() + "/myaccount";
											    }
										}
									});
								}else{
									firebase.database().ref(str.data.id).set({
									     	name: str.data.name,
											profile:'profile/'+str.data.profile_pic,
											usertype:1
									});
									if ($("#is_chat").val() == 1) {
										$("#is_chat").val(0);
										window.location.href = $("#siteurl").val() + "/chatarea";
									} else {
										window.location.href = $("#siteurl").val() + "/myaccount";
									}
								}
						});
				} else {
					showloginmodel();
					document.getElementById("login_error").innerHTML = '<div class="alert  alert-danger alert-dismissible fade show" role="alert">' + str.data + '<button type="button" style="background: none !important;" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="float: inherit;">×</span></button></div>';
				}
			}
		});

	}
}

function PostRegister() {
	var name = $("#reg_name").val();
	var email = $("#reg_email").val();
	var phone = $("#reg_phone").val();
	var password = $("#reg_pwd").val();
	var confirmpassword = $("#reg_cpwd").val();
	var temp = 0;
	if (name == "") {

		document.getElementById("reg_name_error").innerHTML = $("#namereq").val();
		temp = 1;
	}
	if (email == "") {

		document.getElementById("reg_email_error").innerHTML = $("#emailreq").val();
		temp = 1;
	}
	if (ValidateEmail(email) == "invaild") {

		document.getElementById("reg_email_error").innerHTML = $("#vaildemail").val();
		temp = 1;
	}
	if (phone == "") {
		document.getElementById("reg_phone_error").innerHTML = $("#phonereq").val();
		temp = 1;
	}
	if (password == "") {
		document.getElementById("reg_password_error").innerHTML = $("#pwdreq").val();
		temp = 1;
	}
	if (confirmpassword == "") {
		document.getElementById("reg_cpwd_error").innerHTML = $("#confirmpwdreq").val();
		temp = 1;
	}
	if (temp == 1) {
		$("#reg_name").val("");
		$("#reg_email").val("");
		$("#reg_phone").val("");
		$("#reg_pwd").val("");
		$("#reg_cpwd").val("");
	} else {
		$.ajax({
			url: $("#siteurl").val() + "/postregister",
			method: "get",
			data: {
				name: name,
				email: email,
				phone: phone,
				password: password
			},
			success: function (data) {
				var str = JSON.parse(data);
				if (str.status == 1) {
					firebase.database().ref(str.data.id).set({
							name: str.data.name,
							profile:'profile/'+str.data.profile_pic,
							usertype:1
					}, (error) => {
						if (error) {

						} else {
								if ($("#is_chat").val() == 1) {
								    $("#is_chat").val(0);
								    window.location.href = $("#siteurl").val() + "/chatarea";
							    } else {
								    window.location.href = $("#siteurl").val() + "/myaccount";
							    }
						}
					});
				} else {
					document.getElementById("reg_email_error").innerHTML = $("#alreadyemailexiste").val();
				}
			}
		});
	}
}

function Postforgot() {
	var email = $("#forgot_email").val();
	var temp = 0;
	if (email == "") {

		document.getElementById("forgot_email_error").innerHTML = $("#emailreq").val();
		temp = 1;
	}
	if (ValidateEmail(email) == "invaild") {

		document.getElementById("forgot_email_error").innerHTML = $("#vaildemail").val();
		temp = 1;
	}
	if (temp == 1) {
		$("#forgot_email").val("");
	} else {
		$.ajax({
			url: $("#siteurl").val() + "/postforgot",
			method: "get",
			data: {
				email: email
			},
			success: function (data) {
				if (data == 1) {
					document.getElementById("forgot_email_success").innerHTML = $("#mailsend").val();
				} else {
					document.getElementById("forgot_email_error").innerHTML = $("#notfoundemail").val();
				}
			}
		});
	}
}

function checkbothpwd(val) {
	var pwd = $("#reg_pwd").val();
	if (pwd != val) {
		document.getElementById("reg_cpwd_error").innerHTML = $("#bothpwdsame").val();

		$("#reg_cpwd").val("");
		$("#reg_pwd").val("");
	}
}

function updatecheckbothpwd(val) {
	var pwd = $("#npwd").val();
	if (pwd != val) {
		alert($("#bothpwdsame").val());
		$("#rpwd").val("");
		$("#npwd").val("");
	}
}


function checkcurrentpwd(val) {
	$.ajax({
		url: $("#siteurl").val() + "/checkcurrentpwd",
		method: "get",
		data: {
			pwd: val
		},
		success: function (data) {
			if (data == 0) {
				alert($("#checkcpwd").val());
				$("#opwd").val("");
			}
		}
	});
}

function changedatamodelapp($department, $department_name, $id, $name) {
	var txt = '<option value=' + $department + '>' + $department_name + '</option>';
	document.getElementById("department").innerHTML = txt;
	getserviceanddoctor($department);
	$("#doctors").val($id);
}

function ordernow(package_id) {
	window.location.href = $("#siteurl").url + '/subscription' + '/' + package_id;
}

function gotopayumoney() {
	window.location.href = $("#siteurl").val() + "/payumoney";
}


function showbrain(val) {
	if (val == 1) {
		document.getElementById("braintreediv").style.display = "block";
	} else {
		document.getElementById("braintreediv").style.display = "none";
	}
}