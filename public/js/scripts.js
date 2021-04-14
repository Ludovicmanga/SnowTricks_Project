function loadMore(){

    // We increment the offest value every time the button is clicked
        var offset = parseInt(document.getElementById('tricks').offset, 10);
        offset = isNaN(offset) ? 0 : offset;
        offset +=4;
        document.getElementById('tricks').offset = offset;

    // We send the information to the controller
        xhr = new XMLHttpRequest(); 

        // We insert the offset number in the url 
        var url = '{{ path("load_more_tricks", {"offset": "offset_number"}) }}'
        url = url.replace("offset_number", offset)

        // We send the url to the controller
        xhr.open('POST', url , true); 
        xhr.onload = function(){
            
            if (this.status == 200) {
                    var tricks = JSON.parse(this.responseText); 

                    // We create a row div
                    let rowElement = document.createElement('div'); 

                    // We give the class "row" to this div
                    rowElement.classList.add('row'); 
                    document.body.appendChild(rowElement); 

                    var output = ''; 

                    for(var i in tricks){
                        output = 
                        '<div>hello'+tricks[i].name+'</div>'+
                        '<div class="trick">'+
                            '<img src='+tricks[i].coverImagePath +'>'+
                        '</div>'

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

