# Bitrix Module and Component "Request Ratings"

## Technical Specification

### Task:

Develop a Bitrix module "Request Ratings".
The module consists of an administrative interface and a component.

### Objective: Gather client ratings based on 3 criteria:

- Interaction with the operator (1–5).
- Politeness (1–5).
- Speed and accuracy of responses (1–5).

### Data:

The module must store responses and requests in its own database tables.

### Public Section:

- The component should accept the ID of the request as a parameter.
- The visual design of the component and rating interface should include five stars.
- The stars should visually correspond to the assigned rating, and the request number should be displayed.
- Ratings are set by clicking on the stars and processed via an asynchronous request.

### Installation and Uninstallation:

The module must include an installer that creates the component and database tables.
A deinstaller should remove the tables and the component.

### Module Management:

The module should have a settings page where user group permissions can be configured.

### Data Management:

The module should include a menu item in the "Marketing" section, named "Request Ratings", which leads to a page displaying the list of submitted ratings.
The ratings should be displayed as a list with pagination.
Search functionality should allow searching by request number.

## Screenshots

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_IIbbtVcrgB.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_f8h6lkURlA.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_Bj72fK8S1H.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_wbdNhfCKlM.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_sP3uEg0eWP.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_metUXAcGNH.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_OTDK1mumQP.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_6Si1wN4sUr.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_jXTe2d3aRe.png" alt="">

<img src="https://github.com/mizuhomizuho/bitrix-module-appeal-assessments/blob/master/screenshots/chrome_kP7qqqgykL.png" alt="">
