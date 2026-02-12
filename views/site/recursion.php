<?php


use app\helpers\ActiveForm;

$this->title = "recursion";

?>
<h1>recursion</h1>

<div>

    <ul>
        <li onclick="clicker(<?= $model->id ?>, this)"><?= $model->name ?></li>
    </ul>

</div>

<style>
    li{
        cursor: pointer;
    }
</style>

<script>


    function clicker(id, doc, event) {

        if (event) {
            event.stopPropagation(); // ⛔ ОЧЕНЬ ВАЖНО
        }

        const child = doc.querySelector(':scope > ul');

        if (doc.classList.contains('loaded')) {
            if (child) {
                const opened = doc.classList.contains('opened');
                child.style.display = opened ? 'none' : 'block';
                doc.classList.toggle('opened');
            }
            return;
        }

        fetch('/site/load?id=' + id)
            .then(res => res.json())
            .then(data => {

                if (!data.length) return;

                let ul = document.createElement('ul');

                data.forEach(d => {
                    let li = document.createElement('li');
                    li.textContent = d.name;
                    li.onclick = (e) => clicker(d.id, li, e); // 👈 передаём event
                    ul.appendChild(li);
                });

                doc.appendChild(ul);
                doc.classList.add('loaded');
                doc.classList.add('opened');
            });
    }





</script>