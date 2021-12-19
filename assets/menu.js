const url = scriptParams.apiUrl;

function tableCreator(data) {
  const apiTable = document.getElementById("apiTable");
  const tableLoc = document.getElementById("tableLoc");
  apiTable.innerHTML = `<thead></thead><tbody></tbody>`;
  const tableBody = apiTable.querySelector("tbody");
  const tableHead = apiTable.querySelector("thead");

  data.forEach((p) => {
    console.log(p);
    let firstRow = "";
    let row = "";
    for (el in p) {
      firstRow += `<th  scope="col">${el}</th>`;
      row += `<td class=${scriptParams.tableTdClasses} >${p[el]}</td>`;
    }
    firstRow += `<th scope='col'>نمایش</th>`;
    firstRow += `<th scope='col'>خرید/فروش</th>`;
    row += `<td > <input name='pick[]' type="checkbox"  value="on" />
    </td>`;
    row += `<td >  <input  name='buy[]' type="checkbox" value='on' >
    </td>`;
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
      tableCreator(data);
    })
    .catch((err) => {
      console.log(err);
    });
}
