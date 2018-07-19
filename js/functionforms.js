function validacadastro(){
	var mat = $("#matricula");
	var nom = $("#nome");
	var dtn = $("#dataNasc");
	var sen = $("#senha");
	var csn = $("#confsenha");
	var ema = $("#email");
	var cpf = $("#cpf");
	var tel = $("#telefone");
	var cel = $("#celular");
	var cep = $("#cep");
	var uf  = $("#uf");
	var cid = $("#cidade");
	var bai = $("#bairro");
	var log = $("#logradouro");
	var com = $("#complemento");

	if(mat.val() === ""){
		mat.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(nom.val() === ""){
		nom.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(dtn.val() === ""){
		dtn.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(sen.val() === ""){
		sen.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(csn.val() === ""){
		csn.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(ema.val() === ""){
		ema.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(cpf.val() === ""){
		cpf.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(tel.val() === ""){
		tel.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(cel.val() === ""){
		cel.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(cep.val() === ""){
		cep.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(uf.val() === ""){
		uf.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(cid.val() === ""){
		cid.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(bai.val() === ""){
		bai.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(log.val() === ""){
		log.parent(".input").css({
								"color":"red"
								
							});
		erro = true;
	}
	if(erro){
		alert("Preencha os campos em vermelho.");
		return false;
	}else{
		return false;
	}
}