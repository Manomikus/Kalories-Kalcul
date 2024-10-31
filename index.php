<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kcalculateur - BMR et Calories de l'Assiette</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #1e1e1e, #282828);
        color: #f4f4f4;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
        text-align: center;
    }

    .container {
        width: 90%;
        max-width: 700px;
        padding: 20px;
        background: #333;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
        position: relative;
        display: none;
    }

    h1 {
        color: #ff6f61;
        font-size: 2em;
        margin: 0;
    }

    .intro-message {
        color: #f4f4f4;
        font-size: 1.5em;
        margin-bottom: 20px;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 1s ease, transform 1s ease;
    }

    .intro-message.show {
        opacity: 1;
        transform: translateY(0);
    }

    label,
    legend {
        color: #ff6f61;
    }

    input,
    select,
    button {
        width: calc(100% - 20px);
        padding: 10px;
        margin-top: 5px;
        font-size: 1rem;
        border-radius: 4px;
        border: none;
        background: #444;
        color: #f4f4f4;
        display: inline-block;
        vertical-align: middle;
    }

    button {
        background-color: #ff6f61;
        cursor: pointer;
        transition: background 0.3s, transform 0.3s;
    }

    button:hover {
        background-color: #ff5040;
        transform: scale(1.05);
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
        z-index: 10;
        animation: fadeIn 0.5s, zoomIn 0.5s;
    }

    .modal-content {
        background: #333;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 400px;
        color: #f4f4f4;
        text-align: center;
    }

    .modal-content h2 {
        color: #4caf50;
    }

    .close-btn {
        background-color: #ff6f61;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .close-btn:hover {
        background-color: #ff5040;
    }

    #assietteDetails {
        margin-top: 20px;
        padding: 10px;
        background: #222;
        border: 1px solid #555;
        border-radius: 8px;
    }

    #assietteDetails h3 {
        color: #4caf50;
        font-size: 1.2em;
    }

    #assietteItems p {
        margin: 5px 0;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.9);
        }

        to {
            transform: scale(1);
        }
    }
    </style>
</head>

<body>

    <!-- Message d'accueil -->
    <div class="intro-message" id="welcomeMessage">Bonjour, bienvenue sur ton super Kcalculateur</div>
    <div class="intro-message" id="instructionMessage" style="display: none;">Tout d'abord, calculons ton BMR et tes
        besoins quotidiens en protéines</div>

    <!-- Section de Calcul du BMR -->
    <div class="container" id="bmrContainer">
        <h1>Kcalculateur</h1>

        <div class="form-section">
            <label for="sexe">Sexe :</label>
            <select name="sexe" id="sexe" required>
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>

            <label for="poids">Poids (kg) :</label>
            <input type="number" name="poids" id="poids" step="0.1" required>

            <label for="taille">Taille (cm) :</label>
            <input type="number" name="taille" id="taille" required>

            <label for="age">Âge :</label>
            <input type="number" name="age" id="age" required>

            <label for="activity_level">Niveau d'activité :</label>
            <select name="activity_level" id="activity_level" required>
                <option value="sedentary">Sédentaire</option>
                <option value="active">Actif</option>
                <option value="very_active">Très actif</option>
            </select>

            <button type="button" onclick="calculateBMR()">Calculer le BMR et les Besoins en Protéines</button>
        </div>
    </div>

    <!-- Modal pour afficher le résultat du BMR -->
    <div class="modal" id="bmrResultModal">
        <div class="modal-content">
            <h2 id="bmrResult"></h2>
            <button class="close-btn" onclick="goToAssietteForm()">Calculer les Calories de l'Assiette</button>
        </div>
    </div>

    <!-- Section de Calcul des Calories de l'Assiette -->
    <div class="container" id="assietteContainer" style="display:none;">
        <h1>Kcalculateur</h1>
        <div class="intro-message" id="assietteMessage" style="display: none;">Calculons maintenant ensemble ton
            assiette</div>

        <div class="form-section" id="assietteSection">
            <fieldset>
                <legend>Protéines</legend>
                <div id="proteine-section"></div>
                <button type="button" onclick="addItem('proteine')">Ajouter une protéine</button>
            </fieldset>

            <fieldset>
                <legend>Légumes</legend>
                <div id="legume-section"></div>
                <button type="button" onclick="addItem('legume')">Ajouter un légume</button>
            </fieldset>

            <fieldset>
                <legend>Féculents</legend>
                <div id="feculent-section"></div>
                <button type="button" onclick="addItem('feculent')">Ajouter un féculent</button>
            </fieldset>

            <button type="button" onclick="calculateAssiette()" style="margin-top: 10px;">Avis du Pro ?</button>
        </div>

        <div id="assietteDetails">
            <h3>Votre assiette :</h3>
            <div id="assietteItems"></div>
            <p id="assietteTotal">Total : 0 kcal</p>
        </div>
    </div>

    <!-- Modal pour afficher le résultat final du calcul de l'assiette -->
    <div class="modal" id="assietteResultModal">
        <div class="modal-content">
            <h2 id="assietteResult"></h2>
            <button class="close-btn" onclick="closeAssietteModal()">OK</button>
        </div>
    </div>

    <script>
    let bmrValue = 0;
    let besoinsProteines = 0;
    let alimentsChoisis = [];
    let totalCaloriesAssiette = 0;

    // Données des aliments
    const kcalProteine = {
        "oeuf": 150,
        "poulet": 120,
        "boeuf": 130,
        "seitan": 100,
        "lentilles": 330,
        "crevettes": 95,
        "thon": 130,
        "saumon": 180,
        "fromage": 200,
        "whey": 375,
        "skyr": 55,
        "haricots": 320,
        "tofu": 125,
        "agneau": 250
    };
    const kcalLegume = {
        "tomate": 18,
        "poivron": 26,
        "piment": 318,
        "oignon": 42,
        "carotte": 41,
        "brocoli": 34
    };
    const kcalFeculent = {
        "pates_sans_gluten": 130,
        "riz_blanc": 129,
        "riz_complet": 112,
        "pain_complet": 247,
        "pomme_de_terre": 77
    };

    document.addEventListener("DOMContentLoaded", () => {
        const welcomeMessage = document.getElementById("welcomeMessage");
        const instructionMessage = document.getElementById("instructionMessage");

        setTimeout(() => {
            welcomeMessage.classList.add("show");
            setTimeout(() => {
                instructionMessage.style.display = "block";
                setTimeout(() => {
                    instructionMessage.classList.add("show");
                    document.getElementById("bmrContainer").style.display = "block";
                }, 1000);
            }, 1500);
        }, 500);
    });

    function calculateBMR() {
        const sexe = document.getElementById("sexe").value;
        const poids = parseFloat(document.getElementById("poids").value);
        const taille = parseFloat(document.getElementById("taille").value);
        const age = parseInt(document.getElementById("age").value);
        const activityLevel = document.getElementById("activity_level").value;

        // Calcul du BMR en fonction du sexe
        bmrValue = sexe === "homme" ?
            10 * poids + 6.25 * taille - 5 * age + 5 :
            10 * poids + 6.25 * taille - 5 * age - 161;

        // Calcul des besoins en protéines (en grammes) selon le niveau d'activité
        let activityMultiplier;
        if (activityLevel === "sedentary") {
            activityMultiplier = 0.8;
        } else if (activityLevel === "active") {
            activityMultiplier = 1.2;
        } else {
            activityMultiplier = 1.6;
        }

        besoinsProteines = poids * activityMultiplier;

        // Afficher le résultat du BMR et des besoins en protéines dans le modal
        document.getElementById("bmrResult").innerHTML =
            `Votre BMR est de ${bmrValue.toFixed(1)} kcal/jour.<br>Besoin en protéines : ${besoinsProteines.toFixed(1)} g/jour.`;
        openModal("bmrResultModal");
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = "flex";
    }

    function goToAssietteForm() {
        // Fermer le modal de résultat du BMR et afficher le formulaire de l'assiette
        closeModal("bmrResultModal");
        document.getElementById("bmrContainer").style.display = "none"; // Masquer le formulaire BMR
        document.getElementById("instructionMessage").style.display =
            "none"; // Masquer uniquement le texte d'instruction
        document.getElementById("assietteContainer").style.display = "block"; // Afficher le formulaire des calories
        const assietteMessage = document.getElementById("assietteMessage");
        assietteMessage.style.display = "block";
        setTimeout(() => {
            assietteMessage.classList.add("show");
        }, 500);
    }

    function closeAssietteModal() {
        closeModal("assietteResultModal");
        alimentsChoisis = [];
        totalCaloriesAssiette = 0;
        updateAssietteDisplay();
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    function addItem(type) {
        const section = document.getElementById(`${type}-section`);
        const div = document.createElement("div");
        div.classList.add("item");

        const data = type === 'proteine' ? kcalProteine : type === 'legume' ? kcalLegume : kcalFeculent;
        div.innerHTML = `
            <select name="${type}" onchange="updateSelection('${type}', this)">
                ${Object.keys(data).map(aliment =>
                    `<option value="${aliment}">${aliment.charAt(0).toUpperCase() + aliment.slice(1)} - ${data[aliment]} kcal/100g</option>`
                ).join('')}
            </select>
            <input type="number" placeholder="Quantité (g)" onchange="updateSelection('${type}', this.previousElementSibling)" required>
        `;
        section.appendChild(div);
    }

    function updateSelection(type, element) {
        const aliment = element.value;
        const quantite = element.nextElementSibling.value;
        if (aliment && quantite) {
            const kcalData = type === 'proteine' ? kcalProteine : type === 'legume' ? kcalLegume : kcalFeculent;
            const calories = (kcalData[aliment] / 100) * quantite;
            alimentsChoisis.push({
                aliment,
                quantite: parseFloat(quantite),
                calories
            });
            totalCaloriesAssiette += calories;

            updateAssietteDisplay();

            element.parentElement.style.display = "none";
        }
    }

    function updateAssietteDisplay() {
        const assietteItems = document.getElementById("assietteItems");
        assietteItems.innerHTML = alimentsChoisis.map(item =>
            `<p>${item.quantite}g de ${item.aliment} - ${item.calories.toFixed(1)} kcal</p>`
        ).join('');
        document.getElementById("assietteTotal").innerHTML = `Total : ${totalCaloriesAssiette.toFixed(1)} kcal`;
    }

    function calculateAssiette() {
        const avis = totalCaloriesAssiette > bmrValue ?
            "Votre assiette dépasse vos besoins journaliers." :
            "Votre assiette est en dessous de vos besoins journaliers.";

        document.getElementById("assietteResult").innerHTML =
            `Votre assiette contient un total de ${totalCaloriesAssiette.toFixed(1)} kcal. ${avis}`;

        openModal("assietteResultModal");
    }
    </script>
</body>

</html>