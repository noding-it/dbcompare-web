 var data_compare = [];
 var entity_selected = {};
 
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

function sql_details(nome_master,nome_slave,tipo){
    var nome = (nome_master != "null" ? nome_master : nome_slave);

    $("#exampleModalLabel").html("<b>"+nome+"</b>");
    
    $("#master_title").html( (nome_master != "null" ? data_compare.find(x => x.entity_master === nome).master : "") );
    $("#slave_title").html( (nome_slave != "null" ? data_compare.find(x => x.entity_slave === nome).slave : "") );

    $("#master_detail").html( (nome_master != "null" ? data_compare.find(x => x.entity_master === nome).entity_definition_master : "") );
    $("#slave_detail").html( (nome_slave != "null" ? data_compare.find(x => x.entity_slave === nome).entity_definition_slave : "") );

    //compongo oggetto per update
    entity_selected = {
        nome: nome,
        type: tipo,
        entity_definition_master: (nome_master != "null" ? data_compare.find(x => x.entity_master === nome).entity_definition_master : ""),
        entity_definition_slave: (nome_slave != "null" ? data_compare.find(x => x.entity_slave === nome).entity_definition_slave : ""),
        //capisco se devo inserire / cancellare / modificare
        master_to_slave_action: (nome_master == "null" ? "drop" : nome_slave == "null" ? "create" : "alter"),
        slave_to_master_action: (nome_master == "null" ? "create" : nome_slave == "null" ? "drop" : "alter")
    }
}

function update_db_slave()
{
    bootbox.confirm({
        message: "Sicuro di voler aggiornare il DB Slave ?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result)
            {
                var query="";
                switch (entity_selected.master_to_slave_action)
                {
                    case "create":
                        query = entity_selected.entity_definition_master;
                        break;
                    case "alter":
                        query = entity_selected.entity_definition_master.toLowerCase().replace("create ","alter ");
                        break;
                    case "drop":
                        query = "drop " + entity_selected.type + " " + entity_selected.nome + ";";
                        break;
                }

                $.get("FD_UpdateEntity.php?id_to=" + $('#slave').val() + "&query=" + encodeURIComponent(query),
                    function(responce)
                    {
                        if(responce.indexOf("error")>-1) alert(JSON.parse(responce).error);
                        else 
                        {
                            $('#sql_detail').modal('hide');
                            get_data_compare();
                        }
                    }
                ).fail(function() {
                    alert("Errore durante l'aggiornamento del DB !");
                });
            }
        }
    });
}

function update_db_master()
{

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
                            "<button type=\"button\" class=\"waves-effect\" data-toggle=\"modal\" data-target=\"#sql_detail\" class=\"btn btn-secondary\" onclick=\"sql_details('"+responce[i]["entity_master"]+"','"+responce[i]["entity_slave"]+"','"+responce[i]["type"]+"')\" >\n" +
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

