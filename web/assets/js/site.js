let alerts = document.querySelectorAll('.alert')
if (alerts) {
    alerts.forEach(alertItem => {
        alertItem.querySelector('.btn').addEventListener('click', e => {
            alertItem.remove();
        })
    })
}
let dataConfirm = document.querySelectorAll('[data-confirm]');
if (dataConfirm) {
    dataConfirm.forEach(data => {
        data.addEventListener('click', (e) => {
            e.preventDefault();
            console.log(data)
            let confirmDetail = confirm(data.getAttribute('data-confirm'));
            if (confirmDetail) {
                window.location = data.getAttribute('href');
            }

        })
    })
}


let modals = document.querySelectorAll('[modal-id]');
if(modals){
    modals.forEach(m => {
        m.addEventListener('click', () => {
            let modal = document.querySelector(`#${m.getAttribute('modal-id')}`)
            modal.style.display = 'block';
            const body = modal.querySelector('.modal-body');
            const url = body.dataset.ajax;
            if (url && !body.dataset.loaded) {
                fetch(url)
                    .then(r => r.text())
                    .then(html => {
                        body.innerHTML = html;
                        body.dataset.loaded = '1';
                    });
            }


            let closeModal = modal.querySelectorAll('[modal-close]')
            closeModal.forEach(mClose => {
                mClose.addEventListener('click', () => {
                    modal.style.display = 'none';
                })
            })
        })
    })
}

String.prototype.reverseString = function () {
    return this.split('').reverse().join('');


};


let str = "Hello String";
console.log(str.reverseString())