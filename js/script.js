$(document).ready( function() {
	var formMessage = $("#form-message");
	var espaceRegister = $('#espace-register');
	var listSalons = $('#list-salons');
	var sectionChatLdg = $("#section-chat legend");
	var sectionChat = $("#section-chat");
	var chatContent = $('#main-content');
	var listConnectes = $('#list-connectes');

	// Message d'erreur chat utilisateur non connecté
	var warning = $("<p class='msg-error'></p>");	

	////// ON LOAD / AUTO REFRESH //////

	recupSalons();
	recupUsers();
	recupChat();

	var loopChat = setInterval(recupChat, 10000);
	var loopSalons = setInterval(recupSalons, 600000);			
	var loopUsers = setInterval(recupUsers, 120000);


	////// REGISTER //////
	espaceRegister.on('click', function() { // one permet d'activer le bouton une seule fois. 

		// Contenu du formulaire d'engistrement (possibilité afficher en HTML et gérer display none)
		var form = "<div id='register-box'><h4>Inscription</h4><hr><form method='post' action='libs/connect.php' id='form-inscription'><label for='pseudo-register'>Pseudonyme</label><input type='text' name='pseudo' placeholder='Pseudo' id='pseudo-register'><label for='mdp-register'>Mot de passe</label><input type='password' name='mdp' placeholder='Mot de passe' id='mdp-register'><input type='submit' name='register' value='Envoyer' class='btn-perso btn-inscription'></form></div>";
		
		// Bouton s'enregister
		if (espaceRegister.find("button").attr("data-btn") === "register")
		{
			// Ajout du formulaire à la page
			espaceRegister.append(form);	
			// Transformation du bouton d'ouverture inscription en fermeture
			espaceRegister.find("button").text('Fermer');
			espaceRegister.find("button").attr("data-btn", "fermer");		
		}
		// Bouton fermer
		else if (espaceRegister.find("button").attr("data-btn") === "fermer")
		{	
			// Suppression du formulaire
			$("#register-box").remove();
			espaceRegister.find("button").text("S'enregistrer");
			espaceRegister.find("button").attr("data-btn", "register");
		}


		/************ FORMULAIRE INSCRIPTION ************/
		$("#form-inscription").on("submit", function(e){
			e.preventDefault();
			var submitChecker = true;
			var inputPseudo = $("#pseudo-register");
			var inputMdp = $("#mdp-register");
			var formInscription = $("#form-inscription");

			// Verification de la validité du mot de passe
			if(checkPassword(inputMdp.val()) === false)
			{
				// Suppression des anciens messages d'erreur
				formInscription.find('.mdp-error').remove();

				// Mise en forme messages d'erreur
				inputMdp.css("border-color", "red");
				inputMdp.after("<p class='msg-error mdp-error'>Minimum 8 caractères, une majuscule, un caractère spécial</p>");
				inputMdp.focus();

				submitChecker = false;					
			}
			else
			{
				// Suppression des anciens messages d'erreur
				formInscription.find('.mdp-error').remove();
				inputMdp.css("border-color", "#EC9454");
			}

			// Vérification de la validité du pseudo
			if(/^[a-zA-Z0-9_-]{4,14}$/.test(inputPseudo.val()) === false)
			{
				// Suppression des anciens messages d'erreur
				formInscription.find('.pseudo-error').remove();

				// Mise en forme messages d'erreur
				inputPseudo.css("border-color", "red");
				inputPseudo.after("<p class='msg-error pseudo-error'>Entre 5 et 14 caractères alphanumériques, tiret, underscore (_)</p>");
				inputPseudo.focus();
				submitChecker = false;			
			}
			else
			{
				// Suppression des anciens messages d'erreur
				formInscription.find('.pseudo-error').remove();
				inputPseudo.css("border-color", "#EC9454");
			}

			// Pas d'erreur sur la validité du pseudo et du mot de passe
			if(submitChecker === true)
			{
				// Enregistrement de l'utilisateur
				$.ajax({
					url : 'libs/connect.php',
					method : 'POST',
					dataType : 'text',		
					data : {"register" : "true",
							"pseudo" : inputPseudo.val(),
							"mdp" : inputMdp.val()},	
					success : function(data) {
						// fonction exécutée au succès de la requête
							//console.log(data);

						// Pseudonyme déjà utilisé 
						if(data == 'false')
						{
							// Suppression des anciens messages d'erreur
							formInscription.find('.pseudo-error').remove();

							// Mise en forme messages d'erreur
							inputPseudo.css("border-color", "red");
							inputPseudo.after("<p class='msg-error pseudo-error'>Pseudonyme déjà utilisé</p>");
							inputPseudo.focus();							
						}
						// Enregistrement réussi
						if(data == 'true')
						{
							document.location.reload();
						}
					},
					error : function(jqXHR, textStatus, errorThrow){
						// fonction exécutée à l'échec de la requête
						console.log(jqXHR, textStatus, errorThrow);
					},
					complete : function (data) {
						// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
							//console.log(data);
					},									
				});					
			}
		});		
	});

	////// SALON & USERS SALON //////
	listSalons.on('click', 'a', function(e) {
		e.preventDefault();
		var that = $(this);

		// Changement de salon
		$.ajax({
			url : "libs/salons.php",
			method : 'POST',	
			dataType : "text",		
			data : { "salon" : that.attr("href"),
					"nom" : that.text()},
			success : function(data) {
				// fonction exécutée au succès de la requête
				console.log(JSON.parse(data));

				var parsedData = JSON.parse(data);

				// Ajout des attributs pour cibler chaque salon
				sectionChat.attr('data-salon', parsedData.num);
				sectionChatLdg.text(parsedData.nom);
				listConnectes.find('h4').text("Connectés sur "+parsedData.nom);


				// Suppression des anciens messages du chat + affichage des nouveaux
				chatContent.html("");
				recupUsers();
				setTimeout(recupChat,200);
			},
			error : function(jqXHR, textStatus, errorThrow){
				// fonction exécutée à l'échec de la requête
				console.log(jqXHR, textStatus, errorThrow);
			},
			complete : function (data) {
				// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
					//console.log(data);
			}						
		});

		// On récupère les messages du salon
						
	});

	////// CHAT //////
	formMessage.on("submit", function(e) {
		e.preventDefault();
		var that = $(this);



		

		// Données à envoyer
		var dataString = that.serialize(); 

		$.ajax({
			url : 'libs/ajax.php',
			method : that.attr("method"),			
			data : dataString,
			success : function(data) {
				// fonction exécutée au succès de la requête
					//console.log(data);

				// Envoi du message d'erreur si l'utilisateur est non connecté				
				warning.empty();
				warning.html(data);
				warning.insertBefore(chatContent);

				// Suppression du message du textarea après envoi	
				$("#form-message textarea").val("");

				// Appel de la fonction pour afficher les messages
				recupChat();

			},
			error : function(jqXHR, textStatus, errorThrow){
				// fonction exécutée à l'échec de la requête
				console.log(jqXHR, textStatus, errorThrow);
			},
			complete : function (data) {
				// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
					//console.log(data);	
			}						
		});		
	});



});


/* ---------- FONCTIONS ---------- */

////// DISPLAY CHAT //////
function recupChat() {
	
	var datederniermessage = $(".date_mess:first").text();

	$.ajax({
		url : 'libs/ajax.php',
		method : 'POST',		
		data : {"action" : "update",
				"datemess" : datederniermessage,
				"numsalon" : $("#section-chat").attr("data-salon")},	// Paramètre de contrôle pour le service ajax.php
		complete : function (data) {
			// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
				//console.log(data);
		},
		success : function(data) {
			// fonction exécutée au succès de la requête
				//console.log(JSON.parse(data));

			// Récupération des data en JSON converti en objet javascript	
			var parsedData = JSON.parse(data);

			// Variables pour gérer le contenu des messages et la couleur des messages de l'utilisateur
			var content = "";
			var usercolor ="";

			// Boucle pour générer le contenu
			for (var i = 0; i < parsedData.length; i++) 
			{
				// Mise en forme de la date
				var dateMess = parsedData[i].date_message.substr(11);

				// if else pour gérer la couleur de l'utilisateur connecté
				if(parsedData[i].usercolor)
				{
					content += "<p><span class='hidden date_mess'>"+parsedData[i].date_message+"</span><span class='small'> ["+dateMess+"] </span>"+"<span style='color: #"+parsedData[i].usercolor+";'><strong>"+parsedData[i].pseudo+"</strong> : "+parsedData[i].message+"</span></p>";					
				}
				else
				{
					content += "<p><span class='hidden date_mess'>"+parsedData[i].date_message+"</span><span class='small'> ["+dateMess+"] </span>"+"<strong>"+parsedData[i].pseudo+"</strong> : "+parsedData[i].message+"</p>";					
				}	
			}

			// Ajout du contenu à la page
			$('#main-content').prepend(content);	

		},
		error : function(jqXHR, textStatus, errorThrow){
			// fonction exécutée à l'échec de la requête
			console.log(jqXHR, textStatus, errorThrow);
		}				
	});
}


////// DISPLAY USERS //////
function recupUsers() {
	var listConnectes = $('#list-connectes ul');

	$.ajax({
		url : 'libs/salons.php',
		method : 'POST',
		dataType : 'text',		
		data : {"action" : "listConnectes",
				"salon" : $("#section-chat").attr("data-salon")},
		success : function(data) {
			// fonction exécutée au succès de la requête
			console.log(JSON.parse(data));

			// Récupération des data en JSON converti en objet javascript	
			var parsedData = JSON.parse(data);
			var content ="";

			for(var i = 0; i < parsedData.length; i++)
			{
				content += "<li>"+parsedData[i]+"</li>";
			}


			listConnectes.html(content);
		},
		error : function(jqXHR, textStatus, errorThrow){
			// fonction exécutée à l'échec de la requête
			console.log(jqXHR, textStatus, errorThrow);
		},
		complete : function (data) {
			// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
				//console.log(data);
		},									
	});	
}

////// DISPLAY SALONS //////
function recupSalons() {
	var listSalons = $('#list-salons ul');

	$.ajax({
		url : 'libs/salons.php',
		method : 'POST',
		dataType : 'text',		
		data : {"action" : "listSalons"},
		success : function(data) {
			// fonction exécutée au succès de la requête
			//console.log(JSON.parse(data));

			// Récupération des data en JSON converti en objet javascript	
			var parsedData = JSON.parse(data);
			var content ="";

			for (var i = 0; i < parsedData.length; i++) 
			{
				content += "<li><a href='"+parsedData[i].nom.toLowerCase()+parsedData[i].id_salon+"'>"+parsedData[i].nom+"</a></li>";
			}

			listSalons.html(content);
		},
		error : function(jqXHR, textStatus, errorThrow){
			// fonction exécutée à l'échec de la requête
			console.log(jqXHR, textStatus, errorThrow);
		},
		complete : function (data) {
			// fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status
				//console.log(data);
		},									
	});	
}


// PASSWORD CHECKER
function checkPassword(mdp) 
{
   var verifMdp = true;

   if(/[A-Z]/.test(mdp) === false)
   {
   	verifMdp = false;
   } 
   if(/[a-z]/.test(mdp) === false)
   {
   	verifMdp = false;
   } 
   if(/[!@#$%^&*()\-_=+{};:,<.>]/.test(mdp) === false)
   {
   	verifMdp = false;
   } 
   if(/[0-9]/.test(mdp) === false)
   {
   	verifMdp = false;
   } 

   if(mdp.length < 8)
   {
   	verifMdp = false;
   }

   return verifMdp;
}


// // Fonction pour récupérer la date au format SQL NOW()
// function dateJStoSQL() {
// 	var tzoffset = new Date().getTimezoneOffset();
// 	tzoffset = tzoffset * 60000 ;
// 	var date;
//     date = new Date(Date.now() - tzoffset).toISOString().slice(0, 19).replace('T', ' '); 
//     return date;
// }

