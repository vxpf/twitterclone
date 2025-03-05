function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}



let berichten = [];


function postBericht() {
    const berichtInput = document.getElementById('berichtInput');
    const berichtTekst = berichtInput.value.trim();

    if (berichtTekst !== "") {
       
        berichten.push(berichtTekst);
      
        toonBerichten();
        
        berichtInput.value = "";
    } else {
        alert("Voer een bericht in!");
    }
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