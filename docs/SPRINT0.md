# Sprint 0 – Invulbaar Template (Markdown)

> **Gebruik:** Dupliceer dit bestand voor je eigen project. Vervang alle `<>`-velden en vink de checkboxes aan.  
> **Vaststaand (mag je overnemen):** sprints van 1 week, fysieke meetings in *Lokaal Mars (Bit Academy)*, stand-ups fysiek in het lokaal, communicatie via Mattermost, retro in sprint 2 & 4.  
> **Tip:** Bewaar dit bestand in je repo als `SPRINT0.md`.
---

## 📌 Project

- **Projectnaam:** `Backenders-EduReuse`  
- **Korte omschrijving (1–2 zinnen):** `Wij bouwen een webapplicatie waarbij scholen hun gebruikte apparatuur kunnen aanmelden/ophalen, zodat eleketronica een tweede leven kan krijgen`

---

## 🧑‍🤝‍🧑 Wie

| Rol                       | Naam                 | E-mail                         | Telefoon     |
| ------------------------- | -------------------- | ------------------------------ | ------------ |
| **Product Owner (klant)** | `EduReuse` | `<mail@klant.nl>`              | `<06-xxxx>`  |
| **Scrum Coach**           | `Ties Noordhuis`     | `ties@bit-academy.nl`          | `06-29313578`|
| **Developer 1**           | `Dymoreno Milan`             | `dymorenomilan@gmail.com`    | `06-xxxx`  |
| **Developer 2**           | `Giorgio de Rijp`             | `gderijp@gmail.com`    | `06-48656328`  |
| **Developer 3**           | `Jenebi Owini Deel`             | `<voornaam.achternaam@...>`    | `<06-xxxx>`  |

---

## 🎯 Wat

### Doel van het project
Scholen hebben regelmatig afgeschreven of ongebruikte apparatuur (laptops, 3D-printers, educatieve robots). Wij willen een prototype van een website waar scholen:

1. E-waste (herbruikbare educatieve hardware) kunnen **aanmelden /opgeven**,
2. Andere scholen **hun behoefte** kunnen aangeven,
3. Ons als facilitator (Wailsalutem Foundation) **tussenzetten** voor verificatie, matching, ophalen en herverdeling,
4. Informatie vinden over **duurzaam hergebruik** en **e-waste-bewustwording**.

### Eisen en wensen

**Eisen (Must-haves):**

**F1. Aanbod & Aanvraag**

- [ ] DonorSchool kan items aanmaken: type (laptop/3D-printer/robot), merk/model, staat, hoeveelheid, foto(‘s), opmerkingen, locatie (stad/postcode).
- [ ] NeedSchool kan een behoefte plaatsen: gewenste type/hoeveelheid, locatie, deadline/prioriteit.

**F2. Matching (semi-handmatig in MVP)**

- [ ] Admin ziet een lijst met **open aanbod** en **open behoeften** en kan een **match** aanmaken (1-op-1 of 1-op-meerdere).
- [ ] Basic matchscore (heuristiek): type + afstand + hoeveelheid + staat.

**F3. Workflow & Status**

- [ ] Status per item: *Nieuw → In verificatie → Beschikbaar → Gematcht → Ophalen gepland → Opgehaald → Refurbish → Geleverd → Afgerond*.
- [ ] Log van statusupdates zichtbaar voor betrokkenen.

**F4. Authenticatie & Rollen**

- [ ] Simpele login (email+code of demo-login knoppen) met rol: DonorSchool, NeedSchool, Admin.
- [ ] Alleen Admin kan matches en statussen wijzigen (buiten eigen items/aanvragen).

**F5. Informatiepagina’s**

- [ ] Pagina’s met uitleg over e-waste, hergebruik, dataveilig wissen (links/infoblokken), en impact (aantal herverdeelde apparaten in de demo).

**F6. Zoeken & Filters**

- [ ] Filter op type, staat, afstand (op basis van postcode/plaats), hoeveelheid, status.

**F7. Demo-Data & Seed**

- [ ] Script of JSON om snel demo-records te laden (zie “Voorbeelddataset”).
- [ ] **Responsief** (mobiel & desktop)

**Wensen (Nice-to-haves):**
- [ ] `Foto-upload (desnoods base64 of lokale storage in /uploads).`
- [ ] `PDF/CSV-export van matched items.`
- [ ] `Simpele notificaties (e-mailstub of in-app banners).`
- [ ] `Kaartweergave (OpenStreetMap) met grove locaties (stad-niveau).`
- [ ] `Meertalig (NL/EN) toggle.`
- [ ] `Analytics/impact-teller (CO₂-/e-waste-besparing schatting).`

---

## 🧍‍♀️ User Stories (MoSCoW)

> **Format:** *Als \<rol\> wil ik \<actie\> zodat \<waarde\>.*  
> **Minimaal 5 stories.** Zet elke story in de juiste MoSCoW-categorie.

### ✅ Must have (M)

- [ ] Als DonorSchool wil ik apparaten aanmelden zodat ze hergebruikt worden.

- [ ] Als NeedSchool wil ik een behoefte indienen zodat ik passende apparatuur kan ontvangen.


- [ ] Als Admin wil ik vraag en aanbod koppelen en een statusflow beheren.

- [ ] Als Admin wil ik een ophaalmoment toevoegen.

- [ ] Als bezoeker wil ik informatie over e-waste hergebruik.

- [ ] Als gebruiker wil ik lijstjes kunnen filteren op type/staat/locatie.

### 🟠 Should have (S)
- [ ] `<Story S1>`  
- [ ] `<Story S2>`

### 🔵 Could have (C)
- [ ] `Foto-upload (desnoods base64 of lokale storage in /uploads).`
- [ ] `PDF/CSV-export van matched items.`
- [ ] `Simpele notificaties (e-mailstub of in-app banners).`
- [ ] `Kaartweergave (OpenStreetMap) met grove locaties (stad-niveau).`
- [ ] `Meertalig (NL/EN) toggle.`
- [ ] `Analytics/impact-teller (CO₂-/e-waste-besparing schatting).`

### ⚫ Won’t have (W) (nu niet / later misschien)
- [ ] `<Story W1>`

> **Voorbeeld (niet automatisch overnemen):**  
> *Als bezoeker wil ik een **homepagina** zien zodat ik kan leren wat de bakkerij biedt.* (M)  
> *Als beheerder wil ik **producten kunnen toevoegen/bewerken** zodat de lijst up-to-date blijft.* (S)

---

## 🗓️ Wanneer

### Sprints
- Duur per sprint: **1 week**
- Startdatum Sprint 1: `17-11-2025`
- Totale duur traject (optioneel): `4/5`

### Meetings (fysiek – Lokaal Mars, Bit Academy)

| Type                                      | Datum         | Tijd   | Locatie                   |
| ----------------------------------------- | ------------- | ------ | ------------------------- |
| Sprint 1 **planning**                      | `<dd mmm yyyy>` | `10:00` | Lokaal Mars               |
| Sprint 2 **review + planning + retro**     | `<dd mmm yyyy>` | `10:00` | Lokaal Mars               |
| Sprint 3 **planning + halfway presentatie**| `<dd mmm yyyy>` | `10:00` | Lokaal Mars               |
| Sprint 4 **review + planning + retro**     | `<dd mmm yyyy>` | `10:00` | Lokaal Mars               |
| Sprint 5 **review + eindpresentatie**      | `<dd mmm yyyy>` | `10:00` | Lokaal Mars               |

> Pas data aan op basis van jouw kalender. Alle meetings zijn fysiek, tenzij anders afgesproken met de coach/klant.

### Dagelijkse stand-up
- Tijd: **09:00** (max. 15 min)
- Locatie: **fysiek in Lokaal Mars**
- Doel: *Gisteren / Vandaag / Blokkades*

### Teaminzet & beschikbaarheid
- **Minimale aanwezigheid:** **di, wo, do** voor alle teamleden
- Overzicht per persoon:
  - `Dymoreno`: `ma-do/9-15:30`
  - `Giorgio`: `ma-do/9-15:30`
  - `Jenebi`: `ma-do/9-15:30`

**Afwezigheid & afspraken**
- [ ] Ziek/afwezig vóór **08:30** melden via **Mattermost**
- [ ] Te laat? Direct melden in **teamchat**
- [ ] Bij langdurige afwezigheid: taken herverdelen (Scrum Master)

**Communicatiekanalen**
- **Team:** Mattermost
- **Code & planning:** GitHub Projects (Bit Academy template)
- **Klantcontact:** e-mail (`<mail klant>`)

---

## ⚙️ Hoe

### Definition of Ready (DoR)
Een user story is *Ready* als:
- [ ] Doel & acceptatiecriteria zijn duidelijk
- [ ] Ontwerp/wireframe of voorbeeld beschikbaar (indien van toepassing)
- [ ] Benodigde data/API’s bekend
- [ ] Team kan inschatten (story points/t-shirt size)
- [ ] Past binnen sprintcapaciteit

### Definition of Done (DoD)
Een user story is *Done* als:
- [ ] Werkt functioneel en technisch (incl. tests)
- [ ] Peer review gedaan (min. 1 teamlid)
- [ ] Voldoet aan acceptatiecriteria & design
- [ ] Documentatie bijgewerkt (README/handleiding)
- [ ] Ge-merged naar **main**

---

## 🔧 Scrum werkwijze

- [ ] Iedere sprint **eindigt met review** en **start met planning** (achter elkaar, met klant)
- [ ] **Retrospectives** in **Sprint 2** en **Sprint 4**
- [ ] Taken & user stories via **GitHub Projects**  
  Template: https://github.com/orgs/Bit-Academy-Students/projects/1
- [ ] Communicatie: **Mattermost** voor team, e-mail voor klant
- [ ] **Definition of Ready** en **Definition of Done** bekend bij alle teamleden

---

## 📎 Bijlagen (optioneel)

- Link naar repo: `https://github.com/Bit-Academy-Students/Backenders-EduReUse/blob/main/docs/sprint-0-template.md`  
- Link naar designs/wireframes: `<url>`  
- Link naar product backlog: `https://github.com/orgs/Bit-Academy-Students/projects/14`  
- Contactgegevens klant (adres/locatie): `<adres>`