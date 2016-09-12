$(document).ready(function() {
	CupcakeReady();
});


function CupcakeReady() {
//	$.datepicker.setDefaults( $.datepicker.regional[ "pt" ] );
//	$( ".cupdatepicker" ).datepicker({
//		dateFormat: 'dd/mm/yy',
//	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
//	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
//	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
//	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
//	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
//	    nextText: 'Próximo',
//	    prevText: 'Anterior',
//	    enableOnReadonly:false
//	});

	$('.icon-help').click(function() {
		tour = loadTour();
		tour.restart(true);
	});

	$('.tr-link').mousedown(function(e){
		//$('#'+$(this).attr('data-rel')).click();
		//$('#'+$(this).attr('data-rel')).click();
	});

	$('#select-limit').change(function() {
    	loadModal();
    	window.location.href = $(this).val();
    });

	$('.Cupcake-form').submit(function() {
		loadingModal();
		$(this).submit();
	});

	$('.load-modal').click(function(){
		loadingModal();
	});

	$('.ajax-form').submit(function(){
        loadingModal();
		type = $(this).attr('method').toUpperCase();
		url = $(this).attr('action');
		data = $(this).serialize();

		$.ajax({
			type: type,
	  		url: url,
	  		data: data,
			success:function(data){
				if(data && data != 1) {
					return trataAjaxReturn(data);
				}
			}
		});
		return false;
	});

	$('.ajax-link').click(function(e){
		loadingModal();
		e.preventDefault();
		type = "GET",
		url = $(this).attr('href'),
		$.ajax({
			type: type,
			url: url,
			data: '',
			success:function(data){
				if(data && data != 1) {
					return trataAjaxReturn(data);
				}
			}
		});
		return false;
	});

	$('.Cupcake-nav').click(function() {
		loadingModal();
	});

	$("#compra-id").focus(function() {
		$(this).val('');
	});

	$('#buscar-compra-topo').click(function() {
		$('#form-compra-topo').submit();
	});

	$('.btn-print').click(function() {
		imprime();
	});

	$(".pop-nav").click(function(e) {
		e.preventDefault();
		href = $(this).attr('href');
		pop(href);
	});
	$(".pop-peq-nav").click(function(e) {
		e.preventDefault();
		href = $(this).attr('href');
		popPeq(href);
	});

	$('.link-fake').click(function(e) {
		e.preventDefault();
		id = $(this).attr('data-rel');
		$('#'+id).click();
	});


	$('.select-pagination').change(function(e) {
		CupcakePaginate($(this).val());
	});

	$('.cupcake-tab-navigation').click(function(e) {
		e.preventDefault();
		if(!$(this).hasClass('active')) {
			loadingModal();
			var theinput = $('<input>',{
				name: 'CupcakeTab',
				value: $(this).attr('href'),
				type: 'hidden'
			}).appendTo('#nav-form');
			resetPagination();
			$('#nav-form').submit();
		}
		closeModal();
	});

	if(!(typeof CupcakeTab === 'undefined')) {

		if(CupcakeTab != '') {
			$(".cupcake-tab-navigation").parent().removeClass("active");
			$(".cupcake-tab-pane").removeClass("active");
			$("a[href='"+CupcakeTab+"']").first().parent().addClass("active");
			$('#'+CupcakeTab.replace('#', '')).addClass("active");
		}
	}

	$('.float').keyup(function() {
		v = $(this).val().replace(/\D/g,"");//Remove tudo o que não for dígito
        v = v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
        v = v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        v = v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos digityos

        $(this).val(v);
	});

	$('.nao-tem').click(function(e){
		e.preventDefault();
		showNotyError('Está página ainda não foi implementada :(<br/>Mas você acabou de dar um voto para que ela exista.<br/>Aguarde e confie...');
		return false;
	});

	$('.autofocus').focus(function(){
		$(this).select();
	});

	//notifications
	$('.noty').click(function(e){
		e.preventDefault();
		var options = $.parseJSON($(this).attr('data-noty-options'));
		noty(options);
	});

	//popover
	$('[rel="popover"],[data-rel="popover"]').popover();

//    if (!Modernizr.inputtypes.date) {
//    	$( 'input[type=date]' ).datepicker({
//    		dateFormat: 'dd/mm/yy',
//    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
//    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
//    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
//    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
//    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
//    	    nextText: 'Próximo',
//    	    prevText: 'Anterior',
//    	    enableOnReadonly:false
//    	});
//    	$('[type=date]').each(function(){
//    		if ($(this).val()) {
//    			var val = $(this).val().split('-');
//    			$(this).val(val[2]+'/'+val[1]+'/'+val[0]);
//    		}
//    	});
//    }

}

function pop(destino, janela) {
	if (destino.indexOf("?") > 0) {
		concat = '&';
	} else {
		concat = '?'
	}
	var thelink = $('<a>',{
	    href: destino+concat+'pop=1&new=1'
	}).appendTo('body');
	$(thelink).addClass('pop');
	$(thelink).addClass('fancybox');
	$(thelink).attr('data-fancybox-type','iframe');
	$(thelink).click();
}

function popPeq(destino, janela) {
	if (destino.indexOf("?") > 0) {
		concat = '&';
	} else {
		concat = '?'
	}
	var thelink = $('<a>',{
		href: destino+concat+'pop=1&new=1'
	}).appendTo('body');

	$(thelink).attr('data-fancybox-type','iframe').addClass('pop-peq');
	$(thelink).click();
}

function ajaxCall(url, inner) {
	type = 'GET';
	data = false;

	$.ajax({
		type: type,
  		url: url,
  		data: data,
		success:function(data){
			if(data && data != 1) {
				retorno = trataAjaxReturn(data);
				if (inner !== null) {
					$('#'+inner).html(retorno);
					$("#"+inner).effect( "shake", { direction: "up", times: 10, distance: 3}, 12 );
					closeModal();
				} else {
					return retorno;
				}

			}
			return retorno;
		}

	});

}

function trataAjaxReturn(dataReturn) {
	data = dataReturn.split(';');

	if(data[0] == '0' || data[0] == 'alert') {
		CupcakeAlert(data[1]);
	} else if (data[0] == '1') {
		pop(data[1]);
	} else if (data[0] == '2') {
		parent.location.href = data[1];
	} else if (data == "3") {
		parent.location.href = parent.location.href;
	} else if (data[0] == "4" || data[0] == 'redirect' || data[0] == 'redir' || data[0] == 'location') {
		window.location.href = data[1];
	} else if (data == "5" || data == "reload") {
		location.reload();
	} else if (data == "6") {
		return false;
	} else if (data == "7") {
		parent.$.fancybox.close();
	} else {
		return dataReturn;
	}

}

function telaCheia() {
	$('.hidden-tablet').hide();
	$('.main-menu-span').css('width','40px');
	$('.main-menu-span').removeClass('span2');
	$('.main-menu-span').addClass('span1');

	$('.main-content').removeClass('span10');
	$('.main-content').addClass('span11');
}

function CupcakeAlert(msg, btnAlt) {
	typeof btnAlt !== 'undefined' ? $('#default-modal-title').html(btnAlt) : false;
	$('#default-modal-body').html(msg);

    closeModal();
	$('#alertDefault').modal('show');
}

function loadingModal() {
	$('.loading-modal').show();
    $("body").css("cursor", "wait");
}

function loadDarkScreen() {
	$('.dark-screen').show();
}

function closeDarkScreen() {
	$('.dark-screen').hide();
}

function loadModal() {
	loadingModal();
}

function closeModal() {
	$('.loading-modal').fadeOut();
	$("body").css("cursor", "auto");
}

function imprime() {
	type = $('#nav-form').attr('method').toUpperCase();
	url = $('#nav-form').attr('action');

	$('<input>',{
		name: 'print',
		value: '1',
		type: 'hidden'
	}).appendTo('#nav-form');
	data = $('#nav-form').serialize();

	$.ajax({
		type: type,
  		url: url,
  		data: data,
		success:function(data){
			originalContents = document.body.innerHTML;
			document.body.innerHTML = data;
			window.print();

			document.body.innerHTML = originalContents;
			$('<input>',{
				name: 'print',
				value: '',
				type: 'hidden'
			}).appendTo('#nav-form');
			CupcakeReady();
			closeModal();

		}
	});
}

function showNotyError(msg) {
	$('.noty_error_msg').html(msg);
	$('.noty_error').slideDown();
	$('.noty_error').delay(4000).slideUp();
}

function showNotyWarning(msg) {
	$('.noty_warning_msg').html(msg);
	$('.noty_warning').slideDown();
	$('.noty_warning').delay(4000).slideUp();
}

function preparaData(idField)
{
	if (!Modernizr.inputtypes.date) {
		var dataCalcArray = $('#'+idField).val().split('/');
		return dataCalcArray[2]+'-'+dataCalcArray[1]+'-'+dataCalcArray[0];
    } else {
    	return $('#'+idField).val();
    }
}

document.onkeydown = function(){
	switch (event.keyCode) {
/*		case 116 : //F5 button

			loadingModal();
			event.returnValue = false;
			event.keyCode = 0;
			$('#nav-form').submit();

			return false;*/
		case 82 : //R button
			if (event.ctrlKey) {

				loadingModal();
				event.returnValue = false;
				event.keyCode = 0;
				$('#nav-form').submit();

                return false;
            }
		case 80 : //P button
			if (event.ctrlKey) {
				imprime();
				return false;
			}
		}
	}
