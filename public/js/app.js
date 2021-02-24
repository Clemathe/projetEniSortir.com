$(document).ready(function () {

    var $ville = $('#sortie_form_ville');
    var $token = $("#sortie_form_token");

    $ville.change(function() {
        console.log('ville change');
        var $form = $(this).closest('form');

        var data = {};

        // Les données à envoyer en Ajax
        data[$token.attr('name')] = $token.val();
        data[$ville.attr('name')] = $ville.val();

        // Soumission des données
        $.post($form.attr('action'), data).then(function(response)
        {
            console.log(response);
            // Recupération du nouveau <select> et remplacement
            $("#sortie_form_lieu").replaceWith(
                $(response).find('#sortie_form_lieu')
            )
        })
    })











    // $(document).on('change', '#sortie_form_ville', function () {
    //     let $field = $(this)
    //     // let $villeField = $('#sortie_form_ville')
    //
    //     let $form = $field.closest('form')
    //
    //     // Les données à envoyer en Ajax
    //     let data = {}
    //     data[$field.attr('name')] = $field.val()
    //
    //     // On soumet les données
    //     let $input = $(data).find('#sortie_form_lieu')
    //     debugger
    //     $.post($form.attr('action'), data).then(function (data) {
    //
    //         // On récupère le nouveau <select>
    //         let $input = $(data).find('#sortie_form_lieu')
    //         // On remplace notre <select> actuel
    //         $('#sortie_form_lieu').replaceWith($input)
    //     })
    // })


    //     var $ville = $('#sortie_form_ville');
    //     // When sport gets selected ...
    //     $ville.change(function() {
    //     // ... retrieve the corresponding form.
    //     var $form = $(this).closest('form');
    //     // Simulate form data, but only include the selected sport value.
    //     var data = {};
    //     data[$ville.attr('name')] = $ville.val();
    //     // Submit data via AJAX to the form's action path.
    //     $.ajax({
    //     url : $form.attr('action'),
    //     type: $form.attr('method'),
    //     data : data,
    //     success: function(html) {
    //         debugger
    //     // Replace current position field ...
    //     $('#sortie_form_lieu').replaceWith(
    //     // ... with the returned one from the AJAX response.
    //     $(html).find('#sortie_form_lieu')
    //     );console.log($('#sortie_form_lieu'));
    //     // Position field now displays the appropriate positions.
    // }
    // });
    // });
})