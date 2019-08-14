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

function sql_details(nome_master,nome_slave,tipo) {
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

function sql_update(nome_master,nome_slave,tipo) {

    const nome = (nome_master != "null" ? nome_master : nome_slave);
    let query = '';
    //compongo oggetto per update
    entity_selected = {
        nome: nome,
        type: tipo,
        entity_definition_master: (nome_master != "null" ? data_compare.find(x => x.entity_master === nome).entity_definition_master : ""),
        entity_definition_slave: (nome_slave != "null" ? data_compare.find(x => x.entity_slave === nome).entity_definition_slave : ""),
        //capisco se devo inserire / cancellare / modificare
        master_to_slave_action: (nome_master == "null" ? "drop" : nome_slave == "null" ? "create" : "alter"),
        slave_to_master_action: (nome_master == "null" ? "create" : nome_slave == "null" ? "drop" : "alter")
    };

    if (entity_selected.entity_definition_slave === null || entity_selected.entity_definition_slave === '') { // inserimento diretto
        query = entity_selected.entity_definition_master;
        executeQuery(query);
    } else {

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
            callback: (result) => {
                if (result) {
                    if (entity_selected.entity_definition_master === null || entity_selected.entity_definition_master === '') { // cancellazione
                        query = 'drop ' + entity_selected.type + " IF EXISTS " + entity_selected.nome;
                        executeQuery(query);
                    } else if (entity_selected.type === 'view' || entity_selected.type === 'procedure' || entity_selected.type === 'function') {
                        executeQuery('drop ' + entity_selected.type + " IF EXISTS " + entity_selected.nome);
                        setTimeout(() => executeQuery(entity_selected.entity_definition_master), 500);
                    } else { // aggiornamento tabelle
                        alterTable(entity_selected);
                    }
                    console.log(query);
                }
            }
        });
    }
}

function alterTable(entity_selected) {
    const {entity_definition_master: master, entity_definition_slave: slave, nome } = entity_selected;
    let masterRows = master.split('\n');
    let slaveRows = slave.split('\n');
    masterRows = masterRows.map(item => {
        if (item.slice(-1) === ',') {
            return item.substring(0, item.length - 1);
        }
        return item;
    });
    slaveRows = slaveRows.map(item => {
        if (item.slice(-1) === ',') {
            return item.substring(0, item.length - 1);
        }
        return item;
    });
    // copntrollo colonne mancanti
    masterRows.map(mr => {
       const columnName = mr.split(' ')[2];
       if (slaveRows.filter(s => s.indexOf(columnName) > -1).length === 0 && slaveRows.filter(s => s === mr).length === 0) { // non esiste
            console.log(`alter table ${nome} add ${mr}`);
            executeQuery(`alter table ${nome} add ${mr}`);
       } else if (slaveRows.filter(s => s.indexOf(columnName) > -1).length > 0 && slaveRows.filter(s => s === mr).length === 0) { // esiste
           console.log(`alter table ${nome} modify ${mr}`);
           executeQuery(`alter table ${nome} modify ${mr}`);
       }
    });
    // controllo colonne in esubero
    slaveRows.map(sr => {
        const columnName = sr.split(' ')[2];
        if (masterRows.filter(s => s.indexOf(columnName) > -1).length === 0 && masterRows.filter(m => m === sr).length === 0) {
            console.log(`alter table ${nome} drop column ${columnName}`);
            executeQuery(`alter table ${nome} drop column ${columnName}`);
        }
    });

}

function executeQuery(query) {
    $.get("FD_UpdateEntity.php?id_to=" + $('#slave').val() + "&query=" + encodeURIComponent(query) + "&token=AGGIORNA_CAZZO",
        function(responce) {
            try {
                if (responce.indexOf("error")>-1) alert(JSON.parse(responce).error);
                else
                {
                    $('#sql_detail').modal('hide');
                    get_data_compare();
                }
            } catch(err) {
                alert(err.message);
            }
        }
    ).fail(function() {
        alert("Errore durante l'aggiornamento del DB !");
    });
 }

function get_data_compare() {
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
                                "<i class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></i></button>\n" +
                                "<button type=\"button\" class=\"waves-effect\" data-toggle=\"modal\" data-target=\"#sql_update\" class=\"btn btn-secondary\" onclick=\"sql_update('"+responce[i]["entity_master"]+"','"+responce[i]["entity_slave"]+"','"+responce[i]["type"]+"')\" >\n" +
                                "<i class=\"glyphicon glyphicon-play\" aria-hidden=\"true\"></i></button>\n" +
                                "<button type=\"button\" class=\"waves-effect\" data-toggle=\"modal\" data-target=\"#get_data_compare\" class=\"btn btn-secondary\" onclick=\"get_data_compare()\" >\n" +
                                "<i class=\"glyphicon glyphicon-refresh\" aria-hidden=\"true\"></i></button></td>\n" +
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

