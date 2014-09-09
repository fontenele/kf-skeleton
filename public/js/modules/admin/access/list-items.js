var Access = {ListItems: {}};
Access.ListItems.enableSortable = function() {
    $('#block-access .access-item').sortable({
        'group': 'no-drop'
    });

    $('#methods').sortable({
        'group': 'no-drop',
        'drop': false,
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
            if (!item || !item.is(':visible')) {
                return;
            }
            item.removeClass("dragged").removeAttr("style");
            $("body").removeClass("dragging");
            item.find('.btn-remove-item, .access-hidden-item').remove();
            item.append('<i class="btn-remove-item text-danger fa fa-times-circle pull-right"></i>');
            item.append('<input class="access-hidden-item" type="hidden" name="access[' + item.closest('.panel').data('access-cod') + '][]" value="' + item.text() + '">');
        }
    });
};

$(document).ready(function() {

    Access.ListItems.enableSortable();

    // Controlle Select Change
    $('#controller').on('change', function() {
        var controller = $(this).val();
        $('#methods').html('');
        $.getJSON(hostPath + 'admin/access/get-methods', {'controller': controller}, function(result, status) {
            $.each(result.methods, function(controller, methods) {
                $.each(methods, function(method) {
                    $('#methods').append('<li data-controller="' + controller.replace('\\', '|').replace('\\', '|') + '" data-method="' + method + '" class="list-group-item">' + controller + '::' + method + '</li>');
                });
            });
            Access.ListItems.enableSortable();
        });
    });

    /**
     * Module
     */
    // On new module modal open
    $('#md-new-module').on('shown.bs.modal', function(e) {
        $('#fm-access_module :input[name=name]').focus();
    });

    // Save Module
    $('#md-new-module .btn-save-module').on('click', function() {
        var $form = $('#fm-access_module');
        $form.submitAjax(function(data, status) {
            if (status == 'success') {
                Global.alert.showSuccess(data.message);
                $('#md-new-module').modal('hide');

                $('#module, #md-new-access :input[name=access_module], #md-new-submodule :input[name=access_module]').append('<option value="' + data.data.cod + '">' + data.data.name + '</option>');
                $('#module').val(data.data.cod);
                $('#module').selectpicker('refresh');
                $('#module').change();
            } else {
                Global.alert.showError(data.message);
            }
        });
    });

    // Module Select Change
    $('#module').on('change', function() {
        $('#submodule, #fm-access :input[name=access_submodule]').find('[value][value!=""]').remove();
        $('#submodule').prop('disabled', true);
        $('#btn-new-submodule').prop('disabled', true);
        $('#block-access').html('');
        var module = $(this).val();

        if (!(parseInt(module) > 0)) {
            $('#submodule').selectpicker('refresh');
            return;
        }

        $.getJSON(hostPath + 'admin/access/get-submodules-list', {'module': module}, function(result, status) {
            $('#submodule').prop('disabled', false);
            $('#btn-new-submodule').prop('disabled', false);
            $.each(result.submodules, function(i, item) {
                $('#submodule, #fm-access :input[name=access_submodule]').append('<option value="' + item.cod + '">' + item.name + '</option>');
            });
            $('#submodule').selectpicker('refresh');
        });
    });

    /**
     * SubModule
     */
    // On new SubModule modal open
    $('#md-new-submodule').on('shown.bs.modal', function(e) {
        $('#fm-access_submodule :input[name=access_module]').val($('#module').val());
        $('#fm-access_submodule :input[name=access_module]').readonly(true);
        $('#fm-access_submodule :input[name=name]').focus();
    });

    // Save SubModule
    $('#md-new-submodule .btn-save-submodule').on('click', function() {
        var $form = $('#fm-access_submodule');
        $form.submitAjax(function(data, status) {
            if (status == 'success') {
                Global.alert.showSuccess(data.message);
                $('#md-new-submodule').modal('hide');
                $('#submodule, #md-new-access :input[name=access_submodule]').append('<option value="' + data.data.cod + '">' + data.data.name + '</option>');
                $('#submodule').val(data.data.cod);
                $('#submodule').selectpicker('refresh');
                $('#submodule').change();
            } else {
                Global.alert.showError(data.message);
            }
        });
    });

    // SubModule Select Change
    // Populate Access (Panels) and AccessItems (Draggables)
    $('#submodule').on('change', function() {
        $('#block-access').html('');
        $('#btn-new-access').prop('disabled', true);
        $('.btn-save-access-items').prop('disabled', true);
        var submodule = $(this).val();

        if (!(parseInt(submodule) > 0)) {
            return;
        }

        $.getJSON(hostPath + 'admin/access/get-access-list', {'submodule': submodule}, function(result, status) {
            $('#btn-new-access').prop('disabled', false);
            $('.btn-save-access-items').prop('disabled', false);
            //$.log('*** [tudo]', result.access);
            $.each(result.access, function(i, access) {
                $('#block-access').append('<div data-access-cod="' + access.cod + '" class="panel panel-default"><div class="panel-heading">' + access.name + '</div><div class="panel-body"><ol class="access-item col-xs-6"></ol><ol class="access-item col-xs-6"></ol></div></div>');
                var $ol = $('#block-access div[data-access-cod=' + access.cod + '] .access-item:first-child');
                $.log('[access]', i, access, $ol);
                $.each(access.items, function(j, item) {
                    if (item) {
                        var _item = item.split('::');
                        var controller = _item[0].split('\\').join('|');
                        var method = _item[1];
                        $ol.append('<li class="list-group-item" data-controller="' + controller + '" data-method="' + method + '">' + item + '<i class="btn-remove-item text-danger fa fa-times-circle pull-right"></i><input type="hidden" name="access[' + $ol.closest('.panel').data('access-cod') + '][]" value="' + item + '" class="access-hidden-item" /></li>');
                        $.log(' + [items]', controller, method);
                    }

                });
                //$.log($ol);
            });
            Access.ListItems.enableSortable();
        });
    });

    /**
     * Access
     */
    // On new Access modal open
    $('#md-new-access').on('shown.bs.modal', function(e) {
        $('#fm-access :input[name=access_module]').val($('#module').val());
        $('#fm-access :input[name=access_module]').readonly(true);
        $('#fm-access :input[name=access_submodule]').val($('#submodule').val());
        $('#fm-access :input[name=access_submodule]').readonly(true);
        $('#fm-access :input[name=name]').focus();
    });

    // Save Access
    $('#md-new-access .btn-save-access').on('click', function() {
        var $form = $('#fm-access');
        $form.submitAjax(function(data, status) {
            if (status == 'success') {
                Global.alert.showSuccess(data.message);
                $('#md-new-access').modal('hide');
                $('#block-access').append('<div data-access-cod="' + data.data.cod + '" class="panel panel-default"><div class="panel-heading">' + data.data.name + '</div><div class="panel-body"><ol class="access-item col-xs-6"></ol><ol class="access-item col-xs-6"></ol></div></div>');
            } else {
                Global.alert.showError(data.message);
            }
        });
    });

    /**
     * AccessItems
     */
    // Save AccessItems
    $('#fm-access-items .btn-save-access-items').on('click', function() {
        var $form = $('#fm-access-items');
        $form.submitAjax(function(data, status) {
            $.log(data)
            if (status == 'success') {
                Global.alert.showSuccess(data.message);
            } else {
                Global.alert.showError(data.message);
            }
        });

        return false;
    });

    // Access Item button remove
    $('body').on('click', '.btn-remove-item', function() {
        $(this).parent().remove();
        return false;
    });

});