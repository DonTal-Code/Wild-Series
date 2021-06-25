/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

console.log('Hello Webpack Encore !')
import logoPath from './images/favicon.ico';

const $episode_category = $("#episode_category");
const $episode_program = $("#episode_program");
const $token = $('#episode__token')
$episode_category.change(function ()
{
    const $form = $(this).closest('form')
    const data = {}

    data[$token.attr('name')] = $token.val()
    data[$episode_category.attr('name')] = $episode_category.val()
    $.post($form.attr('action'), data).then(function (response)
    {
        $('#episode_program').replaceWith(
            $(response).find('#episode_program')
        )
    })
})
$episode_program.change(function ()
    {
        const $form = $(this).closest('form')
        const data = {}
        data[$token.attr('name')] = $token.val()
        data[$episode_program.attr('name')] = $episode_program.val()
        $.post($form.attr('action'), data).then(function (response)
        {
            $('#episode_season').replaceWith(
                $(response).find('#episode_season')
            )
        })
    })



function log($msg){
    console.log($msg)
}