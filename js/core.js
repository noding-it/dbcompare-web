 var data_compare = [];
 
 $(document).ready(function () {
     "use strict";

     $.getJSON( "Config/config.json", function( data ) {

        var $select_master = $('#master'); 
        var $select_slave = $('#slave'); 
        $select_master.find('option').remove();  
        $select_slave.find('option').remove();  
        $.each(data,function(key, value) 
        {
            $select_master.append('<option value=' + value.id + '>' + value.db + '</option>');
            $select_slave.append('<option value=' + value.id + '>' + value.db + '</option>');
        });

    });

 });

function sql_details(nome){
    $("#exampleModalLabel").html("<b>"+nome+"</b>");
    
    $("#master_title").html(data_compare.find(x => x.entity_master === nome).master);
    $("#slave_title").html(data_compare.find(x => x.entity_master === nome).slave);

    $("#master_detail").html(data_compare.find(x => x.entity_master === nome).entity_definition_master.replace("DEFINER=``@`%`",""));
    $("#slave_detail").html(data_compare.find(x => x.entity_slave === nome).entity_definition_slave.replace("DEFINER=``@`%`",""));
}

function get_data_compare()
{

    $.get("FD_SqlCompare.php?id_master=" + $('#master').val() + "&id_slave=" + $('#slave').val(),
        function(responce)
        {
            data_compare = JSON.parse(responce);
            var table = "";
            responce = JSON.parse(responce);
            table = "<table class=\"table table-hover\">\n" +
                "     <thead>\n" +
                "       <tr>\n" +
                "         <th scope=\"col\">Nome Master</th>\n" +
                "         <th scope=\"col\">Nome Slave</th>\n" +
                "         <th scope=\"col\">Differenze</th>\n" +
                "         <th align=\"center\" scope=\"col\">Dettaglio</th>\n" +
                "       </tr>\n" +
                "     </thead>\n" +
                "     <tbody>";

            for(var i=0;i<responce.length;i++)
            {
                table += "<tr "+ (responce[i]["is_different"] == true ? 'class=\"bg-warning\"' : '') +" >\n" +
                    "  <td>"+responce[i]["entity_master"]+"</td>\n" +
                    "  <td>"+responce[i]["entity_slave"]+"</td>\n" +
                    "  <td align='center'><input type=\"checkbox\" "+(responce[i]["is_different"] == true ? 'checked' : '' )+" disabled='true'></td>\n" +
                    "  <td align='center'> \n" +
                            "<button type=\"button\" class=\"waves-effect\" data-toggle=\"modal\" data-target=\"#sql_detail\" class=\"btn btn-secondary\" onclick=\"sql_details('"+responce[i]["entity_master"]+"')\" >\n" +
                            "<i class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></i></button></td>\n" +
                    " </tr>";
            }

            table += "</tbody></table>";
            $('#tab_compare').html(table);
            $('#Tot').html("Differenze Totali: " + $('input:checkbox:checked').length);

        }
    ).fail(function() {
        alert("Errore durante l'estrazione dei dati !");
    });
}

