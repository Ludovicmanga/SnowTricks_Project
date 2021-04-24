function loadMore(){

    // We increment the offest value every time the button is clicked
        var offset = parseInt(document.getElementById("tricks").offset, 10);
        offset = isNaN(offset) ? 0 : offset;
        offset +=4;
        document.getElementById("tricks").offset = offset;

    // We send the information to the controller
        xhr = new XMLHttpRequest(); 

        // We insert the offset number in the url 
        var url = '{{ path("load_more_tricks", {"offset": "offset_number"}) }}'; 
        url = url.replace("offset_number", offset); 

        // We send the url to the controller
        xhr.open("POST", url , true); 
        xhr.onload = function(){
            
            if (this.status === 200) {
                    var tricks = JSON.parse(this.responseText); 

                    // We create a row div
                    let rowElement = document.createElement("div"); 

                    // We give the class "row" to this div
                    rowElement.classList.add("row"); 
                    document.body.appendChild(rowElement); 

                    var output = ""; 

                    for(var i in tricks){

                        output = 
                        '<div class="trick" id="trick">'+
                            '<a href=" {{ path("trick_show", {"id": "trickid"}) }}"><img src="{{"trickcoverImagePath"}}"></a>'+
                            '<div class="trickContentContainer">'+
                                '<div class="titleContainer">'+
                                    '<h2>{{"trickname"}}</h2>'+
                                '</div>'+
                                '{% if app.user %}'+
                                    '<div class="iconContainer">'+
                                        '<div class="trashIcon icon">'+
                                            '<a onclick="return confirm("attention, cela va supprimer l\'article")" href=" {{ path("trick_delete", {"id": "trickid"}) }}" class="delete"><i class="fas fa-trash"></i></a>'+
                                        '</div>'+
                                        '<div class="penIcon icon">'+
                                            '<a href=" {{ path("trick_update", {"id": "trickid"}) }}"><i class="fas fa-pen"></i></a>'+
                                        '</div>'+
                                    '</div>'+
                                '{% endif %}'+
                            '</div>'+
                        '</div>';

                        //We replace the variables in the twig path with the actual values
                        output = output.replace(/trickid/g, tricks[i].id); 
                        output = output.replace(/trickname/g, tricks[i].name); 
                        output = output.replace(/trickcoverImagePath/g, tricks[i].coverImagePath); 
                        
                        // We create the child div
                        let divToCreate = document.createElement('div') ; 
                        document.body.appendChild(divToCreate)
                        divToCreate.innerHTML = output ;  

                        // We put the child div into the row div
                        rowElement.appendChild(divToCreate); 
                    }      
            }
        }   
        xhr.send(); 
}
