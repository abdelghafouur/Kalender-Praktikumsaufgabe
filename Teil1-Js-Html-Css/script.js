var date = new Date();
var months = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];

function renderBody() {
    document.getElementById("month").innerHTML = months[date.getMonth()] + " " + date.getFullYear();

    renderWeekDays();
    renderCalendar();
}

function renderWeekDays() {
    var weekDays = ["Son", "Mon", "Die", "Mit", "Don", "Fre", "Sam"];
    var weeksHTML = "";
    for (var i = 0; i < weekDays.length; i++) 
        {
            weeksHTML += `<div class="weekDays">${weekDays[i]}</div>`;
        }
    document.querySelector('.weeks').innerHTML = weeksHTML;
}

function renderCalendar() {
    var endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
    var prevDate = new Date(date.getFullYear(), date.getMonth(), 0).getDate();

    var today = new Date();
    var dateData = "";
    var number = 1;
    var nextMonth = false;

    for (var i = 0; i <= 5; i++) {
        dateData += "<div class='calBodyWeek'>";
        for (var j = 0; j <= 6; j++) 
            {
                if (i == 0 && j < firstDay) 
                    {
                        dateData += `<div class='calBodyDays prevMonth'>${prevDate - firstDay + 1 + j}</div>`;
                    } 
                else 
                    {
                        var isToday = (number === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear());
                        dateData += nextMonth ? `<div class='calBodyDays prevMonth'>${number}</div>` : (isToday ? `<div class='calBodyDays currentDay'>${number}</div>` : `<div class='calBodyDays'>${number}</div>`);
                        number++;
                    }
                if ((number > endDate)) 
                    {
                        number = 1;
                        nextMonth = true;
                    }
            }
        dateData += "</div>";
    }

    document.querySelector(".calBody").innerHTML = dateData;
}

function renderMonth(render) {
    if (render == 'prev') 
        {
            date.setMonth(date.getMonth() - 1);
        } 
    else if (render == 'next') 
        {
            date.setMonth(date.getMonth() + 1);
        }
    renderBody();
}
