var Access = {ListItems: {}};
Access.ListItems.enableSortable = function() {

    $('#block-access .access-item').sortable({
        'group': 'no-drop'
    });
    $('#methods').sortable({
        'group': 'no-drop',
        drop: false,
        isValidTarget: function(item, container) {
            return !(container.target.parent().find('.list-group-item[data-controller="' + item.data('controller') + '"][data-method="' + item.data('method') + '"]').length >= 1);
        },
        onDragStart: function(item, container, _super) {
            item.clone().insertAfter(item);
            _super(item);
        },
        onCancel: function(item, container, _super) {
            $.log('cancel');
            item.remove();
        },
        onDrop: function(item) {
            if(!item || !item.is(':visible')) {
                return;
            }
            item.removeClass("dragged").removeAttr("style");
            $("body").removeClass("dragging");
            item.append('<i class="btn-remove-item text-danger fa fa-times-circle pull-right"></i>')
            $.log('drop', item);
        }
    });
};
$(document).ready(function() {
    Access.ListItems.enableSortable();
    $('#controller').on('change', function() {
        var controller = $(this).val();
        $.getJSON(hostPath + 'admin/access/get-methods', {'controller': controller}, function(result, status) {
            $('#methods').html('');
            $.each(result.methods, function(controller, methods) {
                $.each(methods, function(method) {
                    $('#methods').append('<li data-controller="' + controller.replace('\\', '|').replace('\\', '|') + '" data-method="' + method + '" class="list-group-item">' + controller + '::' + method + '</li>');
                });
            });
            Access.ListItems.enableSortable();
        });
    });

    $('#btn-save-new-access').on('click', function() {
        $('#md-new-access').modal('hide');
        $('#block-access').append('<div class="panel panel-default"><div class="panel-heading">' + $('#md-new-access').find(':input[name=name]').val() + '</div><div class="panel-body"><ol class="access-item col-xs-6"></ol><ol class="access-item col-xs-6"></ol></div></div>');
        Access.ListItems.enableSortable();
        return false;
    });

    $('body').on('click', '.btn-remove-item', function() {
        $(this).parent().remove();
        return false;
    });

});