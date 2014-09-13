// jQuery Plugins
(function($) {
    if (!window.console) {
        console = {
            log: function(args) {
                alert(args);
            },
            warn: function(args) {
                alert(args);
            },
            info: function(args) {
                alert(args);
            },
            debug: function(args) {
                alert(args);
            },
            error: function(args) {
                alert(args);
            },
            time: function(args) {
                alert(args);
            },
            timeEnd: function(args) {
                alert(args);
            }
        };
    }
    /**
     * Debug
     */
    $.log = function() {
        if (window['console'] && window['console'].log) {
            return console.log(arguments.length == 1 ? arguments[0] : arguments);
        } else {
            var args = '';
            for (x in arguments) {
                args += arguments[x] + ', ';
            }
            alert(args);
        }
    };
    $.logDebug = function() {
        if (window['console'] && window['console'].debug) {
            return console.debug(arguments.length == 1 ? arguments[0] : arguments);
        } else {
            var args = '';
            for (x in arguments) {
                args += arguments[x] + ',';
            }
            alert(args);
        }
    };
    $.logInfo = function() {
        if (window['console'] && window['console'].info) {
            return console.info(arguments.length == 1 ? arguments[0] : arguments);
        } else {
            var args = '';
            for (x in arguments) {
                args += arguments[x] + ',';
            }
            alert(args);
        }
    };
    $.logWarn = function() {
        if (window['console'] && window['console'].warn) {
            return console.warn(arguments.length == 1 ? arguments[0] : arguments);
        } else {
            var args = '';
            for (x in arguments) {
                args += arguments[x] + ',';
            }
            alert(args);
        }
    };
    $.logError = function() {
        if (window['console'] && window['console'].error) {
            return console.error(arguments.length == 1 ? arguments[0] : arguments);
        } else {
            var args = '';
            for (x in arguments) {
                args += arguments[x] + ',';
            }
            alert(args);
        }
    };
    $.debugStart = function(name) {
        $.logWarn('Iniciando debug: ' + name);
        console.time(name);
    };
    $.debugFinish = function(name) {
        console.group(name);
        $.logWarn('Debug: ' + name);
        console.trace();
        console.timeEnd(name);
        console.groupEnd();
    };
    $.debugProfileStart = function() {
        $.logWarn('Iniciando debug profile: ' + name);
        console.profile();
    };
    $.debugProfileFinish = function() {
        console.group(name);
        $.logWarn('Finalizado debug profile: ' + name);
        console.profileEnd(name);
        console.groupEnd();
    };
    /**
     * Money Field
     * @param {Object} opts
     */
    $.fn.moneyField = function(opts) {
        var defaults = {width: null, symbol: '<span class="glyphicon glyphicon-usd"></span>'};
        var opts = $.extend(defaults, opts);
        return this.each(function() {
            if (opts.width) {
                $(this).css('width', opts.width + 'px');
            }
            $(this).wrap("<div class='input-group'>").before("<span class='input-group-addon'>" + opts.symbol + "</span>");
            $(this).maskMoney({thousands: '.', decimal: ','});
        });
    };
    $.each($('input.money'), function(i, item) {
        $(item).moneyField();
    });
    /**
     * Confirm popup
     */
    $('[data-confirmation]').confirmation({
        'title': 'Tem certeza?',
        'btnOkClass': 'btn btn-xs btn-danger',
        'btnCancelClass': 'btn btn-xs btn-default',
        'btnOkLabel': 'Sim',
        'btnCancelLabel': 'Não'
    });
    /**
     * Value change
     */
    $.each($('[data-value-change]'), function(i, item) {
        $(item).on('change', function() {
            if ($(this).val() !== "" + $(this).data('value')) {
                $(this).addClass('has-changed');
            } else {
                $(this).removeClass('has-changed');
            }
        });
    });
    $('[data-panel-max-button]').on('click', function() {
        var $target = $(this).data('target') ? $($(this).data('target')) : $(this).parents('.panel:eq(0)');
        if ($target.hasClass('panel-maximized')) {
            $target.removeClass('panel-maximized');
        } else {
            $target.addClass('panel-maximized');
        }
        return false;
    });
    /**
     * Select
     */
    $('.selectpicker,[data-select-picker]').selectpicker();
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $('.selectpicker,[data-select-picker]').selectpicker('mobile');
    }

    $.fn.submitAjax = function(callbackDone, callbackFail) {
        callbackDone = callbackDone ? callbackDone : function() {
        };
        callbackFail = callbackFail ? callbackFail : function() {
        };
        $.ajax({
            url: this.attr('action'),
            type: this.attr('method') ? this.attr('method') : 'POST',
            data: this.serialize(),
            dataType: 'json'
        }).done(function(data, status) {
            callbackDone(data, status)
        }).fail(function(data, status) {
            callbackFail(data, status)
        });
    };
    $.fn.readonly = function(enable) {
        if (!this[0]) {
            return;
        }
        if (this[0].tagName == 'SELECT') {
            if (enable) {
                this.on('focus', function() {
                    this.defaultIndex = this.selectedIndex;
                });
                this.on('change', function() {
                    this.selectedIndex = this.defaultIndex;
                });
                this.prop('readonly', 'readonly');
                this.addClass('readonly');
                this.find('option').addClass('readonly');
                return;
            }
            this.off('focus');
            this.off('change');
            this.removeClass('readonly');
            this.prop('readonly', false);
            this.find('option').removeClass('readonly');
            return;
        }
        if (enable) {
            this.prop('readonly', 'readonly');
            this.addClass('readonly');
            return;
        }
        this.prop('readonly', false);
        this.removeClass('readonly');
        return;
    };
})(jQuery);


var x = Error;
x.prototype.log = function() {
    var line = this.lineNumber ? this.lineNumber : this.stack;
    var stackContent = this.stack.split("\n")[0].split('@');
    stackContent[1] = stackContent[1].split(':');
    stackContent[1].pop();
    line = stackContent[1].pop();
    var file = stackContent[1].join(':');
    console.group('%c' + file + ' [' + line + ']' + (stackContent[0] ? ' %c' + stackContent[0] + '()' : ''), "color: green;", stackContent[0] ? "color: blue;" : ' ');
    $.log(arguments);
    console.groupEnd();
};

window.onerror = handleError;
function handleError(err, url, line) {
    console.warn('%cERROR! [' + url + ':' + line + ']\n' + err, 'color: red;');
    return true;
}

// Global
Global = {};

// Alert
Global.alert = {
    '_tplAlert': {
        'success': '<div class="alert alert-success alert-dismissable col-xs-12">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<strong>Sucesso!</strong> {$msg}' +
                '</div>',
        'error': '<div class="alert alert-danger alert-dismissable col-xs-12">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<strong>Erro!</strong> {$msg}' +
                '</div>'
    },
    'show': function(type, msg) {
        switch (type) {
            case 'success':
                $('body > .container > .row').prepend(Global.alert._tplAlert.success.replace('{$msg}', msg));
                break;
            case 'error':
                $('body > .container > .row').prepend(Global.alert._tplAlert.error.replace('{$msg}', msg));
                break;
        }
    },
    'showSuccess': function(msg) {
        Global.alert.show('success', msg);
    },
    'showError': function(msg) {
        Global.alert.show('error', msg);
    }
};
Global.html = {
    '_tpl': {
        'form': {
            'item': '<div class="form-group"><label class="control-label col-xs-3">{$label}</label><div class="col-xs-8">{$input}</div></div>',
            'input': '<input type="text" name="{$name}" class="form-control" />',
            'select': '<select class="form-control" name="{$name}">{$options}</select>',
            'submit': '<button class="btn {$class}" id="{$id}">{$label}</button>',
        },
        'icon': '<span class="glyphicon glyphicon-{$name}"></span>'
    },
    'getTplFormItem': function(label, component) {
        return Global.html._tpl.form.item.replace('{$label}', label).replace('{$input}', component);
    },
    'getTplFormInput': function(name) {
        return Global.html._tpl.form.input.replace('{$name}', name);
    },
    'getTplFormSelect': function(name, options) {
        return Global.html._tpl.form.select.replace('{$name}', name).replace('{$options}', options);
    },
    'getTplFormSubmit': function(id, label, cssClass) {
        return Global.html._tpl.form.submit.replace('{$class}', cssClass).replace('{$id}', id).replace('{$label}', label);
    },
    'getTplIcon': function(name) {
        return Global.html._tpl.icon.replace('{$name}', name);
    }
};
Global.http = {
    'showError': true,
    'messageError': 'Erro ao fazer requisição',
    'postCallback': function() {
        if (result.success) {
            if (result.message) {
                Global.alert.showSuccess(result.message);
            }
            if (result.redirect) {
                Global.http.redirect(result.redirect);
            }
        } else {
            if (Global.http.showError) {
                Global.alert.showError(result.message ? result.message : Global.http.messageError);
            }
        }
    },
    'postJson': function(path, params, callBack) {
        $.post(hostPath + path, params, callBack ? callBack : Global.http.postCallback, 'json');
    },
    'redirect': function(path) {
        window.location = hostPath + path;
    }
};
Timer = {
    'hours': 0,
    'minutes': 0,
    'init': function() {
        this.hours = 0;
        this.minutes = 0;
        return this;
    },
    // Time functions
    'appendTime': function(time) {
        time = time.split(':');
        this.appendHours(time[0]);
        this.appendMinutes(time[1]);
    },
    'appendHours': function(hours) {
        this.hours += parseInt(hours);
    },
    'appendMinutes': function(minutes) {
        this.minutes += parseInt(minutes);
        if (this.minutes >= 60) {
            var hours = parseInt(this.minutes / 60);
            this.minutes -= hours * 60;
            this.hours += hours;
        }
        if (this.minutes < 0) {
            this.minutes = 60 + this.minutes;
            this.appendHours(-1);
        }
    },
    // Day functions
    'appendDayTime': function(timerEnter, timerExit) {
        timerEnter = timerEnter.split(':');
        timerExit = timerExit.split(':');
        var hourExit = timerExit[0];
        var minuteExit = timerExit[1];
        var hourEnter = timerEnter[0];
        var minuteEnter = timerEnter[1];
        this.appendHours(hourExit - hourEnter);
        this.appendMinutes(minuteExit - minuteEnter);
    }
};