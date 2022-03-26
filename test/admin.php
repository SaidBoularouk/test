
<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="UTF-8">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


	<link rel="stylesheet" type="text/css" href="css/style.css">

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body>


<form>

<div class="container-fluid p-2">
<div class="row">
  <div class="col-8">
  <h4>Entrée de fonds de caisse</h4>
  </div>
  <div class="col-4"><a href="index.html" class="link-primary">Main</a></div>
  <hr>
</div>

  <div class="row">
    <div class="col-lg-3 col-md-12 text-center">
      <h3> Montant</h3>
      <h1>0.00 €</h1>

    </div>
    <div class="col">
   
    	<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Type</th>
      <th scope="col">Montant</th>
    <!--  <th scope="col">Retraits</th>
      <th scope="col">Dépots</th>  -->
      <th scope="col">Total</th>
      <th scope="col">Note</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>



   
    </div>
  </div>
  </div>

</form>

</body>

<script type="text/javascript">
$(document).ready(function () {  
var delArray = [];
var submitArray = {};
var source_array = [];
var page = 0;
showData(page);

function showData(page){ 
  $.ajax({
    url:  "php/fetch.php",
    type:'get',
    data: {page:page},
   // cache:true,
    //contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    success: function(result) {
      var total = 0;
 
     $(result).each(function(i,vals){
      var retrait = "";
      var depot = "";
      var type="";
     
      var jsonData = jQuery.parseJSON(vals.json); //Get json metaData transaction
      montant = parseFloat(jsonData.montant); //Convert fund to float val

      /** reformat data  **/
      switch(jsonData.type){
        case "depot":
        type = "Dépot";
        depot =  Math.round(montant*100)/100;
        retrait = 0;
        break;
        case "retrait":
        type = "Retrait";
        retrait = -1*( Math.round(montant*100)/100 ) ;
        depot = 0;
        break;
      }

        total = total + depot + retrait;  // get total amount
        total = Math.round(total*100)/100; // round to 2num after virgula


      /** Change h1 color according to funds amount**/
      $('h1').text(total + " €");      
      switch (true) {
        case (total > 0):
          $('h1').css("color", "green");
          break;
        case (total < 0):
          $('h1').css("color", "red");
          break;
        case (total == 0):
          $('h1').css("color", "black");
          break;
      }
      /** end **/

      
      $('.table > tbody:last-child').append( "<tr id='tr-"+vals.id+"'>"
         + "<td scope='row'>"+ jsonData.date + "</td>" 
         + "<td scope='row'>"+type+"</td>"

         + "<td scope='row'><div class='d-flex' style='width:180px'><input class='form-control me-2' type='text'  value='"+montant+"'  disabled>"
         + "<input type='button' class='btn btn-primary btn-sm invisible' value= 'ok' ></div>"
         +"</td>"
       //  + "<td scope='col'><input class='form-control border-0' type='text' value='"+retrait+"'></td>"
       //  + "<td scope='col'><input class='form-control border-0' type='text' value='"+depot+"'></td>"
         + "<td scope='row'>"+total+"</td>"
         + "<td scope='row'>"+jsonData.note+"</td>"
         
         + "<td scope='row'>"+
         "<select class='form-select' aria-label='Default select'>"
         +"<option  value='-1' selected>--</option>"
         +"<option  value='delete'>Supprimer</option>"
         +"<option  value='update'>Modifier</option>"
         +"</select> </td>"
         + "</tr>");
   
      });

  actionFunction();     

},
    error: function (XHR, textStatus, errorThrown) { console.log(XHR.responseText)  ; }
  }); 

}

 
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() == $(document).height()) {
      page++;
      showData(page);
   }
});

  /*====================
  //=> / =>  function bloc  
  ===================*/


/** @function to delete 
** or update of Data
*/
function actionFunction(){
   $("select").on( "change", function(){
        var optionValue = $(this).val();
         let id = $(this).closest("tr").attr("id");
        switch(optionValue){
          case "delete":
            deleteItem(this, id);
          break;
          case "update":
           updateItem(this, id);
          break;

          default:
          break;
        }
      });
}
/**end del/update func **/


/** @Update func 
 ** 
*/

function updateItem(ele, id){
   var control = $("#"+id).children().find("input");
   control.prop("disabled", false);
   var btn = control.next("input[type=button]");
   btn.removeClass("invisible");

   btn.on("click", function(){
      var formData = {
      action : "update",
      id: id.replace ( /[^\d.]/g, '' ),
      montant: control.val().replace ( /[^\d.]/g, '' )
    };

  var request = $.ajax({
      type: "POST",
      url: "php/process.php",
      data: formData
    });

   request.done(function(msg) {
      console.log(msg);
      if ($.trim(msg) == "" || $.trim(msg) == null  ){ 
        $(control).prop("disabled", true);
        $(btn).fadeOut("normal", function() {
            $(btn).addClass("invisible");
            location.reload();
        });

      }

      //$("td#"+id)
    });

  request.fail(function( jqXHR, textStatus ) {
     alert( "Request failed: " + textStatus );
  });

 
 })

}
/** end update func **/



/** @Confirmation dialog func 
 ** 
*/
function confirmDialog(ele) {
  let text = "êtes-vous sûr de vouloir supprimer ce fond? \n Entrez OK pour confirmer or Cancel pour annuler.";
  if (confirm(text) == true) {
    return true;
  } else {
    $(ele).val('-1');
    return false;
  }
}
/** end confirmation func **/



/** @Delete func 
 ** 
*/

function deleteItem(ele, id){
if( confirmDialog(ele) === true ){
    var formData = {
      action : "delete",
      id: id.replace ( /[^\d.]/g, '' ),
    };

  var request = $.ajax({
      type: "POST",
      url: "php/process.php",
      data: formData
    });

   request.done(function(msg) {
      console.log(msg);
      if ($.trim(msg) == "" || $.trim(msg) == null  ){ 
        $("#"+id).fadeOut("normal", function() {
            $(this).remove();
        });
        showData(page);
      }
      //$("td#"+id)
    });

  request.fail(function( jqXHR, textStatus ) {
     alert( "Request failed: " + textStatus );
  });

}else{

}

}

});
/** end Delete function **/

</script>

</html>
