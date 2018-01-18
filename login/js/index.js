function newUser()
{
	userused(0);
	var username = $('#username').val();
	var password = $('#password_1').val();
	var password2 = $('#password_2').val();

	if(username != "")
	{
		if(password != "")
		{
			if(password2 != "")
			{
				if(password == password2)
				{
					$.ajax({
							   url: 'php/Chat_Control.php',
							   data: { USER_NAME: username,
								       USER_PASSWORD: password,
								       OPTION: "1"},
							   error: function(xhr, status, error) {
							      		alert(xhr.responseText);
							   },
							   dataType: 'json',
							   success: function(data) 
							   {
							      if(data.status == "0")
							      {
							      	alert("User Created!");
							      	clearForm();
							      	animateForm();
							      }
							      else
							      {
							      	alert("Username in use " + data.status);
							      	userused(1);
							      }
							   },
							   type: 'POST'
							});
				}
				else
				{
					alert("Password are not the same, try again!!");
					userused(2);
				}
			}
			else
				userused(4);
		}
		else			
			userused(3);
	}
	else
		userused(1);
}

function login()
{
	userused(0);

	var username = $('#login_user').val();
	var password = $('#pass_user').val();

	if(username != "")
	{
		if(password != "")
		{
			$.ajax({
						url: 'php/Chat_Control.php',
						data: { USER_NAME: username,
						        USER_PASSWORD: password,
								OPTION: "2"},
						error: function(xhr, status, error) {
						   		alert(xhr.responseText);
						},
						dataType: 'json',
						success: function(data) 
						{
						    if(data.status == "0")
						    {
						    	alert("User login " + data.id + " - " + data.user_name);
						    	clearForm();
						    	chat();
						    	
						    }
						    else
						    {
						    	alert("Wrong username or password" + data.status);
						    	userused(7);
						    }
						},
						type: 'POST'
					});
		}
		else
			userused(6);
	}
	else
	{
		userused(5);
	}
}

function animateForm()
{
	$('form').animate({height: "toggle", opacity: "toggle"}, "slow");
}

function userused(ban)
{
	if(ban == 0)
	{
		$('#username').removeClass("error");
		$('#password_1').removeClass("error");
		$('#password_2').removeClass("error");
		$('#login_user').removeClass("error");
		$('#pass_user').removeClass("error");
	}
	else
		if(ban == 1)
		{
			$('#username').focus();
			$('#username').addClass("error");		
		}	
		else
			if(ban == 2)
			{
				$('#password_1').focus();
				$('#password_1').addClass("error");
				$('#password_2').addClass("error");
			}
			else
				if(ban == 3)
				{
					$('#password_1').focus();
					$('#password_1').addClass("error");
				}
				else
					if(ban == 4)
					{
						$('#password_2').focus();
						$('#password_2').addClass("error");
					}
					else
						if(ban == 5)
						{
							$('#login_user').focus();
							$('#login_user').addClass("error");
						}
						else
							if(ban == 6)
							{
								$('#pass_user').focus();
								$('#pass_user').addClass("error");
							}
							else
								if(ban == 7)
								{
									$('#login_user').focus();
									$('#login_user').addClass("error");
									$('#pass_user').addClass("error");
								}
}

function clearForm()
{
	$('#username').val("");
	$('#password_1').val("");
	$('#password_2').val("");
	$('#login_user').val("");
	$('#pass_user').val("");
}

function chat()
{
	$('form').animate({display: "none"}, "slow");
}

$('.message a').click(function(){
   animateForm();
});