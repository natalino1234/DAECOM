function searchCEP(){
if($("#cep").val().length===8){
	/*$("#logradouro").attr("disabled",true);
	$("#bairro").attr("disabled",true);
	$("#cidade").attr("disabled",true);
	$("#uf").attr("disabled",true);*/
	//Nova vari�vel "cep" somente com d�gitos.
	var cep = $("#cep").val().replace(/\D/g, '');

	//Express�o regular para validar o CEP.
	var validacep = /^[0-9]{8}$/;

	//Valida o formato do CEP.
	if(validacep.test(cep)) {

		//Preenche os campos com "..." enquanto consulta webservice.
		$("#logradouro").val("...");
		$("#bairro").val("...");
		$("#cidade").val("...");
		$("#uf").val("...");

		//Consulta o webservice viacep.com.br/
		$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

			if (!("erro" in dados)) {
				//Atualiza os campos com os valores da consulta.
				$("#logradouro").val(dados.logradouro);
				$("#bairro").val(dados.bairro);
				$("#cidade").val(dados.localidade);
				$("#uf").val(dados.uf);
				$("#help").css({
					"display" : "none",
					"color":"black",
					"background":"white"
				});
				$("#cep").parent(".input").css({
												"color":"black",
												"background":"#fff"
											});
			} //end if.
			else {
				//CEP pesquisado n�o foi encontrado.
				limpa_formulario_cep();
				$("#help").css({
					"display" : "block",
					"color":"red",
					"background":"#ffb0b0"
				});
				$("#help").html("CEP n&atilde;o encontrado.");
				$("#cep").parent(".input").css({
												"color":"red",
												"background":"#ffb0b0"
											});
			}
		});
	} else {
		//cep � inv�lido.
		limpa_formulario_cep();
		$("#help").css({
			"display" : "block",
			"color":"red",
			"background":"#ffb0b0"
		});
		$("#help").html("Formato de CEP inv�lido.");
		$("#cep").parent(".input").css({
											"color":"red",
											"background":"#ffb0b0"
										});
	}
}else{
	limpa_formulario_cep();
	/*$("#logradouro").attr("disabled",false);
	$("#bairro").attr("disabled",false);
	$("#cidade").attr("disabled",false);
	$("#uf").attr("disabled",false);*/
}
}

function limpa_formulario_cep(){
$("#logradouro").val("");
$("#bairro").val("");
$("#cidade").val("");
$("#uf").val("");
}

function replaceSpecialChars(str){
	str = str.replace(/[������]/,"A");
	str = str.replace(/[������]/,"a");
	str = str.replace(/[����]/,"o");
	str = str.replace(/[����]/,"O");
	str = str.replace(/[���]/,"u");
	str = str.replace(/[���]/,"u");
	str = str.replace(/[����]/,"E");
	str = str.replace(/[���]/,"e");
	str = str.replace(/[�]/,"C");
	str = str.replace(/[�]/,"c");
return str; 
}

function searchLog(){
var err = false;
var msg = "";
	if($("#logradouro").val().length>=8){
		if($("#cidade").val()===""){
			err = true;
			msg+="Preencha o campo de CIDADE.";
			$("#cidade").parent(".input").css({
												"color":"red",
												"background":"#ffb0b0"
											});
		}else{
			$("#cidade").parent(".input").css({
												"color":"black",
												"background":"#fff"
											});
		}
		if($("#uf").val()===""){
			err = true;
			msg+="Preencha o campo de ESTADO.";
			$("#uf").parent(".input").css({
												"color":"red",
												"background":"#ffb0b0"
											});
		}else{
			$("#uf").parent(".input").css({
												"color":"black",
												"background":"#fff"
											});
		}
		if(err){
			$("#help").html(msg);
			$("#help").css({
						"display" : "block",
						"color":"red",
						"background":"#ffb0b0"
					});
		}else{
			$("#help").css({
						"display" : "block",
						"color":"black",
						"background":"white"
					});
			var cidade = replaceSpecialChars($("#cidade").val());
			var estado = $("#uf").val();
			var logradouro = $("#logradouro").val();
			$.getJSON("https://viacep.com.br/ws/"+ estado +"/"+cidade+"/"+logradouro+"/json/", function(dados) {
				if (!("erro" in dados)) {
					//Atualiza os campos com os valores da consulta.
					var dado = "<div style=\"background: #656565; color: white; border-bottom: 1px solid black;\">Escolha uma op&ccedil;&atilde;o para atualizar o formul&aacute;rio</div>";
					var size = 0;
					while(size<dados.length){
						var sel = dados[size];
						dado += "<div class='option' onclick=\"procuracep("+sel.cep.replace("-","")+")\"><div id='opcep'>"+sel.cep+"</div> - <div id='oplog'>"+sel.logradouro+"</div>"
						+" - <div id='opbai'>"+sel.bairro+"</div> - <div id='opcid'>"+sel.localidade+"</div> - <div id='opuf'>"+sel.uf+"</div>"
						+"</div>";
						size++;
					}
					$("#help").html(dado);
					$("#help").css({
						"display" : "block"
					});
				} //end if.
				else {
					$("#help").css({
						"display" : "block",
						"color":"red",
						"background":"#ffb0b0"
					});
					$("#help").html("Verifique se a Cidade e Estado est�o corretos, e se o logradouro est� com o que deseja correto.");
					$("#cep").parent(".input").css({
													"color":"red",
													"background":"#ffb0b0"
												});
				}
			});
		}
	}
}