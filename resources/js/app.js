require('./bootstrap');

import { createApp } from 'vue'
import VueSocketIO from 'vue-socket.io-extended'
import io from 'socket.io-client'
import ChatComponent from './components/ChatComponent.vue'

const app = createApp({});

// const options = { transports : ['websocket'] };
// const socket = io('ws://localhost:8005', options);
// app.use(VueSocketIO,socket);

app.component('chat', ChatComponent);

app.mount('#app');
