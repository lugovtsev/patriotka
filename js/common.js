$(document).ready(function() {

$(function($){
	   $("#phone").mask("+7-(999)-999-99-99");
	});
	
	//отправка формы
		  function SendForm(formid) {
		  //Валидация полей формы c email
		    var regExpMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
		    var regExpName = /[^а-яЁё\s]/i;
		    var curMail = $('#mail1').val();
		    var curName = $('#name1').val();
		    var status;
		    if ($('#name1').val()=="" || $('#phone1').val()==""  || $('#mail1').val()=="") {
		    	$('#error-form').fadeOut(300).html('<center><p style="color:#d32f2f; font-size:13px;">Все поля обязательны для заполнения</p></center>').fadeIn(300);
		    	status = false;
		    } else {
		    		if (regExpMail.test(curMail)) {
		    				if (!regExpName.test(curName)) {
		    					status = true;
		    				}
		    				else {
		    					$('#error-form').fadeOut(300).html('<center><p style="color:#AF2321;">Имя введено с ошибкой</p></center>').fadeIn(300);
		    						status = false;
		    				}
		    		}
		    		else {
		    			$('#error-form').fadeOut(300).html('<center><p style="color:#AF2321;">Неверный формат e-mail</p></center>').fadeIn(300);
		    			status = false;
		    		}
		    	}
		    //Валидация полей формы

		    if(status) {

		      $$f({

		        formid:formid,//id формы

		        url:'/export/php-script/post.php',//адрес на серверный скрипт, такой же как и в форме
		       onstart:function () {//действие при начале отправки
		             $('#error-form').html('<center>Идет отправка</center>');

		       },
		       onsend:function () {//действие по окончании отправки
		             $('#error-form').html('<center><p style="color:green;">Успешно отправлено!</p></center>');
		       }
		     });
		   }
		 }

});

