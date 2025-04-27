document.addEventListener("DOMContentLoaded", function () {
    const invalid_class = 'input-invalid';
    var phoneMask = IMask(
        document.querySelector("[data-mask='phone_mask']"),
        {
            mask: '+{7} (000) 000-00-00',
            prepare: function (appended, masked) {
                if ((appended === '8' && masked.value === '') || (appended === '8' && masked.value === '+7 (')) {
                    return '';
                }
                return appended;
            },
        })

    document.getElementById("feedback").addEventListener("submit", (e) => {
        e.preventDefault();
        let form_data = new FormData(e.target);
        let send = true;
        let agree = document.getElementById('agree');
        if (agree.checked) {
            agree.closest('.checkbox-container').querySelector('span').classList.remove('input-invalid');
        } else {
            send = false;
            agree.closest('.checkbox-container').querySelector('span').classList.add('input-invalid');
        }
        e.target.querySelectorAll('.require').forEach(el => {
            if (el.value == '') {
                send = false;
                el.classList.add(invalid_class)
            } else {
                el.classList.remove(invalid_class)
            }
        })
        if (!send) {
            Fancybox.show([{src: "Заполните обязательные поля", type: "html"}]);
            return false;
        } else {
            BX.ajax.runComponentAction('bitrix:feedback',
                'add', {
                    mode: 'class',
                    data: form_data,
                })
                .then(function (response) {
                    Fancybox.show([{src: response.data.success, type: "html"}]);
                }, function (response) {
                    if (response.errors[0].code !== 400) {
                        Fancybox.show([{src: "<p>Ошибка сервера.</p>", type: "html"}]);
                    }
                    Fancybox.show([{src: response.errors[0].message, type: "html"}]);
                });
        }
        return false
    });
});

function loadDepartments(select) {
    const departmentsSelect = document.querySelector('select[name="DEPARTMENT"]');
    const doctorsSelect = document.querySelector('select[name="DOCTOR"]');
    BX.ajax.runComponentAction('bitrix:feedback',
        'department', {
            mode: 'class',
            data: {
                action: 'getDepartment',
                filial: select.value,
                xml_id: select.options[select.selectedIndex]['attributes']['xml_id']['value']
            },
        })
        .then(function (response) {
            departmentsSelect.innerHTML = '';
            console.log(JSON.parse(response.data))
            const values = Object.entries(JSON.parse(response.data));
            console.log(values)
            const option = document.createElement('option');
            option.value = '';
            option.text = 'Выбрать отделение';
            departmentsSelect.appendChild(option);
            values.forEach((department, i) => {
                const option = document.createElement('option');
                option.value = department[1];
                option.text = department[1];
                option.setAttribute('xml_id', department[0]);
                departmentsSelect.appendChild(option);
            });
            departmentsSelect.removeAttribute('disabled');
            doctorsSelect.setAttribute('disabled', 'disabled');
        }, function (response) {
        });
}

function loadDoctors(select) {
    const doctorsSelect = document.querySelector('select[name="DOCTOR"]');
    BX.ajax.runComponentAction('bitrix:feedback',
        'doctor', {
            mode: 'class',
            data: {
                action: 'getDoctor',
                filial: select.value,
                xml_id: select.options[select.selectedIndex]['attributes']['xml_id']['value']
            },
        })
        .then(function (response) {
            doctorsSelect.innerHTML = '';

            const values = Object.values(JSON.parse(response.data));
            const option = document.createElement('option');
            option.value = '';
            option.text = 'Выбрать врача';
            doctorsSelect.appendChild(option);
            values.forEach((doctor, i) => {
                const option = document.createElement('option');
                option.value = doctor;
                option.text = doctor;
                option.setAttribute('xml_id', i);
                doctorsSelect.appendChild(option);
            });
            doctorsSelect.removeAttribute('disabled');
        }, function (response) {
        });
}