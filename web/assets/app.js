/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import * as basicLightbox from 'basiclightbox';
import axios from 'axios';


const action = document.querySelector('#actionChangeLocale');

if(action) {
    action.addEventListener('change', function (e) {
        e.preventDefault();

        document.cookie = "app_locale=" + e.target.value + ";path=/";
        window.location.reload();
    });
}

const actionToggleMenu = document.querySelector('.actionToggleMenu');
const body = document.querySelector('body');
const mobileMenu = document.querySelector('.mobile_menu');

actionToggleMenu.addEventListener('click', function (e) {
    e.preventDefault();

    if(mobileMenu.classList.contains('is-active')) {
        mobileMenu.classList.remove('is-active');
        actionToggleMenu.classList.remove('is-active');
        body.classList.remove('is-not_scroll');
    } else {
        mobileMenu.classList.add('is-active');
        actionToggleMenu.classList.add('is-active');
        body.classList.add('is-not_scroll');
    }
});


const actionShowModal = document.querySelectorAll('.actionShowModal');

const instanceModalForm = basicLightbox.create(`
            <div class="modal">
                <h3 class="modal__title">Передзвоніть мені</h3>
                
                <form class="form formCallMeBack">
                    <div class="form__group">
                        <label for="field_name">Ім'я</label>
                        <input type="text" id="field_name" name="name" class="form__control" required>
                    </div>
                    <div class="form__group">
                        <label for="field_phone">Телефон</label>
                        <input type="tel" id="field_phone" name="phone" class="form__control" required>
                    </div>
                    <div class="form__group">
                        <label for="field_comment">Коментар</label>
                        <textarea type="tel" id="field_comment" name="comment" class="form__control"></textarea>
                    </div>
                    <div class="form__action">
                        <button type="submit" class="button button-primary form__submit actionSendMessage">Відправити</button>
                    </div>
                </form>
            </div>
        `);

actionShowModal.forEach(function (element) {
    element.addEventListener('click', function (e) {
        e.preventDefault();

        body.classList.remove('is-not_scroll');
        actionToggleMenu.classList.remove('is-active');
        mobileMenu.classList.remove('is-active');

        instanceModalForm.show();
    });
});

document.addEventListener('submit', function (e) {
    e.preventDefault();

    if (e.target.classList.contains('formCallMeBack')) {
        const formData = new FormData(e.target);

        if (!formData.get('phone') || !formData.get('name')) {
            return;
        }

        axios.post('/api/call_me_back', formData)
            .then(function (response) {
                instanceModalForm.close();
                e.target.reset();

                if (response.data.status === 'success') {
                    const instanceModalForm = basicLightbox.create(`
                        <div class="modal">
                            <h3 class="modal__title">${response.data.title}</h3>
                            <div class="modal__content">${response.data.message}</div>
                        </div>
                    `).show();
                }
            })
            .catch(function (error) {
                alert('Помилка відправки форми');
            });
    }
});