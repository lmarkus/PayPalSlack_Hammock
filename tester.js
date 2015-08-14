/**
 * Created by lmarkus on 12/11/14.
 */
'use strict';

var paypal = require('paypal-rest-sdk');

paypal.configure({
    'mode': 'sandbox', //sandbox or live
    'client_id': 'AVc27xDn4TOP7CLg2qkQWPiaWpzphQHW2W0eyC4GinmD8PHxYZaJteWOKW9m',
    'client_secret': 'EAjbuhD6IW3uMlKxN1EiI_VWoJZ0WW36dlE-oTg97yguFa4ZJoLuUQe5kbvG'
});

paypal.notification.webhook.list(function (error, webhooks) {
    if (error) {
        throw error;
    } else {
        console.log(JSON.stringify(webhooks));
    }
});
