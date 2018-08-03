 $(document).ready(function () {
     "use strict";

 });


function refreshData(){
     $.get("FD_SqlCompare.php",
         function(result){
             //location.reload();
             
         }
     ).fail(function() {
         alert("Errore durante l'aggiornamento dei dati !");
     });
}

function reset_password_demo()
{
    //controllo campi
    if($('#nuova_password').val() == "" || $('#nuova_password').val() == undefined || $('#nuova_password').val() == null ||
        $('#conferma_password').val() == "" || $('#conferma_password').val() == undefined || $('#conferma_password').val() == null
    ){
        alert("Compila le 2 password !");
        return;
    }

    if($('#nuova_password').val() != $('#conferma_password').val()){
        alert("Le 2 password non corrispondono !");
        return;
    }

    $.get("https://demo.costofacile.it/BackEnd/FD_DataServiceGatewayCrypt.php?gest=3&type=1"
            + "&process=" + encodeURIComponent("w8gK1DHD3qcB9G1OlMH4QB923G+qu+xDPNwLG0QIkXAtWy0tSVYtWy1cEnfTlNPYIED49E3TSCXpXxncI6HNwb2WdDnrLiqkkg@@")
            + "&params='" + $('#nuova_password').val() +"'"
            + "&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IklVR0dpTnRZWG5WWkVkM0pGVlo1ODBWRkgi.sfZbCQ4i-HCJfFGRdyBDsd0tmin5GTVQOlgPcHR2bkg",
        function(responce)
        {
            if(responce.indexOf("error") > -1) alert("Errore durante il reset password !");
            else {
                $('#nuova_password').val("");
                $('#conferma_password').val("");
                $('#passwordModal').modal('hide');
                alert("Password resettata !");
            }
        }
    ).fail(function() {
        alert("Errore durante il reset password !");
    });
}

function checkServiziCliente(id,descrizione,data_inizio,data_fine)
{
    $('#serviceModalTitle').html('Stato servizi di "<b>' + descrizione + '</b>"');
    $('#data_inizio').val(moment(data_inizio).locale("it"));
    $('#data_fine').val(moment(data_fine).locale("it"));

    //prendo lo stato dei servizi del cliente selezionato
    $.get("../BackEnd/FD_DataServiceGatewayCrypt.php?gest=3&type=1"
            + "&process=" + encodeURIComponent("jDo5KWa6Ic3KwvC0Tv0ZJmEbdZOn6+ytA7jPHYrp10ItWy0tSVYtWy3e/Kb401Sifu1j3Zd4rZOb+elfWgEM/03k3CwssyGW+w@@")
            + "&params=" + id
            + "&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IkZhZlYzT3NIejVBbGpiVElsM3c3WDM1TzMi.v1kcK8C09sEdUBhKZalcZ8Kb0ABa6iKvoa8FzZFW8KA",
        function(responce)
        {
            if(responce.indexOf("error") > -1) alert("Errore durante l'estrazione dei servizi del cliente !");
            else {

                var table = "";
                responce = JSON.parse(responce);
                table = "<table class=\"table table-striped\">\n" +
                    "     <thead>\n" +
                    "       <tr>\n" +
                    "         <th scope=\"col\">#</th>\n" +
                    "         <th scope=\"col\">Servizio</th>\n" +
                    "         <th scope=\"col\">Prezzo / Mese</th>\n" +
                    "         <th scope=\"col\">Attivo</th>\n" +
                    "       </tr>\n" +
                    "     </thead>\n" +
                    "     <tbody>";

                for(var i=0;i<responce.recordset.length;i++)
                {
                    table += "<tr>\n" +
                        " <th scope=\"row\">"+responce.recordset[i]["id"]+"</th>\n" +
                        "  <td>"+responce.recordset[i]["descrizione"]+"</td>\n" +
                        "  <td align='center'>€"+parseFloat(responce.recordset[i]["prezzo"]).toFixed(2)+"</td>\n" +
                        "  <td align='center'><input type=\"checkbox\" "+(responce.recordset[i]["Attivo"] == 1 ? 'checked' : '' )+" disabled='true'></td>\n" +
                        " </tr>";
                }

                table += "</tbody></table>";

                $('#serviceModalTable').html(table);
            }
        }
    ).fail(function() {
        alert("Errore durante l'estrazione dei servizi del cliente !");
    });
}
