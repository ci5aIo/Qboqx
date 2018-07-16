/**
 * Validation code obtained from Xfinity router 
 */

/*
 *  password_change.php 
 */
	$(document).ready(function() {
		//CSRF
		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		request.open('HEAD', 'actionHandler/ajax_at_a_glance.php', false);
		request.onload = function(){
			$.ajaxSetup({
				beforeSend: function (xhr)
				{
					xhr.setRequestHeader("X-Csrf-Token",request.getResponseHeader('X-Csrf-Token'));
				}
			});
		};
		request.send();
		$("table.data td").each(function() {
			if($(this).text().split("\n")[0].length > 25)
			{
				$(this).closest('table').css("table-layout", "fixed");
				$(this).css("word-wrap", "break-word");
			}
		});
	});
    
$(document).ready(function() {
	/*
	* get status when hover or tab focused one by one
	* but for screen reader we have to load all status once
	* below code can easily rollback
	*/
	//update user bar
	$.ajax({
		type: "POST",
		url: "actionHandler/ajaxSet_userbar.php",
		data: { configInfo: "noData" },
		dataType: "json",
		success: function(msg) {
			// theObj.find(".tooltip").html(msg.tips);
			for (var i=0; i<msg.tags.length; i++){
				$("#"+msg.tags[i]).find(".tooltip").html(msg.tips[i].replace(/-/g, "<br/>"));
				$("#"+msg.tags[i]).removeClass("off");
				if(msg.mainStatus[i]=="false")$("#"+msg.tags[i]).addClass("off");
				if(msg.tags[i] === "sta_fire")
				{
					if (!(("High"== msg.mainStatus[i]) || ("Medium"==msg.mainStatus[i]))) 
					{
						$("#"+msg.tags[i]).addClass("off");
					}
					$("#sta_fire a>span").text(msg.mainStatus[i] + " Security")
				}
			}
			//$sta_batt,$battery_class
			$("#sta_batt a").text(msg.mainStatus[4]+"%");
			$("#sta_batt > div > span").removeClass().addClass(msg.mainStatus[5]);
		},
		error: function(){
			// does something
		}
	});
	//when clicked on this page, restart timer
	var jsInactTimeout = parseInt("840") * 1000;
	//if ("") jsInactTimeout = 5000;	// 5 seconds debug
	// var h_timer = setTimeout('alert("You are being logged out due to inactivity."); location.href="home_loggedout.php";', jsInactTimeout);
	var h_timer = null;
	$(document).click(function() {
		// do not handle click if no-login for GA
		// if ("" == "") {
			// return;
		// }			
		// do not handle click event when count-down show up
		if ($("#count_down").length > 0) {
			return;
		}
		// console.log(h_timer);
		clearTimeout(h_timer);
		h_timer = setTimeout(function(){
			var cnt		= 60;
			var h_cntd  = setInterval(function(){
				$("#count_down").text(--cnt);
				// (1)stop counter when less than 0, (2)hide warning when achieved 0, (3)add another alert to block user action if network unreachable
				if (cnt<=0) {
					clearInterval(h_cntd);	
					jAlert("You have been logged out due to inactivity!");
					location.href="home_loggedout.php";
				}
			}, 1000);
			// use jAlert instead of alert, or it will not auto log out untill OK pressed!
			jAlert('Press <b>OK</b> to continue session. Otherwise you will be logged out in <span id="count_down" style="font-size: 200%; color: red;">'+cnt+'</span> seconds!'
			, 'You are being logged out due to inactivity!'
			, function(){
				clearInterval(h_cntd);
			});
		}
		, jsInactTimeout);
	}).trigger("click");
	// show pop-up info when focus
	$("#status a").focus(function() {
		$(this).mouseenter();
	});
	// disappear previous pop-up
	$("#status a").blur(function() {
		$(".tooltip").hide();
	});
});
$(document).ready(function() {
    comcast.page.init("Troubleshooting > Change Password", "nav-password");
    $("#pageForm").validate({
		debug: false,
		rules: {
			oldPassword: {
				required: true
				,alphanumeric: true
				,maxlength: 63
				,minlength: 3
			}
			,userPassword: {
				required: true
				,alphanumeric: true
				,maxlength: 20
				,minlength: 8
			}
			,verifyPassword: {
				required: true
				,alphanumeric: true
				,maxlength: 20
				,minlength: 8
				,equalTo: "#userPassword"
			}
		},
		submitHandler:function(form){
			next_step();
		}
    });
	$("#oldPassword").val("");
	$("#userPassword").val("");
	$("#verifyPassword").val("");
 	$("#password_show").change(function() {
		var pwd_t = $(this).prop("checked") ? 'type="text"' : 'type="password"';
		$(".password").each(function(){
			var currVal = $(this).find("input").val();
			// Note: After replaced, the $(this) of input will be changed!!!
			$(this).html($(this).html().replace(/(type="text"|type="password")/g, pwd_t));
			$(this).find("input").val(currVal);		
		});
	});
});
function getInstanceNum()
{
	var thisUser = "admin";
	switch(thisUser)
	{
	case "mso":
		return 1;
	case "cusadmin":
		return 2;
	case "admin":
		return 3;
	default: return 0;
	}
}
function cancel_save(){
	window.location = "at_a_glance.php";
}
function set_config(jsConfig)
{
	jProgress('This may take several seconds...', 60);
	$.post(
		"actionHandler/ajaxSet_wizard_step1.php",
		{
			configInfo: jsConfig
		},
		function(msg)
		{
			jHide();
			if ("Match" == msg.p_status) {
				jAlert("Changes saved successfully . <br/> Please login with the new password.", "Alert",function () {
				  window.location = "home_loggedout.php";
				});
			}
			else
			{
				jAlert("Current Password Wrong!");
			}
		},
		"json"     
	);
}
function next_step()
{
	var oldPwd = $('#oldPassword').val();
	var newPwd = $('#userPassword').val();
	var intNum = getInstanceNum();
	var jsConfig = '{"newPassword": "' + newPwd + '", "instanceNum": "' + intNum + '", "oldPassword": "' + oldPwd + '", "ChangePassword": "true"}';
	if (oldPwd == newPwd)
	{
		jAlert("Current Password and New Password Can't Be Same!");
	}
	else
	{
		set_config(jsConfig);
	}
}

/*
 *	comcast.js
 */

var comcast = window.comcast || {};

comcast.page = function() {
	function setupFormValidation() {
		$.validator.setDefaults({
			errorElement : "p"
			,errorPlacement: function(error, element) {
	            error.appendTo(element.closest(".form-row"));
	        }
		});
	
	    jQuery.extend(jQuery.validator.messages, {
	    	required: "This is a required field.",
	    	remote: "Please fix this field.",
	    	email: "Please enter a valid email address.",
	    	url: "Please enter a valid URL.",
	    	date: "Please enter a valid date.",
	    	dateISO: "Please enter a valid date (ISO).",
	    	number: "Please enter a valid number.",
	    	digits: "Please enter only digits",
	    	creditcard: "Please enter a valid credit card number.",
	    	equalTo: "Please enter the same value again.",
	    	accept: "Please enter a value with a valid extension.",
	    	maxlength: $.validator.format("Please enter no more than {0} characters."),
	    	minlength: $.validator.format("Please enter at least {0} characters."),
	    	rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
	    	range: $.validator.format("Please enter a value between {0} and {1}."),
	    	max: $.validator.format("Please enter a value less than or equal to {0}."),
	    	min: $.validator.format("Please enter a value greater than or equal to {0}."),
	    	ipv4: "Please enter an IPv4 address in the format #.#.#.#"
	    });
	
		$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
		}, "Only letters and numbers are valid. No spaces or special characters.");
		
		$.validator.addMethod("exactlengths", function(value, element, param) {
			return this.optional(element) || !jQuery.inArray( value.length, param );
		}, "Please enter exactly {0} characters.");
		
		$.validator.addMethod("hexadecimal", function(value, element) {
			return this.optional(element) || /^[a-fA-F0-9]+$/i.test(value);
		}, "Only hexadecimal characters are valid. Acceptable characters are ABCDEF0123456789.");
		
		$.validator.addMethod("exactlength", function(value, element, param) {
			return this.optional(element) || value.length == param;
		}, jQuery.format("Please enter exactly {0} characters."));
		
		$.validator.addMethod("ipv4", function(value, element) {
			return this.optional(element) || /^0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])$/i.test(value);
		}, "Please enter an IPv4 address in the format #.#.#.#");
	
	    jQuery.validator.addMethod('ip', function(val, el) {
	        function ip_valid(value) {
	            return (value.match(/^\d+$/g) && value >= 0 && value <= 255);
	            }
	            
	 /*            jQuery.validator.addMethod('ipt4', function(val, el) {
	                function ip_valid(value) {
	                    return (value.match(/^\d+$/g) && value > 0 && value <255);
	        }*/
	        
	        var inputs = $(el).closest('.form-row').find('input');
	        var isValid = true;
	        
	        inputs.each(function(index, element) {
	            isValid &= ip_valid($(element).val());
	        });
	        
	        return isValid;
	    },"Please enter a valid IP address.");
	
		$.validator.addMethod("ipv6", function(value, element) {
			return this.optional(element) || /^\s*((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4}){0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?\s*$/i.test(value);
		}, "Please enter an IPv6 address in the format");
		
		$.validator.addMethod("mac", function(value, element) {
			return this.optional(element) || /^[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]$/i.test(value);
		}, "Please enter an MAC address in the format xx:xx:xx:xx:xx:xx");
	
		$.validator.addClassRules({
		    octet: {
				range: [0,255]
		    },
		    ipv4: {
				ipv4: true
		    },
		    ipv6: {
				ipv6: true
		    },
		    hexadecimal: {
		    	hexadecimal: true
		    },
		    exactlength: {
		    	exactlength: true
		    }
		});
	}
}();
