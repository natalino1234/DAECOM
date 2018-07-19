$(document).ready(function(){
	$("#cep").keyup(function() {
		searchCEP();
	});
	$("#uf").keyup(function() {
		searchLog();
	});
	$("#cidade").keyup(function() {
		searchLog();
	});
	$("#logradouro").keyup(function() {
		searchLog();
	});
	$("#matricula").blur(function() {
		$.ajax({type: "POST", url:"/functions/validamatricula.php", data:{"matricula" : $("#matricula").val()+""}})
			.done(function(data) {
				if(data==="1"){
					$("#matricula").parent(".input").css({'color':'red','background':'#fff'});
					document.getElementsByClassName("err")[0].innerHTML = "";
				}else{
					$("#matricula").parent(".input").css({'color':'red','background':'#ffb0b0'});
					document.getElementsByClassName("err")[0].innerHTML = "A matr&iacute;cula informada n&atilde;o &eacute; v&aacute;lida.";
					$("#matricula").focus();
				}
			});
	});
	$("#senha").blur(function() {
		if($(this).val().length<8){
			$("#senha").parent(".input").css({'background':'#ffb0b0'});
		}else{
			$("#senha").parent(".input").css({'background':'#fff'});
		}
	});
	$("#confsenha").blur(function() {
		if($(this).val()!==$("#senha").val()){
			$("#confsenha").parent(".input").css({'background':'#ffb0b0'});
		}else{
			$("#confsenha").parent(".input").css({'background':'#fff'});
		}
	});
	$("#confsenha").keyup(function() {
		if($(this).val()!==$("#senha").val()){
			$("#confsenha").parent(".input").css({'background':'#ffb0b0'});
		}else{
			$("#confsenha").parent(".input").css({'background':'#fff'});
		}
	});
	$("#cpf").blur(function() {
		var cpf = $("#cpf").val()+"";
		if(cpf.length===11){
			cpf = cpf.substring(0,3)+"."+cpf.substring(3,6)+"."+cpf.substring(6,9)+"-"+cpf.substring(9,11);
			$("#cpf").val(cpf);
			$("#cpf").parent(".input").css({'background':'#fff'});
		}else if(cpf.length===14){
			$("#cpf").parent(".input").css({'background':'#fff'});
		}else{
			$("#cpf").parent(".input").css({'background':'#ffb0b0'});
		}
	});
	$("#cpf").keyup(function() {
		var cpf = $("#cpf").val()+"";
		if(cpf.length===3){
			cpf = cpf+".";
			$("#cpf").val(cpf);
		}else
		if(cpf.length===7){
			cpf = cpf+".";
			$("#cpf").val(cpf);
		}else
		if(cpf.length===11){
			cpf = cpf+"-";
			$("#cpf").val(cpf);
		}
		if(cpf.length>=14){
			cpf = cpf.substring(0,14);
			$("#cpf").val(cpf);
			$("#cpf").parent(".input").css({'background':'#fff'});
		}
	});
	$("#telefone").blur(function() {
		var regex = /^\(\d{2}\)\d{4,5}-\d{4}$/;
		var telefone = $("#telefone").val();
		if(regex.test(telefone)){
			$("#telefone").parent(".input").css({'background':'#fff'});
		}else{
			$("#telefone").parent(".input").css({'background':'#ffb0b0'});
			$("#telefone").parent(".input").val("");
		}
	});
	$("#telefone").keyup(function(e) {
		var telefone = $("#telefone").val()+"";
		if(telefone.length===1 && e.keyCode !== 8){
			telefone = "("+telefone;
			$("#telefone").val(telefone);
		}else
		if(telefone.length===3 && e.keyCode !== 8){
			telefone = telefone+")";
			$("#telefone").val(telefone);
		}else
		if(telefone.length===8 && e.keyCode !== 8){
			telefone = telefone+"-";
			$("#telefone").val(telefone);
		}else
		if(telefone.length === 2){
			try {
				parseInt(telefone);
				telefone = "("+telefone+")";
				$("#telefone").val(telefone);
			}catch(err){
				
			}
		}else
		if(telefone.length === 4){
			var lastChar = telefone.substring(telefone.length-1,telefone.length);
			telefone = telefone.substring(0,telefone.length-1)+")"+lastChar;
			$("#telefone").val(telefone);
		}else
		if(telefone.length===9){
			var lastChar = telefone.substring(telefone.length-1,telefone.length);
			telefone = telefone.substring(0,telefone.length-1)+"-"+lastChar;
			$("#telefone").val(telefone);
		}
		if(telefone.length===13){
			telefone = telefone.replace("(","");
			telefone = telefone.replace(")","");
			telefone = telefone.replace("-","");
			telefone = "("+telefone.substring(0,2)+")"+telefone.substring(2,6)+"-"+telefone.substring(6,10);
			$("#telefone").val(telefone);
			$("#telefone").parent(".input").css({'background':'#fff'});
		}
		if(telefone.length===14){
			telefone = telefone.replace("(","");
			telefone = telefone.replace(")","");
			telefone = telefone.replace("-","");
			telefone = "("+telefone.substring(0,2)+")"+telefone.substring(2,7)+"-"+telefone.substring(7,11);
			$("#telefone").val(telefone);
			$("#telefone").parent(".input").css({'background':'#fff'});
		}
		if(telefone.length>14){
			telefone = telefone.substring(0,14)
			$("#telefone").val(telefone);
			$("#telefone").parent(".input").css({'background':'#fff'});
		}
	});
	$("#celular").blur(function() {
		var regex = /^\(\d{2}\)\d{4,5}-\d{4}$/;
		var celular = $("#celular").val();
		if(regex.test(celular)){
			$("#celular").parent(".input").css({'background':'#fff'});
		}else{
			$("#celular").parent(".input").css({'background':'#ffb0b0'});
		}
	});
	$("#celular").keyup(function() {
		var celular = $("#celular").val()+"";
		if(celular.length===1){
			celular = "("+celular;
			$("#celular").val(celular);
		}else
		if(celular.length===3){
			celular = celular+")";
			$("#celular").val(celular);
		}else
		if(celular.length===8){
			celular = celular+"-";
			$("#celular").val(celular);
		}
		if(celular.length===13){
			celular = celular.replace("(","");
			celular = celular.replace(")","");
			celular = celular.replace("-","");
			celular = "("+celular.substring(0,2)+")"+celular.substring(2,6)+"-"+celular.substring(6,10);
			$("#celular").val(celular);
			$("#celular").parent(".input").css({'background':'#fff'});
		}
		if(celular.length===14){
			celular = celular.replace("(","");
			celular = celular.replace(")","");
			celular = celular.replace("-","");
			celular = "("+celular.substring(0,2)+")"+celular.substring(2,7)+"-"+celular.substring(7,11);
			$("#celular").val(celular);
			$("#celular").parent(".input").css({'background':'#fff'});
		}
		if(celular.length>14){
			celular = celular.substring(0,14)
			$("#celular").val(celular);
			$("#celular").parent(".input").css({'background':'#fff'});
		}
	});
	$("#dataNasc").parent()
	.mouseenter(function(){
		$("span[class='tooltiptext']").css({"visibility":"visible"});
	}).mouseleave(function(){
		$("span[class='tooltiptext']").css({"visibility":"hidden"});
	});
});