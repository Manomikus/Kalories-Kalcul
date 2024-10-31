let bmrValue = 0;
let besoinsProteines = 0;
let alimentsChoisis = [];
let totalCaloriesAssiette = 0;

// Données des aliments
const kcalProteine = {
  oeuf: 150,
  poulet: 120,
  boeuf: 130,
  seitan: 100,
  lentilles: 330,
  crevettes: 95,
  thon: 130,
  saumon: 180,
  fromage: 200,
  whey: 375,
  skyr: 55,
  haricots: 320,
  tofu: 125,
  agneau: 250,
};
const kcalLegume = {
  tomate: 18,
  poivron: 26,
  piment: 318,
  oignon: 42,
  carotte: 41,
  brocoli: 34,
};
const kcalFeculent = {
  pates_sans_gluten: 130,
  riz_blanc: 129,
  riz_complet: 112,
  pain_complet: 247,
  pomme_de_terre: 77,
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
  bmrValue =
    sexe === "homme"
      ? 10 * poids + 6.25 * taille - 5 * age + 5
      : 10 * poids + 6.25 * taille - 5 * age - 161;

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
  document.getElementById(
    "bmrResult"
  ).innerHTML = `Votre BMR est de ${bmrValue.toFixed(
    1
  )} kcal/jour.<br>Besoin en protéines : ${besoinsProteines.toFixed(
    1
  )} g/jour.`;
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
  document.getElementById("instructionMessage").style.display = "none"; // Masquer uniquement le texte d'instruction
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

  const data =
    type === "proteine"
      ? kcalProteine
      : type === "legume"
      ? kcalLegume
      : kcalFeculent;
  div.innerHTML = `
        <select name="${type}" onchange="updateSelection('${type}', this)">
            ${Object.keys(data)
              .map(
                (aliment) =>
                  `<option value="${aliment}">${
                    aliment.charAt(0).toUpperCase() + aliment.slice(1)
                  } - ${data[aliment]} kcal/100g</option>`
              )
              .join("")}
        </select>
        <input type="number" placeholder="Quantité (g)" onchange="updateSelection('${type}', this.previousElementSibling)" required>
    `;
  section.appendChild(div);
}

function updateSelection(type, element) {
  const aliment = element.value;
  const quantite = element.nextElementSibling.value;
  if (aliment && quantite) {
    const kcalData =
      type === "proteine"
        ? kcalProteine
        : type === "legume"
        ? kcalLegume
        : kcalFeculent;
    const calories = (kcalData[aliment] / 100) * quantite;
    alimentsChoisis.push({
      aliment,
      quantite: parseFloat(quantite),
      calories,
    });
    totalCaloriesAssiette += calories;

    updateAssietteDisplay();

    element.parentElement.style.display = "none";
  }
}

function updateAssietteDisplay() {
  const assietteItems = document.getElementById("assietteItems");
  assietteItems.innerHTML = alimentsChoisis
    .map(
      (item) =>
        `<p>${item.quantite}g de ${item.aliment} - ${item.calories.toFixed(
          1
        )} kcal</p>`
    )
    .join("");
  document.getElementById(
    "assietteTotal"
  ).innerHTML = `Total : ${totalCaloriesAssiette.toFixed(1)} kcal`;
}

function calculateAssiette() {
  const avis =
    totalCaloriesAssiette > bmrValue
      ? "Votre assiette dépasse vos besoins journaliers."
      : "Votre assiette est en dessous de vos besoins journaliers.";

  document.getElementById(
    "assietteResult"
  ).innerHTML = `Votre assiette contient un total de ${totalCaloriesAssiette.toFixed(
    1
  )} kcal. ${avis}`;

  openModal("assietteResultModal");
}
