import "/assets/styles/admin/admin.css";

import * as actions from "./field/field-collection";

document.addEventListener('DOMContentLoaded', actions.fieldCollectionAction);
document.addEventListener('ea.collection.item-added', actions.fieldCollectionAction);

const handleSettingsForm = () => {
    const actionSaveSettings = document.querySelector('.actionSaveSettings');
    const actionSaveSettingsForm = document.querySelector('form[name="settings"]');

    if(actionSaveSettings) {
        actionSaveSettings.addEventListener('click', function () {
            actionSaveSettingsForm.submit();
        });
    }
}

document.addEventListener('DOMContentLoaded', handleSettingsForm);


const dropdownSettings = document.querySelector('.main-content .dropdown-settings');

if(dropdownSettings) {
    dropdownSettings.addEventListener('click', function (e) {
        if(e.target.classList.contains('dropdown-lang-item')) {
            e.preventDefault();
            document.cookie = "app_locale=" + e.target.dataset.lang + ";path=/";
            window.location.reload();
        }
    });
}