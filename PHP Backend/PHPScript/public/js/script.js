'use strict';

class Notification {
    constructor(options) {
        this.element = document.querySelector('.notification');
        this.effect = options.effect || 'none';
        this.duration = options.duration || 5000;
        this.timeoutHideId = 0;
        this.bindEvent();
    }
    
    bindEvent() {
        /*this.element.addEventListener('click', function() {
            this.element.classList.remove('visible');
            this.clear();
            clearTimeout(this.timeoutHideId);
        }.bind(this));*/
    }
    
    success(data) {
        if(this.element.classList.contains('visible')) { return false; }
        
        this.element.querySelector('.body .title').textContent = data.title;
        this.element.querySelector('.body .message').textContent = data.message;
        this.element.classList.add('success');
        this.show();
    }
    
    fail(data) {
        if(this.element.classList.contains('visible')) { return false; }
        
        this.element.querySelector('.body .title').textContent = data.title;
        this.element.querySelector('.body .message').textContent = data.message;
        this.element.classList.add('fail');
        this.show();
    }
    
    clear() {
        setTimeout(function() {
            this.element.classList.remove('success');
            this.element.classList.remove('fail');
            this.element.querySelector('.body .title').textContent = '';
        this.element.querySelector('.body .message').textContent = '';
        }.bind(this), 500);
    }
    
    show() {
        if(this.effect !== 'none') {
            let effect;
            switch(this.effect) {
                case 'ease': effect = 'ease'; break;
                case 'linear': effect = 'linear'; break;
                case 'ease-in': effect = 'ease-in'; break;
                case 'ease-out': effect = 'ease-out'; break;
                case 'ease-in-out': effect = 'ease-in-out'; break;
                default: effect = 'ease';
                         console.error('Invalid effect. Using default...');
            }
            this.element.classList.add(effect);
            this.element.classList.add('visible');
        }
        else if(this.effect === 'none' && this.duration === 5000) {
            this.element.classList.add('visible');
        }
        
        this.hide();
    }
    
    hide() {
        this.timeoutHideId = setTimeout(function() {
            this.element.classList.remove('visible');
            this.clear();
        }.bind(this), this.duration);
    }
}


/* ===============================================*\
 *                  USE MODE
\* ===============================================*/
'use strict';
        
const notification = new Notification({
    effect: 'easex',
    duration: 4000
});
const btnSuccess = document.querySelector('.btn.success');
const btnFail = document.querySelector('.btn.fail');
   function success(){
      notification.success({
        title: 'Hetal', 
        message: 'New Message'
    });
   }     
