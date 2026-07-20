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


document.addEventListener('DOMContentLoaded', function() {
    // Выбрать / Снять все чекбоксы
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('js-select-all')) {
            const table = e.target.closest('table');
            const checkboxes = table.querySelectorAll('.js-checkbox-row');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        }
    });

    // Обработка кнопки "Удалить выбранные"
    document.addEventListener('click', function(e) {
        console.log(e.target.classList.contains('js-delete-selected'))
        if (e.target.classList.contains('js-delete-selected')) {
            const btn = e.target;
            const container = btn.closest('div').nextElementSibling; // находим таблицу рядом
            const selected = Array.from(container.querySelectorAll('.js-checkbox-row:checked'))
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Выберите хотя бы одну запись для удаления.');
                return;
            }

            if (!confirm('Вы уверены, что хотите удалить выбранные записи?')) {
                return;
            }

            console.log(btn.dataset.url)

            // AJAX запрос (Vanilla JS / fetch)
            fetch(btn.dataset.url, {
                method: 'POST',
                body: JSON.stringify({ ids: selected })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Ошибка при удалении');
                    }
                })
                .catch(() => alert('Сетевая ошибка при запросе.'));
        }
    });
});

String.prototype.reverseString = function () {
    return this.split('').reverse().join('');


};

const Helper = {
    vars: {},
    assign: () => {},
    add(key, val){
        this.vars[key] = val
    },
    get(key = null){
        if(key){
            return this.vars[key];
        }
        else return this.vars
    }
}

Helper.add('a', 1)
Helper.add('b', 2)
Helper.add('c', 3)

console.log(Helper.get())

let str = "Hello String";
console.log(str.reverseString())