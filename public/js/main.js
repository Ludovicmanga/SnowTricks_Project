const tricks = document.getElementsById('trick'); 

if(tricks) {
    tricks.addEventListener('click', e => {
       if(e.target.className === 'delete') {
           alert('are you sure?'); 
       }
    }); 
}