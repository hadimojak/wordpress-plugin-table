
const tableLocation = document.querySelector('#cob_table_plugin');
var timeInterval = scriptParams.timeIntervel;
var url = scriptParams.apiUrl;
const table = document.createElement('table');
table.className = scriptParams.tableClasses;
table.innerHTML = `<thead>
                    
                    </thead>
                    <tbody>
                    
                    </tbody>
                    `;
const tableBody = table.querySelector('tbody');
const tableHead = table.querySelector('thead');

function startInterval(seconds) {
    fetch(url).then(response => {
        if (response.status === 200) {
            return response.json();
        }
        if (response.status === 404) {
            alert('url for data table is incorrect');
            throw new Error('url is incorrect');
        }
    }).then(data => {
        data.forEach(p => {
            console.log(p);
            let firstRow = '';
            for (el in p) {

                firstRow += `<th scope="col">${el}</th>`;
            }
            tableHead.innerHTML = firstRow;

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

    let canTry = true; let i = 0;
    const interval = setInterval(function () {
        tableBody.innerHTML = '';
        fetch(url).then(response => {
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

    }, timeInterval);
}
startInterval();



