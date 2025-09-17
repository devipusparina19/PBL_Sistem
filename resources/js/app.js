// resources/js/app.js

// 1. Bootstrap (axios, echo, dll.)
import './bootstrap';

// 2. Import Tailwind CSS
import '../css/app.css';

// 3. Import library UI tambahan (contoh: Flowbite atau DaisyUI)
import 'flowbite';

// 4. Inisialisasi library tambahan (contoh Toastify)
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

// Contoh fungsi notifikasi global
window.notify = (msg, type = "info") => {
    Toastify({
        text: msg,
        className: type,
        duration: 3000,
        gravity: "top",
        position: "right",
        style: {
            background: type === "error" ? "red" : "green",
        },
    }).showToast();
};

// 5. Bisa tambahkan Alpine.js atau Vue/React kalau mau
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();
