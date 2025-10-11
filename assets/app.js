import app from './vue/instance'

require('./vue/components/__require')

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

app.mount('#app');
