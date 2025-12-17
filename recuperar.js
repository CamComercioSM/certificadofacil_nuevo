let wz_class = ".wizard";
const args = {
    "wz_class": ".wizard",
    "wz_nav_style": "tabs",
    "wz_button_style": ".btn .btn-sm .mx-3",
    "wz_ori": "horizontal",
    "buttons": true,
    "navigation": "all",
    "finish": "Finalizar",
    "bubble": true,
    "next": "Siguiente",
    "prev": "Atras"
};

const wizard = new Wizard(args);
wizard.init();
const $wz_doc = document.querySelector(wz_class);

async function recuperarCertificado() {

}