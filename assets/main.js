
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
tableHead.className = scriptParams.tableHeadClasses;

function tableCreator(data) {

    data.forEach(p => {
        let firstRow = '';
        let row = '';
        console.log(p);
        for (el in p) {
            firstRow += `<th  scope="col">${el}</th>`;
            row += `<td >${p[el]}</td>`;
        }
        tableHead.innerHTML = firstRow;
        tableBody.innerHTML += row;
    });
    tableLocation.insertAdjacentElement('afterbegin', table);


}

function startInterval(seconds) {

    //fetch fro first time

    fetch(url, {
        method: 'GET',
        mode: 'cors',
   
    })
        .then(response => {
            console.log(response);
            if (response.status === 200) {
                return response.json();
            } else {
                alert('url for data table is incorrect');
                throw new Error('url is incorrect');
            }

        }).then(data => {
            console.log(data);
            tableCreator(data);
        }).catch(err => {
            console.log(err);
            return;
        });


    let canTry = true; let i = 0;
    const interval = setInterval(function () {

        tableBody.innerHTML = '';
        fetch(url, { method: 'GET',
        mode: 'cors',
    
        })
            .then(response => {
                if (response.status === 200) {
                    canTry = false;
                    return response.json();
                }
                if (response.status === 404) {
                    canTry = true;
                    i++;
                    console.log(i);
                    if (i > 2) {

                    }
                    throw new Error('url is incorrect');
                }

            }).then(data => {
                tableCreator(data);
            }).catch(err => {
                console.log(err);
                clearInterval(interval);
                alert('url for table data is incorrect');
                return;
            });

    }, timeInterval);
}
startInterval();



