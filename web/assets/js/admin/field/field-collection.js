import Sortable from "sortablejs";

export function fieldCollectionAction() {
    const fields = document.querySelectorAll('.field-collection');
    if (!fields) return;

    console.log(fields);

    fields.forEach((field) => {
        field.classList.add('is-load');

        field.querySelectorAll('.field-collection-item').forEach((item) => {
            updateButtonName(item);
        });

        field.classList.remove('is-load');
    });

    function updateButtonName(item) {
        const button = item.querySelector('.accordion-button');
        const title = item.querySelector('.field-collection-item-title');
        button.innerHTML = '<i class="fas fw fa-chevron-right form-collection-item-collapse-marker"></i> ' + title.value;
    }

    document.addEventListener('input', (event) => {
        if (event.target.classList.contains('field-collection-item-title')) {
            updateButtonName(event.target.closest('.field-collection-item'));
        }
    });
}

const eaCollectionSortableHandler = function (event) {
    const fields = document.querySelectorAll('.field-collection-sortable');
    if (!fields) return;

    fields.forEach((field) => {
        let items = field.querySelector('div[data-empty-collection]');

        if(!items) {
            items = field;
        }

        Sortable.create(items, {
            onChange: function (evt) {
                const collectionItems = items.querySelectorAll('.field-collection-item');
                collectionItems.forEach((item, i) => {
                    item.classList.remove('field-collection-item-first');
                    item.classList.remove('field-collection-item-last');

                    if(i === 0) {
                        item.classList.add('field-collection-item-first');
                    }

                    if(i === collectionItems.length - 1) {
                        item.classList.add('field-collection-item-last');
                    }

                    const inputs = item.querySelectorAll("input");
                    inputs.forEach((input) => {
                        const name = input.name;
                        input.name = name.replace(/\[\d+\]/, `[${i}]`);
                    });
                });
            },
        });
    });
};

window.addEventListener("DOMContentLoaded", eaCollectionSortableHandler);
document.addEventListener("ea.collection.item-added", eaCollectionSortableHandler);
document.addEventListener("ea.collection.item-removed", eaCollectionSortableHandler);