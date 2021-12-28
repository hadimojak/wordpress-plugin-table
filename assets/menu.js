const url = scriptParams.apiUrl;
const pickOptions = scriptParams.pick_options;
const buyOptions = scriptParams.buy_options;

function tableCreator(data) {
  const apiTable = document.getElementById("apiTable");
  const tableLoc = document.getElementById("tableLoc");
  apiTable.innerHTML = `<thead></thead><tbody></tbody>`;
  const tableBody = apiTable.querySelector("tbody");
  const tableHead = apiTable.querySelector("thead");
  tableHead.style.position = 'sticky';
  tableHead.style.top = '0';
  tableHead.style.backgroundColor = '#71d7f7';

  data.forEach((p) => {
    let firstRow = "";
    let row = "";
    for (el in p) {
      firstRow += `<th scope="col">${el}</th>`;
      row += `<td>${p[el]}</td>`;
    }
    firstRow += `<th scope='col'>نمایش</th>`;
    firstRow += `<th scope='col'>خرید/فروش</th>`;

    const alias = row.substring(
      row.indexOf("<td>") + 4,
      row.indexOf("</td>")
    );
    row += `<td > <input name='pick[${alias}]' type="checkbox" ${Object.keys(pickOptions).includes(alias) ? 'checked' : ''}  value="on"  /></td>`;
    row += `<td >  <input  name='buy[${alias}]' type="checkbox" ${Object.keys(buyOptions).includes(alias) ? 'checked' : ''} value='on' ></td>`;
    tableHead.innerHTML = firstRow;
    tableBody.innerHTML += row;
  });
  tableLoc.insertAdjacentElement("afterbegin", apiTable);
}

function fetchFunction() {
  fetch(url)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data.rows);
      tableCreator(data.rows);
    })
    .catch((err) => {
      console.log(err);
    });
}

window.onload = function () {
  document.getElementById('fetchBtn').click();
};