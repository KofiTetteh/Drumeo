$(document).ready(function(){

   $(document).on('click', '.product', function(e){
            e.preventDefault();
           var id = $(this).data('id');  
               $.ajax({
                    url:        'http://localhost/auto/bootstrap/ajax/getproduct.php',
                    data:       {id:id},
                    type:       'POST',   
                    success: function(jd){
                       $('.showproduct').empty().html(jd); 
                    }
             });
            

               
        });
});