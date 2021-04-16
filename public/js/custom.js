$(document).ready(function () {

	//AJAX
	    $("#EmailOTP").click(function(e){
	        e.preventDefault();
	        $('#EmailOTP').hide();
	         $('#loader').show();

	        let email = $("input[name=email]").val();
	        let token = $("input[name=_token]").val();

	        $.ajax({
	           type:'POST',
	           url:'/getotp',
	           data:{_token: token, email:email},
	           success:function(data){
	           	   $('#loader').hide();
	           	   $('#register').show();
	               $('#OTPSection').show();
	           }
	        });
		});

});