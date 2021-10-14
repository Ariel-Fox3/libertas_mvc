String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

$.extend({
    redirectPost: function(location, args) {
        var form = '';
        $.each( args, function( key, value ) {
            value = value.split('"').join('\"')
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
    }
});

$( document ).ajaxSend(function() {
    console.log('starting ajax...');
    $('#loading_ajax').addClass('show');
});

$(document).ajaxSuccess(function() {
    console.log('ajax completed.');
    $('#loading_ajax').removeClass('show');
});

function renameFile(originalFile, newName) {
    return new File([originalFile], newName, {
        type: originalFile.type,
        lastModified: originalFile.lastModified,
    });
}

var Constants = {
    PLUGIN_NAME: 'timer',
    TIMER_RUNNING: 'running',
    TIMER_PAUSED: 'paused',
    TIMER_REMOVED: 'removed',
    DAYINSECONDS: 86400
};

function _secondsToTimeObj(totalSeconds) {
    var totalMinutes;
    totalSeconds = totalSeconds || 0;
    totalMinutes = Math.floor(totalSeconds / 60);
    return {
        days: totalSeconds >= Constants.DAYINSECONDS ?
            Math.floor(totalSeconds / Constants.DAYINSECONDS) : 0,
        hours: totalSeconds >= 3600 ?
            Math.floor(totalSeconds % Constants.DAYINSECONDS / 3600) : 0,
        totalMinutes: totalMinutes,
        minutes: totalSeconds >= 60 ?
            Math.floor(totalSeconds % 3600 / 60) : totalMinutes,
        seconds: totalSeconds % 60,
        totalSeconds: totalSeconds
    };
}

function _paddedValue(num) {
    num = parseInt(num, 10);
    return (num < 10 && '0') + num;
}

function secondsToFormattedTime(seconds, formattedTime) {
    var timeObj = _secondsToTimeObj(seconds);
    var formatDef = [
        { identifier: '%d', value: timeObj.days },
        { identifier: '%h', value: timeObj.hours },
        { identifier: '%m', value: timeObj.minutes },
        { identifier: '%s', value: timeObj.seconds },
        { identifier: '%g', value: timeObj.totalMinutes },
        { identifier: '%t', value: timeObj.totalSeconds },
        { identifier: '%D', value: _paddedValue(timeObj.days) },
        { identifier: '%H', value: _paddedValue(timeObj.hours) },
        { identifier: '%M', value: _paddedValue(timeObj.minutes) },
        { identifier: '%S', value: _paddedValue(timeObj.seconds) },
        { identifier: '%G', value: _paddedValue(timeObj.totalMinutes) },
        { identifier: '%T', value: _paddedValue(timeObj.totalSeconds) }
    ];

    // Use `for` loop to support ie8 after transpilation
    for (var i = 0; i < formatDef.length; i++) {
        formattedTime = formattedTime.replace(formatDef[i].identifier, formatDef[i].value);
    }

    return formattedTime;
}

function prettyTimeToSeconds(editedTime) {
    var arr, time;

    if (editedTime.indexOf('sec') > 0) {
        time = Number(editedTime.replace(/\ssec/g, ''));
    } else if (editedTime.indexOf('min') > 0) {
        editedTime = editedTime.replace(/\smin/g, '');
        arr = editedTime.split(':');
        time = Number(arr[0] * 60) + Number(arr[1]);
    } else if (editedTime.match(/\d{1,2}:\d{2}:\d{2}:\d{2}/)) {
        arr = editedTime.split(':');
        time = Number(arr[0] * Constants.DAYINSECONDS) + Number(arr[1] * 3600) +
            Number(arr[2] * 60) + Number(arr[3]);
    } else if (editedTime.match(/\d{1,2}:\d{2}:\d{2}/)) {
        arr = editedTime.split(':');
        time = Number(arr[0] * 3600) + Number(arr[1] * 60) + Number(arr[2]);
    }

    return time;
}

function confirmar(color, message, small, btn_yes, btn_no, onConfirm, onDecline) {
    var fClose = function() {
        modal.modal("hide");
    };
    var $this = this;
    var modal = $('.modal.modal-alert');

    color = color != null ? color : 'danger';
    color = color == 'danger' ? 'error' : color;

    var dangerMode = color == 'error' || color == 'warning' ? true : false;

    // icon = icon != null ? icon : 'fas fa-exclamation-triangle'; //OBSOLETE

    small = small != null ? small : '';

    var text = small != '' ? small : message;
    var title = small != '' ? message : '';


    btn_yes = btn_yes != null ? btn_yes : 'Ok';
    btn_no = btn_no != null ? btn_no : null;

    onConfirm = onConfirm != null ? onConfirm : function() {};
    onDecline = onDecline != null ? onDecline : function() {};

    Swal.fire({
        title: title,
        html: text,
        icon: color,
        confirmButtonText: btn_yes,
        denyButtonText: btn_no,
        showDenyButton: btn_no == null ? false : true,
        position: 'top'
        
    }).then(function (response) {
        if (response.isConfirmed) {
            onConfirm();
        } else {
            onDecline();
        }
    });

    // var classes_cores = '';
    // switch (color) {
    //     case 'danger':
    //         classes_cores = 'bg-danger text-white';
    //         break;
    //     case 'info':
    //         classes_cores = 'bg-info text-white';
    //         break;
    //     case 'success':
    //         classes_cores = 'bg-success text-white';
    //         break;
    //     case 'warning':
    //         classes_cores = 'bg-warning text-black';
    //         break;
    // }

    // $(modal).find('.modal-body').attr('class', 'modal-body text-center ' + classes_cores);
    // $(modal).find('i').attr('class', icon);
    // $(modal).find('p').html(message);
    // $(modal).find('small').html(small);

    // $(modal).find('#yes').text(btn_yes);
    // $(modal).find('#no').text(btn_no).show();
    // $(modal).find("#yes").unbind().one('click', function() {
    //     onConfirm();
    //     fClose();
    // });
    // $(modal).find("#no").unbind().one("click", function() {
    //     onDecline();
    //     fClose();
    // });

    // if (btn_no == null) {
    //     $(modal).find('#no').hide();
    // }

    // modal.modal({
    //     backdrop: false,
    //     show: true
    // });
}

function notifyAfterReload(tipo) {
    var color, icon, message;
    switch (tipo) {
        case 'cancelar-atividade':
            color = 'warning';
            icon = 'fas fa-check';
            message = 'Atividade cancelada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-atividade':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova atividade criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'editar-atividade':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Atividade alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'finalizar-atividade':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Atividade finalizada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova tarefa criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Tarefa alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-comment-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Comentário adicionado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-pagina':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova página criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'editar-pagina':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Página alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'enviar-arquivo':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Arquivo enviado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-arquivo':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Arquivo excluído com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'ativar-pagina':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Página ativada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'desativar-pagina':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Página desativada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-categoria':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova categoria criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-categoria':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Categoria alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-categoria':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Categoria excluída com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova notícia criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notícia alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'destaque-on-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notícia destacada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'destaque-off-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Removido destaque da notícia com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notícia excluída com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'ativar-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notícia ativada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'desativar-noticia':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notícia desativada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-usuario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Novo usuário criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-usuario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Usuário alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-usuario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Usuário excluído com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'ativar-usuario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Usuário ativado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'desativar-usuario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Usuário desativado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Novo cliente criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Cliente alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Cliente excluído com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-senha-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova senha de cliente cadastrada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-senha-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Senha de cliente alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-senha-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Senha de cliente excluída com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-telefone-cliente':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Telefone cadastrado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-solicitacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova solicitação criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-solicitacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Solicitação alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-solicitacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Solicitação excluída com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'finalizar-solicitacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Solicitação finalizada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-formulario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Novo formulário criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-formulario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Formulário alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-formulario':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Formulário excluído com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-grupo':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Novo grupo criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-grupo':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Grupo alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'novo-status-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Novo status de tarefas criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-status-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Status de tarefas alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-responsavel-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Responsável da tarefa alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-status-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Status da tarefa alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'erro-alterar-status-job':
            color = 'error';
            icon = 'fas fa-exclamation-triangle';
            let title = 'Erro';
            message = 'Ocorreu um erro ao alterar o status da tarefa.';
            if ($.cookie('pendent_notify_erro') != null) {
                title = message;
                message = $.cookie('pendent_notify_erro');
                $.removeCookie('pendent_notify_erro');
            }
            showNotification(color, message, title);
            break;
        case 'novo-contrato':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Contrato de cliente criado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'editar-contrato':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Contrato de cliente alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'editar-entrega':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Entrega alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-entrega':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova entrega criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-roteiro-job':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Grupo ativo alterado com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-foto':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Imagem alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'finalizar-tarefa':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Tarefa finalizada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'reabrir-tarefa':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Tarefa reaberta com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'excluir-entrega':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Entrega excluída com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'agendar-entrega':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Entrega agendada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'reiniciar-entregas':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Entrega reiniciada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-reuniao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Nova reunião criada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'update-reuniao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Reunião atualizada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'finalizar-reuniao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Reunião finalizada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'reiniciar-entregas':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Entrega reiniciada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-plataforma':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Plataforma de agendamento adicionada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-plataforma':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Plataforma de agendamento alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'nova-notificacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notificação geral adicionada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
        case 'alterar-notificacao':
            color = 'success';
            icon = 'fas fa-check';
            message = 'Notificação geral alterada com sucesso.';
            showNotification(color, message, 'Sucesso');
            break;
                
    }


}

function showNotification(color, message, title, position = null, close = null, progress = null, newestOnTop = null, duration = null) {
    color = color != null ? color : 'info';
    var type = null;
    switch (color) {
        case 'info':
            type = 'default'
        break;
        case 'error':
            type = 'danger'
        break;
        default:
            type = color;
        break;
    }
    // position = position != null ? position : 'toast-bottom-right';
    if (position == null) {
        var pX = 'right';
        var pY = 'bottom';
    } else {
        var pX = position['x'];
        var pY = position['y'];
    }
    close = close != null ? close : true;

    progress = progress != null ? progress : true; //OBSOLETE
    newestOnTop = newestOnTop != null ? newestOnTop : true; //OBSOLETE

    duration = duration != null ? duration : 5000;
    var ripple = true;

    var msg = title + "<br>" + message;


    // $.notify({
    //   icon: icon,
    //   message: message
    // }, {
    //   type: color,
    //   timer: 1000,
    //   delay: 5000,
    //   showProgressbar: false,
    //   placement: {
    //     from: from,
    //     align: align
    //   }
    // });

    // toastr[color](message, title, {
    //     positionClass: position,
    //     closeButton: close,
    //     progressBar: progress,
    //     newestOnTop: newestOnTop,
    //     timeOut: duration
    // });

    window.notyf.open({
        type: type,
        message: msg,
        duration: duration,
        ripple: ripple,
        dismissible: close,
        position: {
            x: pX,
            y: pY
        }
    });
}

function getExt(arquivo) {
    let spl = arquivo.split('.');
    let ext = spl[spl.length - 1];
    /*
      far fa-image
      fas fa-video
      fas fa-file-archive
      fas fa-file
      fas fa-external-link-square-alt
    */
    return ext;

}

function scrollFunction() {
    var btn = $('.back-to-top');
    if (document.body.scrollTop > 580 || document.documentElement.scrollTop > 580) {
        btn.addClass('on');
        btn.removeClass('off');
    } else {
        btn.addClass('off');
        btn.removeClass('on');
    }
}

function scrollTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    $(this).parent().addClass('off').removeClass('on');
}

function createTreeView(element, idusuario = null) {
    var data = new Object();
    data.id = idusuario;
    if (idusuario !== null) {
        data.tipo = 'usuario';
    }
    $.post('permissao/ajax/ajax_get_permissoes.php', data, function(res) {
        var obj = JSON.parse(res);
        var tree = new Array();
        for (let el of Object.keys(obj)) {
            var o = new Object();
            o.text = obj[el].text;
            o.href = obj[el].href;
            if (obj[el].ativo_user == '1') {
                o.state = new Object();
                o.state.checked = true;
            }
            // o.nodes = obj[el].nodes;
            o.nodes = [];

            function createNode(aux_obj, obj_to_push) {
                let o2 = new Object();
                o2.text = aux_obj.text;
                o2.href = aux_obj.href;
                if (aux_obj.idpai != null) {
                    let state = new Object();
                    state.checked = (aux_obj.ativo_user == '1') ? true : false;
                    state.disabled = (obj[el].ativo_user == '1') ? false : true;
                    state.expanded = false;
                    state.selected = false;
                    o2.state = state;
                }
                obj_to_push.push(o2);
            }

            for (let e = 0; e < obj[el].nodes.length; e++) {
                createNode(obj[el].nodes[e], o.nodes);
                if (obj[el].nodes[e].hasOwnProperty('nodes')) {
                    for (let h = 0; h < obj[el].nodes[e].nodes.length; h++) {
                        if (!o.nodes[e].hasOwnProperty('nodes')) o.nodes[e].nodes = [];
                        createNode(obj[el].nodes[e].nodes[h], o.nodes[e].nodes);
                    }
                }
            }

            tree.push(o);
        }

        $(element).treeview({
            data: tree,
            levels: 3,
            multiSelect: true,
            showCheckbox: true,
            highlightSelected: false,
            onNodeChecked: function(event, data) {
                let nodes = data.nodes;
                if (Array.isArray(nodes)) {
                    for (let i = 0; i < nodes.length; i++) {
                        $(element).treeview('enableNode', nodes[i].nodeId);
                    }
                }
            },
            onNodeUnchecked: function(event, data) {
                let nodes = data.nodes;
                if (Array.isArray(nodes)) {
                    for (let i = 0; i < nodes.length; i++) {
                        $(element).treeview('disableNode', nodes[i].nodeId);
                    }
                }
            }
        });

    });
}

function createShareUrl(desc, tipo) {
    $.post('../func/create_share_url.php', {
        desc: desc,
        tipo: tipo
    }, function(url) {
        confirmar('success', 'Link de compartilhamento criado', 'O link de compartilhamento é:<br> <b>' + url + '</b>');
    });
}

var editors = [];

function getEditors() {
    return editors;
}

var customUploadProgress = function() {
    var xhr = new window.XMLHttpRequest();
    // Handle progress
    //Upload progress
    xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            //Do something with upload progress
            if (percentComplete < 1) {
                $('#load_custom').css('display', 'block').css('width', (percentComplete * 100) + '%');
            } else {
                $('#load_custom').css('width', '100%');
                setTimeout(function() {
                    $('#load_custom').css('transition', '.5s ease').css('width', '0%');
                    setTimeout(function() {
                        $('#load_custom').css('display', 'none').css('transition', '.2s ease');
                    }, 500);
                }, 3500);
            }
        }
    }, false);

    return xhr;
}

$(document).ready(function() {

    $('#inputSemCache').change(function() {
        var ativo = '0';
        if ($(this).prop('checked')) {
            ativo = '1'
        }

        $.post('post/sem_cache.php', {
            ativo: ativo
        }, function(res) {
            if (res != '') {
                console.log(res);
            }
        })
    })

    $('#inputManutencao').change(function() {
        var ativo = '0';
        if ($(this).prop('checked')) {
            ativo = '1'
        }

        confirmar('question', 'Você tem certeza?', null, 'Sim', 'Não', function () {
            $.post('post/manutencao.php', {
                ativo: ativo
            }, function(res) {
                if (res != '') {
                    console.log(res);
                } else {
                    location.reload();
                }
            });
        }, function () {
            location.reload();
        });

    })

    // $('#input-search-header').keyup(function(e) {
    //     e.preventDefault();
    //     $(this).autocomplete({
    //         minLength: 1,
    //         source: function(request, response, e) {
    //             $.post('post/search_header.php', {
    //                 term: $("#input-search-header").val()
    //             }, function(data) {
    //                 response(JSON.parse(data));
    //             });
    //         },
    //         select: function(event, ui) {
    //             $("#input-search-header").val(ui.item.nome);
    //             location.href = "?pg=comments&lc=job&id=" + ui.item.id;
    //             return false;
    //         }
    //     })
    //     .autocomplete("instance")._renderItem = function(ul, item) {
    //         return $("<li>")
    //             .append(`<div class="w-100"><b class="pl-2">${item.nome}</b><small class="pl-2">${item.tipo}</small><br><small class="pl-3">Código: ${item.codigo} <span class="pl-3">Referência: ${item.ref}</span></small></div>`)
    //             .appendTo(ul);
    //     };
    // });

    $('.sidebar-toggle').click(function() {
        if ($.cookie('showMenuBeto') == null || $.cookie('showMenuBeto') == 'true') {
            $.cookie('showMenuBeto', 'false');
        } else {
            $.cookie('showMenuBeto', 'true');
        }
    });


    if (!window.Quill) {
        return $(".quill-editor,.quill-toolbar").remove();
    }

    var Embed = Quill.import('blots/block/embed');
    class Hr extends Embed {
        static create(value) {
            let node = super.create(value);
            // give it some margin
            node.setAttribute('style', "height:0px; margin-top:30px; margin-bottom:30px; position: relative;");
            return node;
        }
    }
    Hr.blotName = 'hr'; //now you can use .ql-hr classname in your toolbar
    Hr.className = 'my-hr';
    Hr.tagName = 'hr';

    var customHrHandler = function(){
        // get the position of the cursor
        var range = this.quill.getSelection();
        if (range) {
            // insert the <hr> where the cursor is
            this.quill.insertEmbed(range.index,"hr","null");
            this.quill.setSelection(range.index + 1, 0);
        }
    }

    Quill.register({
        'formats/hr': Hr
    });

    var clearfix = $('.clearfix');
    for (var i = 0; i < clearfix.length; i++) {
        let atual_cl = $(clearfix[i]);
        let editor = $(atual_cl).find('.quill-editor');
        let toolbar = $(atual_cl).find('.quill-toolbar');
        // console.log(editor);
        let ed = new Quill(editor[0], {
            modules: {
                toolbar: { 
                    container: toolbar[0],
                    handlers: {
                        'hr': customHrHandler
                    }
                }
            },
            placeholder: "Digite aqui...",
            theme: "snow"
        });

        function loop() {
            // let html = $(ed.container).children().html();
            let html = $(atual_cl).find('.ql-editor').html();
            // let clearfix = $(ed.container).parent()
            let n;
            if (atual_cl.attr('data-name')) {
                n = atual_cl.attr('data-name');
            } else {
                n = atual_cl.attr('name');
                atual_cl.attr('data-name', n).removeAttr('name');
            }
            let i = atual_cl.find('input[name="' + n + '"]');
            if (i.length == 0) {
                i = $(`<input type="text" class="d-none" name="${n}">`);
                atual_cl.append(i);
            }
            i.val('');
            i.val(html);
            $(i).trigger('change');
        }
        ed.on('editor-change', function(delta, oldDelta, source) {
            // console.log($(ed.container), atual_cl);
            loop();
            // console.log(clearfix, n, i);
        });
        loop();
        editors.push(ed);
    }

    $('.ql-editor').change(function() {
        // alert('oi');
    });

    $('#sidebar a[data-toggle="collapse"]').click(function() {
        var i = $(this).find('.fa-chevron-down');
        if (i.hasClass('fa-rotate-180')) {
            i.removeClass('fa-rotate-180');
        } else {
            i.addClass('fa-rotate-180');
        }
    });

    $(window).scroll(function() {
        scrollFunction();
    });

    if ($.cookie('pendent_notify') != null) {
        notifyAfterReload($.cookie('pendent_notify'));
        $.removeCookie('pendent_notify');
    }

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        orientation: 'bottom',
        todayHighlight: true,
        language: 'pt-BR'
    });

    $('.datepicker-up').datepicker({
        format: 'dd/mm/yyyy',
        orientation: 'top',
        todayHighlight: true,
        language: 'pt-BR'
    });

    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
    $('.bsselect').selectpicker({
        style: '',
        styleBase: 'form-control btn-selectpicker',
        dropupAuto: false,
        size: 7
    });

    $('.bsselect-status').selectpicker({
        style: '',
        styleBase: 'btn-select-status text-dark',
        dropupAuto: false,
        size: 7
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle-second="tooltip"]').tooltip();

    $('.editor-atividades, .editor-padrao').summernote({
        height: 200,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['height', ['height']],
            ['insert', ['link', 'table']],
            ['codeview']
        ],
        callbacks: {
            onInit: function(e) {
                // console.log(e.editor);
                $(e.editor).addClass('btn-outline-info');
            }
        }
    });
});