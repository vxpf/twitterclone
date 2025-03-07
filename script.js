function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

let berichten = [];

function postBericht() {
    const berichtInput = document.getElementById('berichtInput');
    const berichtenLijst = document.getElementById('berichtenLijst');

    const berichtDiv = document.createElement('div');
    berichtDiv.className = 'bericht';

    const commentText = document.createElement('span');
    commentText.className = 'comment-text';
    commentText.textContent = berichtInput.value;

    const editButton = document.createElement('button');
    editButton.textContent = 'Edit';
    editButton.onclick = function() {
        editBericht(editButton);
    };

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.onclick = function() {
        deleteBericht(deleteButton);
    };

    berichtDiv.appendChild(commentText);
    berichtDiv.appendChild(editButton);
    berichtDiv.appendChild(deleteButton);

    berichtenLijst.appendChild(berichtDiv);

    berichtInput.value = '';
}

function toonBerichten() {
    const berichtenLijst = document.getElementById('berichtenLijst');
    berichtenLijst.innerHTML = ""; 

    berichten.forEach((bericht, index) => {
        const berichtDiv = document.createElement('div');
        berichtDiv.classList.add('bericht');

        const berichtTekst = document.createElement('p');
        berichtTekst.textContent = bericht;
        berichtDiv.appendChild(berichtTekst);

        const verwijderKnop = document.createElement('button');
        verwijderKnop.textContent = "Verwijder";
        verwijderKnop.onclick = () => verwijderBericht(index);
        berichtDiv.appendChild(verwijderKnop);

        berichtenLijst.appendChild(berichtDiv);
    });
}

function verwijderBericht(index) {
    berichten.splice(index, 1); 
    toonBerichten(); 
}

function toggleMenu() {
    var menu = document.getElementById("menu");
    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}

function editBericht(button) {
    const berichtDiv = button.parentElement;
    const commentText = berichtDiv.querySelector('.comment-text');
    const newText = prompt('Edit your comment:', commentText.textContent);
    if (newText !== null) {
        commentText.textContent = newText;
    }
}

function deleteBericht(button) {
    const berichtDiv = button.parentElement;
    berichtDiv.remove();
}