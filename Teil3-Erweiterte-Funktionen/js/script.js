// Auto-Ausblenden der Warnmeldung nach 2 Sekunden
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        var alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.classList.add('hide');
        }
    }, 2000);
});

// Funktion zur Steuerung der Sichtbarkeit der Eingabefelder basierend auf der Auswahl
var radioButtons = document.querySelectorAll('input[class="is_full_day"]');
radioButtons.forEach(function (radioButton) {
    radioButton.addEventListener("change", function () {
        var isFullDay = this.value === "1";
        var timeInputs = document.getElementById("timeInputs");
        var timeInputsEdite = document.getElementById("timeInputsEdite");
        if (isFullDay) {
            timeInputs.style.display = "none";
            timeInputsEdite.style.display = "none";
        } else {
            timeInputs.style.display = "block";
            timeInputsEdite.style.display = "block";
        }
    });
});

// Funktionen zur Steuerung des Bearbeitungsmodals

var modal = document.getElementById('editModal');

// Funktion zum Schließen des Modals
function closeModalEdite() {
    modal.style.display = "none";
}

// Funktion zum Öffnen des Modals und zum Ausfüllen der Eingabefelder mit den Termindaten
function openModalEdite(id) {
    // AJAX-Anfrage, um Termininformationen abzurufen und das Modal mit diesen Daten zu füllen
    fetch("get_appointment.php?id=" + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch appointment');
            }
            return response.json();
        })
        .then(appointment => {
            document.getElementById("editId").value = appointment.id;
            document.getElementById("editTitle").value = appointment.title;
            document.getElementById("editDescription").value = appointment.description;
            document.getElementById("editDate").value = appointment.date;
            document.getElementById("end_dateEdite").value = appointment.end_date;

            var editCategorySelect = document.getElementById("editCategory");
            editCategorySelect.value = appointment.category_id;

            if (appointment.is_full_day == 0) {
                document.getElementById("timeInputsEdite").style.display = "block";
                document.getElementById("isFullDayYes").checked = false;
                document.getElementById("isFullDayNo").checked = true;
                document.getElementById("editStartTime").value = appointment.start_time;
                document.getElementById("editEndTime").value = appointment.end_time;
            } else {
                document.getElementById("timeInputsEdite").style.display = "none";
                document.getElementById("isFullDayYes").checked = true;
                document.getElementById("isFullDayNo").checked = false;
            }

            modal.style.display = "block";
        })
        .catch(error => {
            console.error('Error fetching appointment:', error);
            alert('Fehler beim Abrufen des Termins. Bitte versuchen Sie es später erneut.');
        });
}

// Validierung des Bearbeitungsformulars
function validateFormEdit() {
    var title = document.getElementById("editTitle");
    var date = document.getElementById("editDate");
    var end_date = document.getElementById("end_dateEdite");
    var isFullDayRadios = document.getElementsByName("is_full_dayEdite");
    var is_full_day;
    for (var i = 0; i < isFullDayRadios.length; i++) {
        if (isFullDayRadios[i].checked) {
            is_full_day = isFullDayRadios[i].value;
            break;
        }
    }
    var startTime = document.getElementById("editStartTime");
    var endTime = document.getElementById("editEndTime");

    title.style.border = "1px solid #ccc";
    date.style.border = "1px solid #ccc";
    end_date.style.border = "1px solid #ccc";
    startTime.style.border = "1px solid #ccc";
    endTime.style.border = "1px solid #ccc";

    if (title.value.trim() === '') {
        title.style.border = "1px solid red";
        return false;
    }

    if (date.value.trim() === '') {
        date.style.border = "1px solid red";
        return false;
    }

    if (end_date.value.trim() !== "") {
        if (end_date.value.trim() <= date.value.trim()) {
            date.style.border = "1px solid red";
            end_date.style.border = "1px solid red";
            return false;
        }
    }


    if (is_full_day == 0) {
        if (startTime.value.trim() === '') {
            startTime.style.border = "1px solid red";
            return false;
        }
        if (endTime.value.trim() === '') {
            endTime.style.border = "1px solid red";
            return false;
        }
        if (endTime.value.trim() <= startTime.value.trim()) {
            startTime.style.border = "1px solid red";
            endTime.style.border = "1px solid red";
            return false;
        }
    }
    return true;
}

// Funktion zum Anzeigen von Termininformationen in einem Modal
function DisplayEventsModel(id) {
    fetch("get_appointment.php?id=" + id)
        .then(response => response.json())
        .then(appointment => {
            document.getElementById("displayTitle").textContent = appointment.title;
            document.getElementById("displayDate").textContent = appointment.date;
            document.getElementById("displayCategory").textContent = appointment.name;

            if (appointment.description != "") {
                document.getElementById("displayDescription").textContent = appointment.description;
                document.getElementById("Description_display").style.display = "flex";

            } else {
                document.getElementById("Description_display").style.display = "none";
            }

            if (appointment.end_date != null) {
                document.getElementById("displayend_date").textContent = appointment.end_date;
                document.getElementById("end_datedisplay").style.display = "flex";

            } else {
                document.getElementById("end_datedisplay").style.display = "none";
            }

            if (appointment.is_full_day == 0) {
                document.getElementById("timeInputsdisplay").style.display = "block";
                document.getElementById("showFullDay").textContent = "NO";
                document.getElementById("displayStartTime").textContent = appointment.start_time;
                document.getElementById("displayEndTime").textContent = appointment.end_time;

            } else {
                document.getElementById("timeInputsdisplay").style.display = "none";
                document.getElementById("showFullDay").textContent = "YES";
            }

            document.getElementById("displayModal").style.display = "block";
        })
        .catch(error => console.error('Error fetching appointment:', error));
}

// Funktion zum Schließen des Modals
function closeDisplayEventsModel() {
    document.getElementById("displayModal").style.display = "none";
}

// Funktion zum Löschen eines Termins
function deleteAppointment(id) {
    if (confirm("Sind Sie sicher, dass Sie diesen Termin löschen möchten?")) {
        $.ajax({
            url: 'delete_appointment.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function (response) {
                if (response === 'success') {
                    $("#message").removeClass('error').addClass('success').text(
                        'Termin erfolgreich gelöscht.').fadeIn().delay(2000).fadeOut();
                    // Refresh the page after a short delay
                    setTimeout(function () {
                        window.location.href = "index.php";
                    }, 2000); // Wait for 2 seconds before redirecting
                } else if (response === 'error') {
                    $("#message").removeClass('success').addClass('error').text(
                        'Löschen des Termins fehlgeschlagen.').fadeIn().delay(2000).fadeOut();
                } else {
                    $("#message").removeClass('success').addClass('error').text(
                        'Ungültige Antwort vom Server.').fadeIn().delay(2000).fadeOut();
                }
            },
            error: function (xhr, status, error) {
                // Display error message
                $("#message").removeClass('success').addClass('error').text(
                    'Löschen des Termins fehlgeschlagen.').fadeIn().delay(2000).fadeOut();
            }
        });
    }
}

// Funktionen zum Öffnen des Modals für das Hinzufügen von Terminen
function openAddModal() {
    document.getElementById("title").value = "";
    document.getElementById("date").value = "";
    document.getElementById("description").value = "";
    document.getElementById("end_date").value = "";
    document.getElementById("start_time").value = "";
    document.getElementById("end_time").value = "";
    document.getElementById("full_day_no").selected = true;
    document.getElementById("addModal").style.display = "block";
}

// Funktion zum Schließen des Modals
function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}

// Funktion zum Absenden des Formulars zum Hinzufügen eines Termins
function submitAddAppointment(event) {

    event.preventDefault();

    var title = document.getElementById("title");
    var date = document.getElementById("date");
    var end_date = document.getElementById("end_date");
    var isFullDayRadios = document.getElementsByName("is_full_day");
    var is_full_day;
    for (var i = 0; i < isFullDayRadios.length; i++) {
        if (isFullDayRadios[i].checked) {
            is_full_day = isFullDayRadios[i].value;
            break;
        }
    }
    var startTime = document.getElementById("start_time");
    var endTime = document.getElementById("end_time");

    title.style.border = "1px solid #ccc";
    date.style.border = "1px solid #ccc";
    end_date.style.border = "1px solid #ccc";
    startTime.style.border = "1px solid #ccc";
    endTime.style.border = "1px solid #ccc";

    if (title.value.trim() === '') {
        title.style.border = "1px solid red";
        return;
    }

    if (date.value.trim() === '') {
        date.style.border = "1px solid red";
        return;
    }

    if (end_date.value.trim() != '') {
        if (end_date.value.trim() <= date.value.trim()) {
            date.style.border = "1px solid red";
            end_date.style.border = "1px solid red";
            return;
        }
    }

    if (is_full_day == 0) {
        if (startTime.value.trim() === '') {
            startTime.style.border = "1px solid red";
            return;
        }
        if (endTime.value.trim() === '') {
            endTime.style.border = "1px solid red";
            return;
        }
        if (endTime.value.trim() <= startTime.value.trim()) {
            startTime.style.border = "1px solid red";
            endTime.style.border = "1px solid red";
            return;
        }
    }

    var formData = new FormData(document.getElementById("addForm"));

    $.ajax({
        type: "POST",
        url: "add_appointment.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.trim() === 'success') {
                document.getElementById("addModal").style.display = "none";
                $("#message").removeClass('error').addClass('success').text(
                    'Termin erfolgreich hinzugefügt.').fadeIn().delay(2000).fadeOut();
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            } else if (response.trim() === 'error_day') {
                $("#message").removeClass('success').addClass('error').text(
                    'Der Tag hat bereits einen ganztägigen Termin.').fadeIn().delay(2000).fadeOut();
            } else if (response.trim() === 'error_time') {
                $("#message").removeClass('success').addClass('error').text(
                    'Der Tag hat bereits Termine innerhalb dieses Zeitrahmens.').fadeIn().delay(2000).fadeOut();
            }
            else {

                $("#message").removeClass('success').addClass('error').text('Termin konnte nicht hinzugefügt werden.')
                    .fadeIn().delay(2000).fadeOut();
            }
        },
        error: function (xhr, status, error) {

            var errorMessage = xhr.responseText;
            $("#message").removeClass('success').addClass('error').text(errorMessage).fadeIn().delay(
                2000).fadeOut();
        }
    });
}
