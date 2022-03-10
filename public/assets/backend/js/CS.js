
function verification()
{
	var a=document.getElementById('name');
	veri_name=a.value;
	if(veri_name==" ")
		alert("Nom invalide");
	var b=document.getElementById('user');
	veri_user=b.value;
	if(veri_name==" ")
		alert("Prénom invalide");
	var b=document.getElementById('pass');
	if(b.value.length<8)
		formulaire.reset();
	for (let i = 0; i < 1; i++) {
		var a_prenom=a.value.substring(0, a.value.indexOf('.'));
		for (let j = 0; j < 1; j++) {
			var a_nom=a.value.substring(a.value.indexOf('.')+1, a.value.indexOf('@'));
		}
	}
	alert("Bienvenu "+a_prenom+" "+a_nom);
	formulaire.submit();
}