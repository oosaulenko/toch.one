import "/assets/styles/admin/field/field-block.css";

import * as basicLightbox from 'basiclightbox'

const btnAddBlockEl = document.querySelector(".el-add_block");

const allBlocks = document.createElement('div');

btnAddBlockEl.addEventListener("click", function (e) {
    e.preventDefault();

    console.log("click");

    const instance = basicLightbox.create(`
    <div class="modald">
        <p>A custom modal that has been styled independently. It's not part of basicLightbox, but perfectly shows its flexibility.</p>
        <input placeholder="Type something">
        <a>Close</a>
    </div>
`, {
        onShow: (instance) => {
            instance.element().querySelector('a').onclick = instance.close
        }
    })

    instance.show()
});