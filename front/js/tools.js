window.onload = main;


/**
 * Change les clés en x et y
 */
dataJson = JSON.parse(JSON.stringify(dataJson).split('"step_count":').join('"y":'));
dataJson = JSON.parse(JSON.stringify(dataJson).split('"date_creation":').join('"x":'));

/**
 * Itère dans l'array JSON, inverse la date et créer un object Date()
 */
for(var object of dataJson) {
    object.x = object.x.split("/").reverse();
    object.x = new Date(object.x[0], object.x[1] - 1, object.x[2]);
}
console.log(dataJson);


var highestStepEntry = {y:0};

for(var object of dataJson) {
    //console.log(object.y);
    if (object.y > highestStepEntry.y) {
        highestStepEntry = object;
        console.log(object);
    }
}

for (var object of dataJson) {
    if (object == highestStepEntry) {
        object.indexLabel = "Valeur la plus haute";
        object.markerColor = "red";
    }
}

// Moyenne
var moyenne = 0;
var arrayMoyenne = [];

for (var object of dataJson) {
    moyenne += object.y;
}
moyenne /= dataJson.length;

for (var object of dataJson) {
    var newObj = new Object();
    newObj.x = object.x;
    newObj.y = moyenne;
    arrayMoyenne.push(newObj);
}

// 31 dernières journées
var today = new Date();	
var currentDate = new Date (today.getFullYear(), today.getMonth(), today.getDate());
var lastThirtyOneDayDate = new Date (today.getFullYear(), today.getMonth()-1, today.getDate());
console.log("lastThirtyOneDayDate " + lastThirtyOneDayDate);


// Array des 31 derniers jours
var arrayMoyenneLastThirtyOneDay = [];
var moyenne = 0;

for (var object of dataJson) {
    if ((object.x.getMonth() >= lastThirtyOneDayDate.getMonth() && object.x.getFullYear() == currentDate.getFullYear()) || 
    (object.x.getMonth() == 0 && lastThirtyOneDayDate.getMonth() == 11 && object.x.getFullYear() == currentDate.getFullYear())) {
        var newObj = new Object();
        newObj.x = object.x;
        newObj.y = object.y;
        arrayMoyenneLastThirtyOneDay.push(newObj);
        moyenne += object.y;
    }
}

// Moyenne des 31 derniers jours
moyenne /= arrayMoyenneLastThirtyOneDay.length;

for (var object of arrayMoyenneLastThirtyOneDay) {
    object.y = moyenne;
}

// Array par année
var arrayYear = [];
var firstDate = dataJson[0];
var moyenne = 0;
/* Nombre d'année de l'utilisation du podomètre
 * Source : https://stackoverflow.com/a/19395302
 */
var numberYear = [];
var isSame = false;
//dataJson.forEach(function (x) { numberYear[x.x.getFullYear()] = (numberYear[x.x.getFullYear()] || 0) + 1;});
for (var object of dataJson) {
    if (numberYear.length <= 0) {
        numberYear.push(object.x.getFullYear());
    } else {
        for (var year of numberYear) {
            if (year == object.x.getFullYear()) {
                isSame = true;
            } else {
                isSame = false;
            }
        }
        if (isSame == false) {
            numberYear[numberYear.length] = object.x.getFullYear();
        }
    }
    
}
console.log(numberYear);

var chartContainerYear = document.getElementById("chartContainerYear");
console.log(chartContainerYear);



function chartGlobal () {

    var chart = new CanvasJS.Chart("chartContainerGlobal", {
        animationEnabled: true,
        title: {
            text: "Nombre de pas par jour depuis l'utilisation du podomètre"
        },
        axisX: {
            title: "Jour",
            //minimum: new Date(2015, 01, 25),
            //maximum: new Date(2017, 02, 15),
            valueFormatString: "D/MM/YYYY"
        },
        axisY: {
            title: "Nombre de pas",
            titleFontColor: "#4F81BC",
            includeZero: true,
            suffix: " pas"
        },
        toolTip: {
            shared: true
        },
        data: [
            {
                indexLabelFontColor: "darkSlateGray",
                name: "Moyenne",
                type: "area",
                color: "rgba(165, 223, 0, 0.7)",
                xValueFormatString: "D/MM/YYYY",
                yValueFormatString: "## ### ### pas",
                dataPoints: arrayMoyenne
            },
            {
            indexLabelFontColor: "darkSlateGray",
            name: "Nombre de pas par jour",
            type: "area",
            color: "rgba(0,75,141,0.7)",
            xValueFormatString: "D/MM/YYYY",
            yValueFormatString: "## ### ### pas",
            dataPoints: /*[
                { x: new Date(2015, 02, 1), y: 74.4, label: "Q1-2015" },
                { x: new Date(2015, 05, 1), y: 61.1, label: "Q2-2015" },
                { x: new Date(2015, 08, 1), y: 47.0, label: "Q3-2015" },
                { x: new Date(2015, 11, 1), y: 48.0, label: "Q4-2015" },
                { x: new Date(2016, 02, 1), y: 74.8, label: "Q1-2016" },
                { x: new Date(2016, 05, 1), y: 51.1, label: "Q2-2016" },
                { x: new Date(2016, 08, 1), y: 40.4, label: "Q3-2016" },
                { x: new Date(2016, 11, 1), y: 45.5, label: "Q4-2016" },
                { x: new Date(2017, 02, 1), y: 78.3, label: "Q1-2017", indexLabel: "Highest", markerColor: "red" }
            ]*/
            dataJson
        }]
    });
    chart.render();

}

function chartLastThirtyOneDays () {

    var chart = new CanvasJS.Chart("chartContainerLastThirtyOneDays", {
        animationEnabled: true,
        title: {
            text: "Nombre de pas par jour des 31 derniers jours"
        },
        axisX: {
            title: "Jour",
            minimum: lastThirtyOneDayDate,
            maxi1um: currentDate,
            valueFormatString: "D/MM/YYYY"
        },
        axisY: {
            title: "Nombre de pas",
            titleFontColor: "#4F81BC",
            includeZero: true,
            suffix: " pas"
        },
        toolTip: {
            shared: true
        },
        data: [
        {
            indexLabelFontColor: "darkSlateGray",
            name: "Moyenne",
            type: "area",
            color: "rgba(165, 223, 0, 0.7)",
            xValueFormatString: "D/MM/YYYY",
            yValueFormatString: "## ### ### pas",
            dataPoints: arrayMoyenneLastThirtyOneDay
        },
        {
            indexLabelFontColor: "darkSlateGray",
            name: "Nombre de pas par jour des 31 derniers jours",
            type: "area",
            color: "rgba(223, 0, 0, 0.7)",
            xValueFormatString: "D/MM/YYYY",
            yValueFormatString: "## ### ### pas",
            dataPoints: dataJson
        }
        ]
    });
    chart.render();

}
var dropdownButton = document.getElementById("dropdownButton");
dropdownButton.append("Année");

function chartYear () {

    for (var year of numberYear) {
        (function() {
            var contentYear = document.createElement("div");
            contentYear.style.height = "300px";
            contentYear.style.width = "100%";
            contentYear.style.top = "300px";
            contentYear.id = "contentYear" + year;
            chartContainerYear.append(contentYear);
            contentYear.classList.add("hide");

            var tmpDate = currentDate;
            if (numberYear.indexOf(year) == numberYear.length - 1) {
                contentYear.classList.remove("hide");
            }
            if (tmpDate.getFullYear() != year) {
                tmpDate = new Date (year, 11, 31);
            } else {
                tmpDate = currentDate;
            }

            var dropdownMenu = document.getElementById("dropdownMenu");
            var dropdownItem = document.createElement("button");
            dropdownItem.classList.add("dropdown-item");
            dropdownItem.type = "button";
            dropdownItem.id = "dropdownItem" + year;
            
            dropdownItem.append(year);
            dropdownMenu.append(dropdownItem);

            var chart = new CanvasJS.Chart("contentYear" + year, {
                animationEnabled: true,
                title: {
                    text: "Nombre de pas par jour selon l'année"
                },
                axisX: {
                    title: "Jour",
                    minimum: new Date (year, 0, 1),
                    maximum: tmpDate,
                    valueFormatString: "D/MM/YYYY"
                },
                axisY: {
                    title: "Nombre de pas",
                    titleFontColor: "#4F81BC",
                    includeZero: true,
                    suffix: " pas"
                },
                toolTip: {
                    shared: true
                },
                data: [
                {
                    indexLabelFontColor: "darkSlateGray",
                    name: "Moyenne",
                    type: "area",
                    color: "rgba(165, 223, 0, 0.7)",
                    xValueFormatString: "D/MM/YYYY",
                    yValueFormatString: "## ### ### pas",
                    dataPoints: arrayMoyenneLastThirtyOneDay
                },
                {
                    indexLabelFontColor: "darkSlateGray",
                    name: "Nombre de pas par jour en " + year,
                    type: "area",
                    color: "rgba(223, 0, 0, 0.7)",
                    xValueFormatString: "D/MM/YYYY",
                    yValueFormatString: "## ### ### pas",
                    dataPoints: dataJson
                }
                ]
            });
            chart.render();
        }());
    }

    var dropdownItems = dropdownMenu.querySelectorAll("button");    
    var contentYearItems = document.getElementById("chartContainerYear").childNodes;
    
    // Menu déroulant
    (function() { 
        dropdownItems.forEach (element => {
            element.onclick = function() {
                contentYearItems.forEach(element2 => {
                        var dropdownYear = element.id.slice(element.id.length - 4);
                        var contentYearTmp = element2.id.slice(element2.id.length - 4);
                        if (dropdownYear == contentYearTmp) {
                            element2.classList.remove("hide");
                        } else {
                            element2.classList.add("hide");
                        }
                });      
            }
        });
    }());
}

function main() {
    chartGlobal();
    chartLastThirtyOneDays();
    chartYear();
}