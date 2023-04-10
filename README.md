# Skripsi Arip - Web Application for Thesis Process

Skripsi Arip is a web application that is used to help the process of making a thesis. This web application of K-Means method and Trend Moment for drug stock prediction at PT. Lestari Jaya Farma (LJF) in the city of Kediri. This web application is made using the MERN stack (MySQL, PHP, NodeJS) and Tailwind CSS.

## Preparation for the project

1. Install NodeJS, website: <https://nodejs.org/en/>
2. Install XAMPP, website: <https://www.apachefriends.org/download.html>
3. Install Git, website: <https://git-scm.com/downloads>
4. Install Visual Studio Code, website: <https://code.visualstudio.com/download>
5. Install Extensions Taiwind CSS IntelliSense, website: <https://marketplace.visualstudio.com/items?itemName=bradlc.vscode-tailwindcss>
6. Install Extensions Live Server, website: <https://marketplace.visualstudio.com/items?itemName=ritwickdey.LiveServer>
7. Install Extensions PostCSS Language Support, website: <https://marketplace.visualstudio.com/items?itemName=csstools.postcss>

## How to clone the project

1. Open the terminal
2. Change the current working directory to the location where you want the cloned directory.
3. Write the command `git clone https://github.com/ikimukti/skripsi-arip.git`
4. Open the project in Visual Studio Code with the command `code .`
5. `npm install -D tailwindcss@latest postcss@latest autoprefixer@latest` to install tailwindcss
6. `npx tailwindcss init -p` to create tailwind.config.js
7. `npx tailwindcss build src/styles.css -o public/styles.css` to create public/styles.css
8. `npm run build` to create public/bundle.js
