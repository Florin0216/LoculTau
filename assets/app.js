import app from './vue/instance'

require('./vue/components/__require')

import * as bootstrap from 'bootstrap';
import {VueReCaptcha} from "vue-recaptcha-v3";

window.bootstrap = bootstrap;

app.use(VueReCaptcha, { siteKey: '6Lfm5vgrAAAAAAeoyKdIt4Sfr5E7JykJOuO-g6Lv' });

app.mount('#app');
