const tableLocation = document.querySelector("#cob_table_plugin");
const tableTheme = scriptParams.tableTheme;
console.log(tableTheme);
var timeInterval = scriptParams.timeIntervel;
var url = scriptParams.apiUrl;
const table = document.createElement("table");




table.className = scriptParams.tableClasses;
if (scriptParams.tableStripedClass === "table-striped") {
  table.className += " table-striped";
}
if (scriptParams.tableHoverClass === "table-hover") {
  table.className += " table-hover";
}
table.innerHTML = `<thead></thead><tbody></tbody>`;
const tableBody = table.querySelector("tbody");
const tableHead = table.querySelector("thead");
tableHead.className = scriptParams.tableHeadClasses;

if (tableTheme === 'scroll') {
  tableLocation.style.maxHeight = '300px';
  tableLocation.style.overflow = 'auto';
  tableHead.style.position = 'sticky';
  tableHead.style.top = '0';
  tableHead.style.backgroundColor = '#fff';
}

if (tableTheme === 'paggini') {

}

function tableCreator(data) {
  data.forEach((p) => {
    let firstRow = "";
    let row = "";
    for (el in p) {
      // console.log(el);
      if (el === "button") {
        firstRow += `<th  scope="col"></th>`;
      } else {
        firstRow += `<th  scope="col">${el}</th>`;
      }
      row += `<td class=${scriptParams.tableTdClasses} >${p[el]}</td>`;
    }
    tableHead.innerHTML = firstRow;
    tableBody.innerHTML += row;
  });
  tableLocation.insertAdjacentElement("afterbegin", table);
}

function startInterval(seconds) {
  //fetch fro first time

  fetch(url, {
    method: "GET",

  })
    .then((response) => {
      if (response.status === 200) {
        return response.json();
      } else {
        alert("url for data table is incorrect");
        throw new Error("url is incorrect");
      }
    })
    .then((data) => {
      tableCreator(data);
    })
    .catch((err) => {
      console.log(err);
      return;
    });

  let canTry = true;
  let i = 0;
  const interval = setInterval(function () {
    tableBody.innerHTML = "";
    fetch(url, { method: "GET" })
      .then((response) => {
        if (response.status === 200) {
          canTry = false;
          return response.json();
        }
        if (response.status === 404) {
          canTry = true;
          i++;
          if (i > 2) {
          }
          throw new Error("url is incorrect");
        }
      })
      .then((data) => {
        tableCreator(data);
      })
      .catch((err) => {
        console.log(err);
        clearInterval(interval);
        alert("url for table data is incorrect");
        return;
      });
  }, timeInterval);
}
startInterval();
