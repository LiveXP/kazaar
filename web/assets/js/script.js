var MorningCheck = {
    initEditorMDE: function (id) {
        return translatedSimpleMDE(id);
    }
};

$(document).ready(function () {
    $.fn.select2.defaults.set("theme", "bootstrap");

    if ($("#appbundle_checking_comment").length > 0) {
        MorningCheck.simplemde = MorningCheck.initEditorMDE("appbundle_checking_comment");
    }

    if (document.getElementById('morning_check')) {
        $("#morning_check").change(function (e) {
            e.preventDefault();
            var id = $("#morning_check").find(":selected").val();
            if (id.length !== 0) {
                window.location.href = Routing.generate("start_specific_morning_check", {id: id});
            }
        })
    }

    if (document.getElementById('copyLast')) {
        $('#copyLast').click(function () {
            MorningCheck.simplemde.value($('#last').val());
            $('#collapseTwo').collapse();
            var top = $('#appbundle_checking_comment').offset().top;
            $('html,body').animate({scrollTop: top}, 1000);
        })
    }


    $('label.btn').click(function () {
        var parent = $(this).parent('.btn-group');
        var data = $(parent).data('toggle');

        if (parent.length && data.length && data === "buttons-radio") {
            var input = $(this).children('input');
            if (input.prop('type') === 'radio') {
                $('.label.btn').removeClass('active');
                $(this).addClass('active');
                input.prop('checked', true);
                input.trigger('change')
            }
        }
    });

    $('.btn-delete').click(function () {
        var target = $(this).data('target');
        if (target.length) {
            $(target).val(null);
            var id = $("#target_" + target).data('id');
            $("#target_" + target).removeAttr("style");

            if (typeof id !== "undefined") {
                $.ajax({
                    url: Routing.generate("remove_image"),
                    type: 'POST',
                    data: {id: id},
                    success: function (result) {}
                });
            }
        }
    });

    $('.hide_alert').click(function () {
        $(this).parent().hide();
    });

    // History
    if (document.getElementById('research_date')) {
        var select2 = $("#research_name").select2({
            placeholder: "Choisir le morning check",
            allowClear: true
        });

        $("#research_date").datepicker({
            format: "dd/mm/yyyy",
            todayBtn: "linked",
            language: "fr",
            clearBtn: true,
            autoclose: true,
            todayHighlight: true
        });

        $("#research_date").change(function () {
            $.ajax({
                url: Routing.generate("ajax_search_names"),
                type: "GET",
                data: {'date': $('#research_date').val()},
                success: function (data) {
                    $('#research_name option').remove();

                    select2.select2({
                        data: data,
                        placeholder: "Choisir le morning check",
                        allowClear: true
                    });
                }
            });

        });
    }

    $('#send-button-bootbox').click(function () {
        var link = $(this).data('href');
        bootbox.confirm("Attention ! L'email a déjà été envoyé, voulez vous continer et l'envoyer à nouveau ?", function (result) {
            if (result) {
                window.location.href = link;
            }
        });
    });

    $(".img-preview").magnificPopup({type: 'image'});

    //If the status is not checked, we check the OK status
    if (document.getElementById('appbundle_checking_status_1')) {
        if (!$('#appbundle_checking_status_1').prop('checked') && !$('#appbundle_checking_status_2').prop('checked')) {
            $('#appbundle_checking_status_1').prop('checked', true);
        }
    }

    if (document.getElementById('final_button')) {
        $('#final_button').click(function () {
            var link = $(this).data('href');
            if ($('#final_button').data('bootbox') == "1") {
                bootbox.confirm({
                    message: '<strong>Attention !</strong> Vous avez cliqué sur <i>Finaliser</i>, voulez vous passer tous les Checking non remplis au statut OK ?',
                    buttons: {
                        confirm: {
                            label: 'Passer en OK',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Ne rien faire',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            window.location.href = link + "?validate=1";
                        } else {
                            window.location.href = link;
                        }
                    }
                });
            } else {
                window.location.href = link;
            }

        });

    }

    if (document.getElementById("stats_date")) {
        $("#stats_date").datepicker({
            format: "mm/yyyy",
            language: "fr",
            clearBtn: true,
            autoclose: true,
            startView: "months",
            minViewMode: "months"
        });

    }
});
