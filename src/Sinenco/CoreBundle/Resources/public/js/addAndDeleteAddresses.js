//jQuery(document).ready(function(){
        function loadAddButtonProfile(){
            // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
            var $container = $('div#sinenco_core_profile_edit_userAddress');
            
            // On ajoute un lien pour ajouter une nouvelle catégorie
            var $addLink = $('<a href="#" id="add_category" class="btn btn-default">Ajouter une catégorie</a>');
            $container.append($addLink);
            
            // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
            $addLink.click(function(e) {
                addCategory($container);
                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
            
            // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
            var index = $container.find(':input').length;
            
            // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
            if (index === 0) {
                addCategory($container);
            } else {
                // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
                $container.children('div').each(function() {
                    addDeleteLink($(this));
                });
            }
            
            // La fonction qui ajoute un formulaire Categorie
            function addCategory($container) {
                //alert ("said something.  Don't give up on me.");
                // Dans le contenu de l'attribut « data-prototype », on remplace :
                // - le texte "__name__label__" qu'il contient par le label du champ
                // - le texte "__name__" qu'il contient par le numéro du champ
                var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Catégorie n°' + (index+1))
                        .replace(/__name__/g, index));
                
                // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
                addDeleteLink($prototype);
                
                // On ajoute le prototype modifié à la fin de la balise <div>
                $container.append($prototype);
                
                // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
                index++;
            }
            
            // La fonction qui ajoute un lien de suppression d'une catégorie
            function addDeleteLink($prototype) {
                // Création du lien
                $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');
                
                // Ajout du lien
                $prototype.append($deleteLink);
                
                // Ajout du listener sur le clic du lien
                $deleteLink.click(function(e) {
                    $prototype.remove();
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            }
        };
        
        
        window.onload = function() {
            //alert ("said something.  Don't give up on me.");
            loadAddButtonProfile();
        };