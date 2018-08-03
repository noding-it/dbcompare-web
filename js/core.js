 $(document).ready(function () {
     "use strict";

     $.getJSON( "../Config/config.json", function( data ) {

        var $select_master = $('#master'); 
        var $select_slave = $('#slave'); 
        $select_master.find('option').remove();  
        $select_slave.find('option').remove();  
        $.each(data,function(key, value) 
        {
            $select_master.append('<option value=' + key + '>' + value + '</option>');
            $select_slave.append('<option value=' + key + '>' + value + '</option>');
        });

    });

 });

function get_data_compare()
{

    $.get("FD_SqlCompare.php?id_master=" + $('#master').val() + "&id_slave=" + $('#slave').val(),
        function(responce)
        {
            if(responce.indexOf("error") > -1) alert("Errore durante l'estrazione dei dati !");
            else {
                var table = "";
                responce = JSON.parse(responce);
                table = "<table class=\"table table-striped\">\n" +
                    "     <thead>\n" +
                    "       <tr>\n" +
                    "         <th scope=\"col\">Nome Master</th>\n" +
                    "         <th scope=\"col\">Nome Slave</th>\n" +
                    "         <th scope=\"col\">Differenze</th>\n" +
                    "       </tr>\n" +
                    "     </thead>\n" +
                    "     <tbody>";

                for(var i=0;i<responce.length;i++)
                {
                    table += "<tr>\n" +
                        "  <td>"+responce[i]["entity_master"]+"</td>\n" +
                        "  <td>"+responce[i]["entity_slave"]+"</td>\n" +
                        "  <td align='center'><input type=\"checkbox\" "+(responce[i]["is_different"] == true ? 'checked' : '' )+" disabled='true'></td>\n" +
                        " </tr>";
                }

                table += "</tbody></table>";

                $('#tab_compare').html(table);
            }
        }
    ).fail(function() {
        alert("Errore durante l'estrazione dei dati !");
    });
}

