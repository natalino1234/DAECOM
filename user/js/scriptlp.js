function searchCEP(){
if($("#cep").val().length===8){
	/*$("#logradouro").attr("disabled",true);
	$("#bairro").attr("disabled",true);
	$("#cidade").attr("disabled",true);
	$("#uf").attr("disabled",true);*/
	//Nova variável "cep" somente com dígitos.
	var cep = $("#cep").val().replace(/\D/g, '');

	//Expressão regular para validar o CEP.
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
				$("#uf").parent().parent().children("td").children(".edit").css({"display":"inline"});
				$("#logradouro").attr("size",""+($("#logradouro").val()).length);
				$("#uf").attr("size",""+($("#uf").val()).length);
				$("#bairro").attr("size",""+($("#bairro").val()).length);
				$("#cidade").attr("size",""+($("#cidade").val()).length);
			} //end if.
			else {
				//CEP pesquisado não foi encontrado.
				limpa_formulario_cep();
				$("#help").css({
					"display" : "block",
					"color":"red",
					"background":"#ffb0b0"
				});
				$("#help").html("CEP não encontrado.");
				$("#cep").parent(".input").css({
												"color":"red",
												"background":"#ffb0b0"
											});
			}
		});
	} else {
		//cep é inválido.
		limpa_formulario_cep();
		$("#help").css({
			"display" : "block",
			"color":"red",
			"background":"#ffb0b0"
		});
		$("#help").html("Formato de CEP inválido.");
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
	str = str.replace(/[ÀÁÂÃÄÅ]/,"A");
	str = str.replace(/[àáâãäå]/,"a");
	str = str.replace(/[óòõô]/,"o");
	str = str.replace(/[ÓÒÕÔ]/,"O");
	str = str.replace(/[úùû]/,"u");
	str = str.replace(/[ÚÙÛ]/,"u");
	str = str.replace(/[ÈÉÊË]/,"E");
	str = str.replace(/[éèê]/,"e");
	str = str.replace(/[Ç]/,"C");
	str = str.replace(/[ç]/,"c");
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
		}
		if($("#uf").val()===""){
			err = true;
			msg+="Preencha o campo de ESTADO.";
			$("#uf").parent(".input").css({
												"color":"red",
												"background":"#ffb0b0"
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
					$("#help").html("Verifique se a Cidade e Estado estão corretos, e se o logradouro está com o que deseja correto.");
					$("#cep").parent(".input").css({
													"color":"red",
													"background":"#ffb0b0"
												});
				}
			});
		}
	}
}

function procuracep(cep){
	$("#cep").val(cep);
	searchCEP();
	$("#help").css({"display" : "none"});
}