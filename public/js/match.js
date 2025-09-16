document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("matchForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/public/index.php?controller=match&action=add", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let newRow = `
                    <tr>
                        <td>${data.match.match_id}</td>
                        <td>${data.match.match_date}</td>
                        <td>${data.match.match_time}</td>
                        <td>${data.match.location}</td>
                        <td>${data.match.match_fee}</td>
                    </tr>`;
                document.getElementById("matchTable").insertAdjacentHTML("afterbegin", newRow);
                document.getElementById("matchForm").reset();
            } else {
                alert("Maç eklenemedi.");
            }
        })
        .catch(error => console.error("Hata:", error));
    });
});
