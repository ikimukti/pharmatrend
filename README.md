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
3. Write the command `git clone https://github.com/ikimukti/skripsi-arip.git`.
4. Change the current working directory to the cloned directory `cd skripsi-arip`.
5. Open the project in Visual Studio Code with the command `code .`.
6. `npm install -D tailwindcss@latest postcss@latest autoprefixer@latest` to install tailwindcss

    ```text
    added 92 packages, and audited 93 packages in 2s

    19 packages are looking for funding
    run `npm fund` for details

    found 0 vulnerabilities
    ```

7. `npx tailwindcss init -p` to create tailwind.config.js

    ```text
    tailwindcss 2.2.7

    ✅ Created Tailwind config file: tailwind.config.js
    ✅ Created PostCSS config file: postcss.config.js
    ```

    or

    ```text
    tailwind.config.js already exists.
    postcss.config.js already exists.
    ```

8. `npx tailwindcss build src/css/input.css -o public/css/output.css` to create public/styles.css

    ```text
    tailwindcss 2.2.7

    ✅ Generated public/css/output.css successfully.
    ```

    or

    ```text
    public/css/output.css already exists.
    ```

9. `npm run build` to create public/bundle.js or `npm run dev` to create public/bundle.js and watch for changes
10. Open the project in Visual Studio Code with the command `code .`
11. Run XAMPP and start Apache and MySQL
12. Open the browser and go to `http://localhost/phpmyadmin/`
13. Create a database with the name `skripsi-arip`
14. import the database file `skripsi-arip.sql` in the database folder
15. Open web browser icognito mode and go to `http://localhost/skripsi-arip/public/`
