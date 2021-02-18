jQuery(document).ready(function () {

    $(document).on('change', '#sortie_form_ville', function () {
        let $field = $(this)
        // let $villeField = $('#sortie_form_ville')

        let $form = $field.closest('form')

        // Les données à envoyer en Ajax
        let data = $field.val()

        // On soumet les données
        let $input = $(data).find('#sortie_form_lieu')
        debugger
        $.post($form.attr('action'), data).then(function (data) {

            // On récupère le nouveau <select>
            let $input = $(data).find('#sortie_form_lieu')
            // On remplace notre <select> actuel
            $('#sortie_form_lieu').replaceWith($input)
        })
    })
})