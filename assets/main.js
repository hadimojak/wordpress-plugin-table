
const tableLocation = document.querySelector('#cob_table_plugin');
const table = document.createElement('table');
table.className = scriptParams.tableClasses;
table.innerHTML = `<thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رمز ارز</th>
                        <th scope="col">حجم بازار</th>
                        <th scope="col">آخرین قیمت</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                    `;
const tableBody = table.querySelector('tbody');




let canTry = true; let i = 0;
const interval = setInterval(function () {
    tableBody.innerHTML = '';
    fetch('http://localhost:3000/admin/categoryData').then(response => {
        if (response.status === 200) {
            canTry = false;
            return response.json();
        }
        if (response.status === 404) {
            canTry = true;
            i++;
            if (i > 2) {
                clearInterval(interval);
                alert('url for table data is incorrect');
            }
            throw new Error('url is incorrect');
        }

    }).then(data => {
        // console.log(data);
        data.forEach(p => {
            const row = `
            <th scope="row">${p.id}</th>
            <td>${p.title}</td>
            <td>${p.createdAt}</td>
            <td>${p.updatedAt}</td>
            `;
            tableBody.innerHTML += row;
        });
        tableLocation.insertAdjacentElement('afterbegin', table);
    }).catch(err => { console.log(err); });

}, scriptParams.timeIntervel);


