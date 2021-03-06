const tableLocation = document.querySelector("#cob_table_plugin");
const tableTheme = scriptParams.tableTheme;
const pickOptions = scriptParams.pick_options;
const buyOptions = scriptParams.buy_options;

var timeInterval = scriptParams.timeIntervel * 1000;
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
  tableLocation.style.maxHeight = '450px';
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
    Object.keys(p).forEach(q => {
      console.log(q);
      switch (q) {
        case 'alias':
          firstRow += `<th  scope="col">لوگو</th>`;

          break;
        case 'title':
          firstRow += `<th  scope="col">title</th>`;

          break;
        case 'faTitle':
          firstRow += `<th  scope="col">عنوان</th>`;

          break;
        case 'currentPriceIRR':
          firstRow += `<th  scope="col">قیمت به ریال</th>`;

          break;
        case 'currentPriceUSD':
          firstRow += `<th  scope="col">قیمت به دلار</th>`;

          break;
          case 'status':

          break;
        default:
          firstRow += `<th  scope="col">${q}</th>`;

          break;
      }
    });
    firstRow += `<th  scope="col">خرید/فروش</th>`;
    if (Object.keys(pickOptions).includes(p.alias)) {
      for (el in p) {
        switch (el) {
          case 'alias':
            row += `<td class=${scriptParams.tableTdClasses} ><i class="cf cf-${p[el]}" style="font-size: 3em;"></i></td>`;

            break;
            case 'status':
  
              break;
          default:
            row += `<td class=${scriptParams.tableTdClasses} >${p[el]}</td>`;

            break;
        }

      }
    }
    if (Object.keys(buyOptions).includes(p.alias) && Object.keys(pickOptions).includes(p.alias)) {
      row += `<td class=${scriptParams.tableTdClasses} ><a class="btn btn-success" href="#" role="button">خرید/فروش</a>
      </td>`;
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
      tableCreator(data.rows);
      console.log(data);
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
        tableCreator(data.rows);
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


document.getElementById('rial').addEventListener('click', rialHandler);
function rialHandler() {
  console.log('tooooo riallllllll');
}
document.getElementById('tether').addEventListener('click', tetherHandler);
function tetherHandler() {
  console.log('tooooo tether');
}
document.getElementById('bitcoin').addEventListener('click', bitcoinHandler);
function bitcoinHandler() {
  console.log('tooooo bitcoin');
}